<?php

namespace LibraMenu\Widget;

use Zend\View\Model\ViewModel;
/**
 * Description of Menu
 *
 * @author duke
 */
class Menu
{
    protected $tableGateway;


    public function addMenu($e)
    {
        $resultSet = array(
            array(
                'title' => 'libra-app-index',
                'controller' => 'libra-app-index',
                'alias' => 'libra-app-index',
            ),
            array(
                'title' => 'libra-article-index',
                'controller' => 'libra-article-index',
                'alias' => 'libra-article-index',
            )
        );
        $menu = new ViewModel(array(
            'menu'          => $resultSet,
            'routeMatch'    => $e->getRouteMatch()->getParams(),
        ));
        $menu->setTemplate('libra-menu/widget/menu');
        $view  = $e->getViewModel();
        $view->addChild($menu, 'menu');
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
