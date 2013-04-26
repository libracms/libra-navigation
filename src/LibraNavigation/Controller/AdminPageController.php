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

    /**
     * This will change value if it containes new lines aka "\n"
     * @param array $var
     * @return string
     */
    protected function varExportPretty($var)
    {
        $str = "<?php\nreturn " . var_export($var, true) . ";\n";
        $str = preg_replace("/=> \n + array/", '=> array', $str);
        $str = preg_replace_callback(
            '/^(  )+/m',
            function($m) {
                return str_repeat($m[0], 2);
            },
            $str
        );
        $str = preg_replace("/^( +)[0-9]+ => /m", '$1', $str);

        return $str;
    }

    /**
     *
     * @param string $idString
     * @param \Zend\Navigation\Navigation $container
     * @return \Zend\Navigation\Page\AbstractPage
     */
    protected function findPageById($idString, Navigation $container)
    {
        $ids = explode('.', $idString);
        $lastId = array_pop($ids);
        foreach ($ids as $id) {
            $container = array_values($container->getPages());
            if (!isset($container[$id]) || !$container[$id]->hasPages()) {
                return $this->notFoundAction(); //@todo need implement create action
            }
            $container = $container[$id];
        }
        $container = array_values($container->getPages());
        $page = $container[$lastId];
        return $page;
    }

    public function editAction()
    {
        $containerName = 'default';
        $id = $this->params('id', null);
        if ($id === null)
            return false; //undefined id or create new menu

        $config = $this->getServiceLocator()->get('config');
        $navigationAsArray = $config['navigation'][$containerName];
        
        PageMvc::setDefaultRouter($this->getEvent()->getRouter());
        $container = new Navigation($navigationAsArray);

        $page = $this->findPageById($id, $container);
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
                    if ($data['order'] == 0)
                        $data['order'] = null;
                    $page->setOptions($data);

                    $containerToArray = $container->toArray();
                    $this->cleanupNavigationArray($containerToArray);

                    $filePath = "config/constructed/navigation.$containerName.php";
                    file_put_contents($filePath, $this->varExportPretty($containerToArray));

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
            'name'      => $containerName,
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
