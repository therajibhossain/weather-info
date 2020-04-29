<?php
/**
 * Auto loading our classes
 */

spl_autoload_register('gww_autoload');
function gww_autoload($class_name)
{
    require_once 'gwweatherconfig.php';
    if (false !== strpos($class_name, 'GWWeather')) {
        $dirSep = DIRECTORY_SEPARATOR;
        $parts = explode('\\', $class_name);
        $class = 'class-' . strtolower(array_pop($parts));
        $folders = strtolower(implode($dirSep, $parts));
        if (file_exists($classpath = dirname(__FILE__) . $dirSep . $folders . $dirSep . $class . '.php')) {
            require_once($classpath);
        } else {
            wp_die('The ' . $class_name . ' does not exist');
        }
    }
}