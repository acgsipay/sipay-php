<?php

function autoload($class)
{
    $path = realpath(__DIR__) . DIRECTORY_SEPARATOR;

    $class = ltrim($class, '\\');

    if(substr(strtolower($class), 0, 5) == 'sipay') {
        $class = ltrim(substr($class, 6), '\\');
    }
    else {
        return false;
    }

    $filename  = '';
    $namespace = '';

    if ($last = strrpos($class, '\\')) {
        $namespace = substr($class, 0, $last);
        $class = substr($class, $last + 1);
        $filename  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $filename .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

    $path .= $filename;

    if (file_exists($path)) {
        require $path;
    }
}

if(!defined('SIPAY_SDK_ROOT_PATH')) {
    define('SIPAY_SDK_ROOT_PATH', realpath(__DIR__) . DIRECTORY_SEPARATOR);
}

if(!defined('SIPAY_SDK_PROFILE_PATH')) {
    define('SIPAY_SDK_PROFILE_PATH', SIPAY_SDK_ROOT_PATH . 'profile' . DIRECTORY_SEPARATOR);
}

require_once 'helpers.php';

spl_autoload_register('autoload');
