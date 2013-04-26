Libra CMS
=======================
Navigation
-----------------------
Add sitemap generating by module returned configs

## Use
  Enable at application.config.php
  Add this lines:

~~~
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
            'config/constructed/admin-navigation.php',
            'config/constructed/navigation.php',
        ),
~~~
