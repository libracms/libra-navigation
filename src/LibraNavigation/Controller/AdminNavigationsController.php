<?php

/*
 * eJoom.com
 * This source file is subject to the new BSD license.
 */

namespace LibraNavigation\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Description of AdminNavigationsController
 *
 * @author duke
 */
class AdminNavigationsController extends AbstractActionController
{
    public function viewAction()
    {
        $navigations = include 'config/constructed/navigation.php';
        $navigations = $navigations['navigation'];
        return array(
            'navigations' => $navigations,
        );
    }
}
