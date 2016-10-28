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
        foreach ($backtrace as $call) {
            if (isset($call['file'])) {
                echo '@ ', $call['file'], ' ';
            }
            if (isset($call['line'])) {
                echo ': ', $call['line'], ' ';
            }
            if (isset($call['class'])) {
                echo '\\', $call['class'], ' ';
            }
            if (isset($call['type'])) {
                echo $call['type'], ' ';
            }
            if (isset($call['function'])) {
                echo $call['function'], ' ';
            }
            echo "\n";
        }
        echo "\n";
        var_dump($expression);
        echo "\n\n";
    }
}
