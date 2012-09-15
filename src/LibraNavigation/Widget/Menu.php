<?php

namespace LibraNavigation\Widget;

use Zend\View\Model\ViewModel,
    Zend\Mvc\MvcEvent;
/**
 * Description of Menu
 *
 * @author duke
 */
class Menu
{
    protected $tableGateway;


    public function addMenu(MvcEvent $e)
    {
        $phpRenderer = $e->getApplication()->getServiceManager()->get('Zend\View\Renderer\PhpRenderer');
        $pages = array(
            array(
                'label'      => 'Home',
                'title'      => 'Go Home',
                'module'     => 'libra-app',
                'controller' => 'index',
                'action'     => 'index',
                'order'      => -100, // make sure home is the first page
                'route'      => 'home',
                //'urlHelper'  => $this->plugin('url'),
            ),
            array(
                'label'      => 'Special offer this week only!',
                'route'      => 'default',
                'module'     => 'libra-article',
                'controller' => 'index',
                'action'     => 'index',
                //'visible'    => false, // not visible
            ),
            array(
                'label'      => 'Special offer this week only!3',
                'route'      => 'libra-article',
                //'controller' => 'index',
                //'action'     => 'index',
                //'visible'    => false, // not visible
            ),
            array(
                'label'      => 'Special offer this week only!2',
                'uri'        => $phpRenderer->url('libra-article', array()),
            ),
            array(
                'label'      => 'rus article uri',
                'uri'        => $phpRenderer->url('libra-article', array('alias' => 'ru-page')),
            ),
            array(
                'label'      => 'Uri for FAQ',
                'uri'        => $phpRenderer->url('libra-article', array('alias' => 'faq')),
            ),
            array(
                'label'      => 'FAQ',
                'route'      => 'libra-article',
                'controller' => 'index',
                'action'     => 'index',
                'params'     => array(
                    'alias' => 'faq',
                ),
                //'visible'    => false, // not visible
            ),
            array(
                'label'      => 'Rus article',
                'route'      => 'libra-article',
                'controller' => 'index',
                'action'     => 'index',
                'params'     => array(
                    'alias' => 'ru-page',
                ),
                //'visible'    => false, // not visible
            ),
        );

        $router = $e->getRouter();
        \Zend\Navigation\Page\Mvc::setDefaultRouter($router);
        $a = $e->getApplication()->getServiceManager()->get('someFactory');  //test
        $navigation = $e->getApplication()->getServiceManager()->get('Zend\Navigation\Navigation');
        $navigation->addPages($pages);

        //$view  = $e->getViewModel();
        //$view->navigation = $navigation;  // OR $view->widget()->add('navigation', $navigation');
        $helperMenu = $phpRenderer->navigation($navigation)->findHelper('menu');
        $helperMenu->setUlClass('nav nav-list');

        return true;
    }

    public function setTableGateway($tableGateway)
    {
        $this->tableGateway = $tableGateway;
        return $this;
    }

    public function getTableGateway()
    {
        return $this->tableGateway;
    }
}
