<?php

/*
 * eJoom.com
 * This source file is subject to the new BSD license.
 */

namespace LibraNavigation\Controller;

use Exception;
use LibraNavigation\Form\PageFilter;
use LibraNavigation\Form\PageForm;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Navigation\Navigation;
use Zend\Navigation\Page\AbstractPage;
use Zend\Navigation\Page\Mvc as PageMvc;

/**
 * List of pages and can edit them
 *
 * @author duke
 */
class AdminPageController extends AbstractActionController
{
    protected $containerName;

    /**
     * Determ if is it creating of new page
     * @var bool
     */
    protected $newPage = false;

    /**
     * Parent page
     * @var AbstractPage
     */
    protected $parentPage;

    /**
     * Id specification of parent id
     * @var string
     */
    protected $parendIdSpec;

    protected function isCreatePage()
    {
        return (bool) $this->newPage;
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

    protected function containerName()
    {
        if ($this->containerName === null) {
            $config = $this->serviceLocator->get('config');
            $this->containerName = $config['libra_navigation']['container_name'];
        }

        return $this->containerName;
    }

    protected function saveNavigation($container)
    {
        $containerToArray = $container->toArray();
        $this->cleanupNavigationArray($containerToArray);

        $config = $this->serviceLocator->get('config');
        $pathPattern = $config['libra_navigation']['save_pattern'];
        $filePath = sprintf($pathPattern, $this->containerName());

        return file_put_contents($filePath, $this->varExportPretty($containerToArray));
    }


    /**
     * leading ',' mean creating of new page
     * @param string $idString
     * @param Navigation $container
     * @return AbstractPage
     */
    protected function findPageById($idString, Navigation $container)
    {
        $ids = explode('.', $idString);
        $lastId = array_pop($ids);
        $this->parendIdSpec = implode('.', $ids);

        foreach ($ids as $id) {
            $container = array_values($container->getPages());
            if (!isset($container[$id])) {
                return $this->notFoundAction();
            }
            $container = $container[$id];
        }
        $this->parentPage = $container;

        if ($lastId === '') {  //test for leading '.'
            $this->newPage = true;
            return 1; //create page
        }

        $container = array_values($container->getPages());

        if (!isset($container[$lastId])) {  //test out of range
            //throw new \InvalidArgumentException('Wrong id. Those page not found.');
            return $this->notFoundAction();
        }
        
        $page = $container[$lastId];

        return $page;
    }

    public function editAction()
    {
        $containerName = $this->containerName();
        $id = $this->params('id', null);
        if ($id === null)
            return false; //undefined id or create new menu

        $config = $this->getServiceLocator()->get('config');
        $navigationAsArray = $config['navigation'][$containerName];
        
        PageMvc::setDefaultRouter($this->getEvent()->getRouter());
        $container = new Navigation($navigationAsArray);
        $page = $this->findPageById($id, $container);

        $queryParams = $this->params()->fromQuery();
        if (!empty($queryParams)) {
            if ($page instanceof AbstractPage) {  //not new page
                if (isset($queryParams['visible'])) {
                    $page->setVisible($queryParams['visible']);
                }
                $this->saveNavigation($container);
            }
            return $this->redirect()->toRoute('admin/libra-navigation/pages', array('id' => $this->parendIdSpec));
        }

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
                    //if ($data['order'] === '') $data['order'] = null;
                    if ($data['order'] == 0)
                        $data['order'] = null;
                    if ($data['route'] === '')
                        $data['route'] = null;
                    if (!$page instanceof AbstractPage) {  //if new page => create
                        $page = AbstractPage::factory($data);
                        $this->parentPage->addPage($page);
                    } else {
                        $page->set('params', array_merge($page->get('params'), $data['params']));
                        unset($data['params']);
                        $page->setOptions($data);
                    }

                    $this->saveNavigation($container);

                    $this->flashMessenger()->addSuccessMessage('Chanches was saved');
                    //return $this->redirect()->toUrl($redirectUrl);
                } catch (Exception $exc) {
                    $this->flashMessenger()->addErrorMessage(sprintf('DB error. May be duplicate entry. %s', $exc->getMessage()));
                    throw $exc;
                }
            }
        } elseif ($prg === false) {  //as usual, first GET query
            if ($page instanceof AbstractPage) {
                $form->setData($page);
            }
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
        $idSpec = $this->params('id');

        $config = $this->getServiceLocator()->get('config');
        $navigationAsArray = $config['navigation'][$this->containerName()];
        
        PageMvc::setDefaultRouter($this->getEvent()->getRouter());
        $container = new Navigation($navigationAsArray);

        $page = $this->findPageById($idSpec, $container);
        if (!$page instanceof AbstractPage)
            return $this->notFoundAction ();
        
        $this->parentPage->removePage($page);
        $this->saveNavigation($container);

        $parentIdSpec = $this->parendIdSpec;
        if (!$this->parentPage->hasPages()) {
            $parentIds = explode ('.', $this->parendIdSpec);
            array_pop($parentIds);
            $parentIdSpec = implode('.', $parentIds);
        }
        return $this->redirect()->toRoute('admin/libra-navigation/pages', array('id' => $parentIdSpec));
    }
}
