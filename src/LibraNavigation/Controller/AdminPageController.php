<?php

/*
 * eJoom.com
 * This source file is subject to the new BSD license.
 */

namespace LibraNavigation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Navigation\Page\Mvc as PageMvc;
use Zend\Navigation\Navigation;

/**
 * List of pages and can edit them
 *
 * @author duke
 */
class AdminPageController extends AbstractActionController
{
    public function editAction()
    {
        $name = 'default';
        $id = $this->params('id', null);
        if ($id === null) return false; //undefined id or create new

        $ids = explode('.', $id);
        $config = $this->getServiceLocator()->get('Config')->get('navigation')->get($name);
        $config = include 'config/constructed/navigation.php';
        $pages = $config['navigation'][$name];
        if (isset($id)) {
            $pages = $pages[$id]['pages'];
        }
        PageMvc::setDefaultRouter($this->getEvent()->getRouter());
        $container = new Navigation($pages);
        return array(
            'container' => $container,
            'name'      => $name,
            'id'        => $id,
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
