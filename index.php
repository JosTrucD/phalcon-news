<?php
date_default_timezone_set("Asia/Vientiane");
error_reporting(E_ALL);

define("IN_JOST"     , 1);
define("PATH_ROOT"   , dirname(__FILE__));
define("PATH_APP"    , PATH_ROOT."/application");
define("PATH_CACHE"  , PATH_ROOT."/cache");
define("PATH_CONFIG" , PATH_ROOT."/config");
define("PATH_PLUGINS", PATH_ROOT."/application/plugins");
define("PATH_UPLOADS", "uploads");

try {
	$config = include PATH_CONFIG . '/config.php';
	define('DOMAIN', $config->application->publicUrl);
    require_once $config->application->pluginsDir . 'common.php';
    include PATH_CONFIG . '/loader.php';
    include PATH_CONFIG . '/services.php';
    include PATH_CONFIG . '/routers.php';
    

    $application = new \Phalcon\Mvc\Application();
    $application->setDI($di);
    echo $application->handle()->getContent();
} catch (Phalcon\Exception $e) {
    echo get_class($e), " : <br><span style='color:red'>";
    echo $e->getMessage(), "</span><br><hr>";
    echo " File : ", $e->getFile(), "<br>";
    echo " Line : ", $e->getLine(), "<br><pre style='color:blue'>";
    echo $e->getTraceAsString(), '</pre>';
    echo $e->getMessage();
} catch (PDOException $e) {
    echo $e->getMessage();
}
function debug($data)
{
    if ($data) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}