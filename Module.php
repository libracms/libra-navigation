<?php

namespace LibraMenu;

use Zend\Module\Manager,
    Zend\EventManager\StaticEventManager,
    Zend\Module\Consumer\AutoloaderProvider;

class Module implements AutoloaderProvider
{
    public function init(Manager $moduleManager)
    {
        $events = StaticEventManager::getInstance();
        $events->attach('bootstrap', 'bootstrap', array($this, 'initMenu'));
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

    public function initMenu($e)
    {
        $app       = $e->getParam('application');
        $events    = $app->events();
        $locator   = $app->getLocator();
        $menu      = $locator->get('LibraMenu\Widget\Menu');
        $events->attach('dispatch', array($menu, 'addMenu'));
    }

}
