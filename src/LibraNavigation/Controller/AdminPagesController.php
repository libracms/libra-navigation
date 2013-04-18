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
class AdminPagesController extends AbstractActionController
{
    public function listAction()
    {
        $name = 'default';
        $id = $this->params()->fromRoute('id', null);
        $config = $this->getServiceLocator()->get('config');
        $pages = $config['navigation'][$name];
        if (isset($id)) {
            $ids = explode('.', $id);
            foreach ($ids as $key => $item) {
                if (!isset($pages[$item]['pages']) || count($pages[$item]['pages']) == 0) {
                    return $this->notFoundAction();
                }
                $pages = $pages[$item]['pages'];
            }
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
