<?php

/*
 * eJoom.com
 * This source file is subject to the new BSD license.
 */

namespace LibraNavigation\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of SitemapController
 *
 * @author duke
 */
class SitemapController extends AbstractActionController
{
    public function generateAction()
    {
        $urlset = array();
        $modules = $this->getServiceLocator()->get('ModuleManager')->getLoadedModules();
        foreach ($modules as $moduleName => $module) {
            if (method_exists($module, 'getSitemapConfig')) {
                $moduleUrlset = $module->getSitemapConfig($this->getServiceLocator());
                if ($moduleUrlset !== null && !is_array($moduleUrlset)) {
                    throw new \Exception(sprintf('Module "%s" must return array or null', $moduleName));
                }
                $urlset = $urlset + (array)$moduleUrlset;
            }
        }

        /** @link http://support.google.com/webmasters/bin/answer.py?hl=en&answer=183668 description */
        /*$data = array(
            'loc'        => '',
            'lastmod'    => null,
            'changefreq' => null,
            'priority'   => null,
        );*/
        $view = new ViewModel(array(
            'urlset' => $urlset,
        ));
        $view->setTerminal(true);
        $this->getResponse()->getHeaders()->addHeaderLine('Content-Type: text/xml; charset=utf-8');
        return $view;
    }

}
