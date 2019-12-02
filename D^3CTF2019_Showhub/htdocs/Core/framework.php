<?php
ini_set('display_errors', 'Off');
error_reporting(0);
session_start();
spl_autoload_register(function ($class) {
    if (stristr($class, 'Controller')) {
        include WEB_PATH . '/Controllers/' . $class . '.php';
    } else {
        include WEB_PATH . '/Models/' . $class . '.php';
    }
});
if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}
require_once WEB_PATH . '/config.php';
require_once WEB_PATH . '/vendor/autoload.php';
require_once WEB_PATH . '/Core/App.php';
require_once WEB_PATH . '/Core/Request.php';
require_once WEB_PATH . '/Core/Mysql.php';
Mysql::getInstance($CONFIG['dbhost'], $CONFIG['dbuser'], $CONFIG['dbpassword'], $CONFIG['dbname']);
$app = new App();
$request = new Request();
echo $app->run($request);

