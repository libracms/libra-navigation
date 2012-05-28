<?php

namespace LibraNavigation;

use Zend\ModuleManager\ModuleManager,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function init(ModuleManager $moduleManager)
    {
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $this->initMenu($e);
    }

    public function initMenu(MvcEvent $e)
    {
        $app        = $e->getApplication();
        $events     = $app->events();
        //$locator   = $app->getServiceManager();
        //$locator   = $app->getLocator();
        //$menu      = $locator->get('LibraMenu\Widget\Menu');
        $menu       = new \LibraNavigation\Widget\Menu;
        $events->attach('dispatch', array($menu, 'addMenu'));
    }

}
