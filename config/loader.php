<?php
$loader = new \Phalcon\Loader();
$loader->registerDirs(array(
	$config->application->modelsDir,
	$config->application->controllersDir,
	$config->application->libraryDir,
	$config->application->pluginsDir
));
$loader->register();