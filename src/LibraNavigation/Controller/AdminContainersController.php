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
class AdminContainersController extends AbstractActionController
{
    public function viewAction()
    {
        $navigation = include 'config/constructed/navigation.php';
        $navigation = $navigation['navigation'];
        return array(
            'navigation' => $navigation,
        );
    }
}
