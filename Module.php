<?php

namespace LibraNavigation;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\Mvc\MvcEvent;

class Module implements
    AutoloaderProviderInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
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
        $events     = $app->getEventManager();
        //$locator   = $app->getServiceManager();
        //$locator   = $app->getLocator();
        //$menu      = $locator->get('LibraMenu\Widget\Menu');
        //$menu       = new \LibraNavigation\Widget\Menu;
        //$events->attach('dispatch', array($menu, 'addMenu'));
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
            ),
            'factories' => array(
            ),
        );
    }

}
