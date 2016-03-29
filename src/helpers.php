<?php

if(!function_exists('sipay_path')) {
    function sipay_path($folder, $file = null)
    {
        $path = realpath(__DIR__) . DIRECTORY_SEPARATOR;
        $path .= trim(str_replace('/', DIRECTORY_SEPARATOR, $folder), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        return $path . ($file ?: '');
    }
}

if(!function_exists('utimestamp')) {
    function utimestamp($format) 
    {
        $microtime = microtime(true);
        list($sec, $usec) = preg_split('/(\.|\,)/', $microtime);
        $format = str_replace('u', $usec, $format);
        return date($format, $microtime);
    }
}