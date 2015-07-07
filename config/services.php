<?php
$di = new \Phalcon\DI\FactoryDefault();
$di->set('dispatcher', function(){
	return new \Phalcon\Mvc\Dispatcher();
});
$di->set('url', function() use ($config){
	$url = new \Phalcon\Mvc\Url();
	$url->setBaseUri($config->application->baseUri);
	return $url;
});
$di->set('view', function() use ($config) {
	$view = new \Phalcon\Mvc\View();
	$view->setViewsDir($config->application->viewsDir);
	// $view->registerEngines(array(
	// 	".volt" => 'volt'
	// ));
	return $view;
});
// $di->set('volt', function($view, $di) {
// 	$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
// 	$volt->setOptions(array(
// 		"compiledPath" => "cache/volt/",
// 		"compiledSeparator" => "_"
// 	));
// 	$compiler = $volt->getCompiler();
// 	$compiler->addFunction('is_a', 'is_a');
// 	return $volt;
// }, true); 
/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function() use ($config) {
	return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
		"host"     => $config->database->host,
		"username" => $config->database->username,
		"password" => $config->database->password,
		"dbname"   => $config->database->dbname,
        "charset"  => $config->database->charset
	));
});
$di->set('session', function(){
	$session = new Phalcon\Session\Adapter\Files();
	$session->start();
	return $session;
});
/**
 * Register the flash service with custom CSS classes
 */
$di->set('flash', function(){
	return new Phalcon\Flash\Session(array(
		'error'   => 'message warning',
		'success' => 'message success',
		'notice'  => 'message alert-info',
	));
});
/**
 * Library helper
 */
$di->set('helper', function(){
	return new Helper();
});
/**
 * Library string
 */
$di->set('string', function(){
	return new String();
});
/**
 * Library date
 */
$di->set('date', function(){
	return new Date();
});
/**
 * Upload image
 */
$di->set('upload', function(){
	return new Upload();
});

$di->set('logger', function() {
	return new \Phalcon\Logger\Adapter\File(__DIR__.'/cache/logs/'.date('Y-m-d').'.log');
}, true);
/* set_error_handler(function($errno, $errstr, $errfile, $errline) use ($di){
	if (!(error_reporting() & $errno)) {
	return;
	}
	$di->getFlash()->error($errstr);
	$di->getLogger()->log($errstr.' '.$errfile.':'.$errline, Phalcon\Logger::ERROR);
	return true;
}); */

