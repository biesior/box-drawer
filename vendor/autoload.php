<?php
// For IDE, Important these disabled inspections should be checked before releasing MINOR or MAJOR versions!
/** @noinspection PhpUnhandledExceptionInspection */

declare(strict_types=1);

// you can define only one of these, depending where your autoloader is placed in the structure!
//define('BASE_PATH', realpath(dirname(__DIR__, 2))); // Use this if you move autoloader 2 directories deeper from index.php
define('BASE_PATH', realpath(dirname(__DIR__, 1))); // Use this if you move autoloader 1 directory deeper from index.php
//define('BASE_PATH', realpath(dirname(__FILE__))); // use this if on the same level as index.php

function box_drawer_autoloader($class)
{
    $parts = explode('\\', $class);
    $partsGiven = count($parts);
    if ($partsGiven < 3) {
        throw new Exception(sprintf("This autoloder supports minimum 3 parts in namespace, %s given", $partsGiven), time());
    }
    unset($parts[0]);
    unset($parts[1]);
    $filename = BASE_PATH . '/src/' . implode('/', $parts) . '.php';
    /** @noinspection PhpIncludeInspection */
    require_once($filename);
}

spl_autoload_register('box_drawer_autoloader');