<?php

/*
 * eJoom.com
 * This source file is subject to the new BSD license.
 */

namespace LibraNavigation\Controller;

use LibraNavigation\Form\PageFilter;
use LibraNavigation\Form\PageForm;
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
    public function createAction()
    {

    }

    public function editAction()
    {
        $name = 'default';
        $post = $this->params()->fromPost();
        $id = $this->params('id', null);
        if ($id === null) return false; //undefined id or create new menu

        $config = $this->getServiceLocator()->get('config');
        $pages = $config['navigation'][$name];
        $ids = explode('.', $id);
        $lastId = array_pop($ids);
        foreach ($ids as $key => $item) {
            if (!isset($pages[$item]['pages']) || count($pages[$item]['pages']) == 0) {
                return $this->notFoundAction(); //@todo need implement create action
            }
            $pages = $pages[$item]['pages'];
        }
        PageMvc::setDefaultRouter($this->getEvent()->getRouter());
        $container = new Navigation($pages);
        $pages = array_values($container->getPages());
        $page = $pages[$lastId];

        $form = new PageForm();
        $form->setInputFilter(new PageFilter);
        $data = $page->toArray();
        //$form->setData($post);
        //$form->isValid();
        return array(
            'container' => $container,
            'name'      => $name,
            'id'        => $id,
            'page'      => $page,
            'form'      => $form,
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
