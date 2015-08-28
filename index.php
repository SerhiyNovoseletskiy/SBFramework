<?
use app\sitebuilder\Application;

defined('SB_DEBUG') or define('SB_DEBUG', true);


require_once __DIR__ .'/vendor/sitebuilder/autoload.php';

if (SB_DEBUG) {
    $debug = new \app\debug\Debug();
}

$config = require_once (__DIR__ .'/config/config.php');
\app\sitebuilder\SiteBuilder::$app = new Application($config);
\app\sitebuilder\SiteBuilder::$app->run();