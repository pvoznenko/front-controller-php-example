<?php

// Command that starts the built-in web server
$command = sprintf('php -S %s:%d -t %s >/dev/null 2>&1 & echo $!', WEB_SERVER_HOST, WEB_SERVER_PORT, WEB_SERVER_DOCROOT);

// Execute the command and store the process ID
$output = array();
exec($command, $output);
$pid = (int)$output[0];

echo sprintf('%s - Web server started on %s:%d with PID %d', date('r'), WEB_SERVER_HOST, WEB_SERVER_PORT, $pid), PHP_EOL;

// Kill the web server when the process ends
register_shutdown_function(function() use ($pid) {
    echo sprintf('%s - Killing process with ID %d', date('r'), $pid), PHP_EOL;
    exec('kill ' . $pid);
});

define('ROOT', current(explode('tests', __DIR__)));

// More bootstrap code
require_once ROOT . 'tests/BaseWebTestClass.php';
require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/app/config/config.php';

if (is_readable(DB_FILE_PATH)) {
    unlink(DB_FILE_PATH);
}