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
                        'may_terminate' => true,
                        'child_routes' => array(
                            'navigations' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/navigations[/:action][/:id]',
                                    'constraints' => array(
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'id'         => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'admin-navigations',
                                        'action'     => 'view',
                                    ),
                                ),
                            ),
                            'navigation' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/navigation/:action[/:name]',
                                    'constraints' => array(
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'name'       => '[a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'admin-navigation',
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
            'libra-navigation/admin-navigations' => 'LibraNavigation\Controller\AdminNavigationsController',
            'libra-navigation/admin-navigation'  => 'LibraNavigation\Controller\AdminNavigationController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
