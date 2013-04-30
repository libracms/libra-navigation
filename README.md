Libra CMS
=======================
Navigation
-----------------------
Add sitemap generating by module returned configs  
This module allow web management of your navigation.

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

For changing configs like another container name copy __libra-navigation.global.php__
to your autoloader default folder and do respective changes.