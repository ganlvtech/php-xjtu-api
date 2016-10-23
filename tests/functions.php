<?php
if (!function_exists('config')) {
    function config($key = null)
    {
        static $config = null;
        if (is_null($config)) {
            $config = include 'config.php';
        }
        if (is_null($key)) {
            return $config;
        }
        return $config[$key];
    }
}
