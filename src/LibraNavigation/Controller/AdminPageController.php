<?php

/*
 * eJoom.com
 * This source file is subject to the new BSD license.
 */

namespace LibraNavigation\Controller;

use LibraNavigation\Form\PageFilter;
use LibraNavigation\Form\PageForm;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Navigation\Navigation;
use Zend\Navigation\Page\Mvc as PageMvc;

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

    protected function cleanupNavigationArray(array &$array)
    {
        foreach ($array as $key => &$item) {
            if (is_array($item) && !empty($item)) {
                $this->cleanupNavigationArray($item);
            } else {
                if ($item === null
                    || (is_array($item) && empty($item))
                    || ($key == 'active' && $item == false)
                    || ($key == 'visible' && $item == true)
                ) {
                    unset($array[$key]);
                }
            }
        }
    }

    public function editAction()
    {
        $name = 'default';
        $id = $this->params('id', null);
        if ($id === null) return false; //undefined id or create new menu

        $config = $this->getServiceLocator()->get('config');
        $pages_array = $config['navigation'][$name];
        $navigationDefault = new Navigation($pages_array);
        $ids = explode('.', $id);
        $lastId = array_pop($ids);
        foreach ($ids as $key => $item) {
            if (!isset($pages_array[$item]['pages']) || count($pages_array[$item]['pages']) == 0) {
                return $this->notFoundAction(); //@todo need implement create action
            }
            $found_pages_array = $pages_array[$item]['pages'];
        }
        PageMvc::setDefaultRouter($this->getEvent()->getRouter());
        $container = new Navigation($found_pages_array);
        $pages = array_values($container->getPages());
        $page = $pages[$lastId];

        $form = new PageForm();
        $form->setInputFilter(new PageFilter);

        $redirectUrl = $this->url()->fromRoute('admin/libra-navigation/page', array('action'=> 'edit', 'id' => $id));
        $prg = $this->prg($redirectUrl, true);
        if ($prg instanceof Response) {
            return $prg;
        } elseif ($prg !== false) {  //$prg = @var array POST data
            $form->setData($prg);
            if ($form->isValid()) {
                try {

                    $data = $form->getData();
                    unset($data['submit']);
                    unset($data['csrf']);
                    $page->set('params', array_merge($page->get('params'), $data['params']));
                    unset($data['params']);
                    //if ($data['order'] === '') $data['order'] = null;
                    if ($data['order'] == 0) $data['order'] = null;

                    $container->toArray();
                    $page->setOptions($data);
                    $navigationArray = $navigationDefault->toArray();

                    $this->cleanupNavigationArray($navigationArray);

                    $varExport = var_export($navigationArray, true);
                    $varExport = preg_replace("/=> \n + array/", '=> array', $varExport);
                    $varExport = str_replace("  ", '    ', $varExport);
                    $varExport = preg_replace("/[0-9]+ => /", '', $varExport);
                    file_put_contents("config/constructed.$name.php", "<?php\nreturn " . $varExport . ";\n");
                    $this->flashMessenger()->addSuccessMessage('Chanches was saved');
                    //return $this->redirect()->toUrl($redirectUrl);
                } catch (\Exception $exc) {
                    $this->flashMessenger()->addErrorMessage(sprintf('DB error. May be duplicate entry. %s', $exc->getMessage()));
                    throw $exc;
                }
            }
        } elseif ($prg === false) {  //as usual, first GET query
            $form->setData($page);
        }

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
