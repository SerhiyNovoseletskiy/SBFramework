<?
use app\sitebuilder\Application;

defined('SB_DEBUG') or define('SB_DEBUG', true);


require_once __DIR__ .'/components/sitebuilder/autoload.php';

if (SB_DEBUG) {
    $debug = new \app\debug\Debug();
}

$config = require_once (__DIR__ .'/config/config.php');
Application::$app = new Application($config);

Application::$app->run();