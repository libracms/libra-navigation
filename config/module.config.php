<?php
return array(
    'di' => array(
        'instance' => array(

            //Setup aliases

            //Alias for every controller prefixed by lowercase namespace
            'alias' => array(
                'libra-menu-index'         => 'LibraMenu\Controller\IndexController',
            ),

            // Defining where to look for views. This works with multiple paths,
            // very similar to include_path
            'Zend\View\Resolver\TemplatePathStack' => array(
                'parameters' => array(
                    'paths'  => array(
                        'libra-menu-glob' => __DIR__ . '/../view',
                        'libra-menu' => __DIR__ . '/../view/libra-menu',
                    ),
                ),
            ),
        ),
    ),
);
