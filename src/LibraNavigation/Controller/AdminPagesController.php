<?php

/*
 * eJoom.com
 * This source file is subject to the new BSD license.
 */

namespace LibraNavigation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Navigation\Service\ConstructedNavigationFactory;

/**
 * List of pages and can edit them
 *
 * @author duke
 */
class AdminPagesController extends AbstractActionController
{
    public function listAction()
    {
        $name = $this->params('name', 'default');
        $config = include 'config/constructed/navigation.php';
        $pages = $config['navigation'][$name];
        $container = $this->getServiceLocator()->get('navigation');
        //$container2 = new ConstructedNavigationFactory($pages);
        return array(
            'container' => $container,
            'name'      => $name,
            'pages'     => $container->getPages($this->getServiceLocator()),
        );
    }

    public function deleteAction()
    {

    }

    public function publishAction()
    {

    }

    public function unpublishAction()
    {

    }

}
