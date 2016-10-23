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
if (!function_exists('test_dump')) {
    function test_dump($expression)
    {
        echo "\n";
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $trace = $backtrace[1];
        echo "{$trace['class']} -> {$trace['function']}\n\n";
        var_dump($expression);
        echo "\n\n";
    }
}
