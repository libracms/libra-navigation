<?php

/*
 * eJoom.com
 * This source file is subject to the new BSD license.
 */

namespace LibraNavigation\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Description of AdminNavigationController
 *
 * @author duke
 */
class AdminContainerController extends AbstractActionController
{
    public function editAction()
    {
        $name = $this->params('name', 'default');
        //$navigations = include 'config/constructed/navigation.php';
        //$navigation = $navigations['navigation'][$name];
        $navigation = $this->getServiceLocator()->get('navigation');
        return array(
            'navigation' => $navigation,
            'name'       => $name,
        );
    }
}
