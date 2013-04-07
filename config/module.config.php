<?php
return array(
    'router' => array(
        'routes' => array(
            'libra-navigation' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'module'     => 'libra-navigation',
                    ),
                ),
                'child_routes' => array(
                    'sitemap' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'sitemap.xml',
                            'defaults' => array(
                                'module'     => 'libra-navigation',
                                'controller' => 'sitemap',
                                'action'     => 'generate',
                            ),
                        ),
                    ),
                ),
            ),
            'admin' => array(
                'child_routes' => array(
                    'libra-navigation' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/navigation',
                            'defaults' => array(
                                'module'     => 'libra-navigation',
                            ),
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'containers' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/containers[/:action][/:id]',
                                    'constraints' => array(
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'         => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'admin-containers',
                                        'action'     => 'view',
                                    ),
                                ),
                            ),
                            'container' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/container/:action[/:name]',
                                    'constraints' => array(
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'name'       => '[a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'admin-container',
                                        'action'     => 'edit',
                                    ),
                                ),
                            ),
                            'pages' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/pages[/:action[/:name]]',
                                    'constraints' => array(
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'name'       => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'admin-pages',
                                        'action'     => 'list',
                                        'name'       => 'default',
                                    ),
                                ),
                                'may_terminate' => true,
                            ),
                            'page' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/page/:action[/:name]',
                                    'constraints' => array(
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'name'       => '[a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'admin-page',
                                        'action'     => 'edit',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'libra-navigation/sitemap'      => 'LibraNavigation\Controller\SitemapController',
            'libra-navigation/admin-containers'         => 'LibraNavigation\Controller\AdminContainersController',
            'libra-navigation/admin-container'          => 'LibraNavigation\Controller\AdminContainerController',
            'libra-navigation/admin-pages'   => 'LibraNavigation\Controller\AdminPagesController',
            'libra-navigation/admin-page'    => 'LibraNavigation\Controller\AdminPageController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
