<?php
define('BASE_PATH', realpath(dirname(__FILE__) . '/..'));
function box_drawer_autoload($class)
{
    $classToSearch = str_replace('\\', '/', $class);
    $filename = BASE_PATH . '/Classes/' . $classToSearch . '.php';
    require_once($filename);
}

spl_autoload_register('box_drawer_autoload');
