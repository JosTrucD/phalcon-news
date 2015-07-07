<?php
return new \Phalcon\Config(array (
	'database' => array (
		'adapter'  => 'Mysql',
		'host'     => 'localhost',
		'username' => 'root',
		'password' => '',
		'dbname'   => 'gxtraigao',
		'charset'  => 'utf8'
	),
	'application' => array(
		'controllersDir' => PATH_APP . '/controllers/',
		'modelsDir'      => PATH_APP . '/models/',
		'viewsDir'       => PATH_APP . '/views/',
		'libraryDir'     => PATH_APP . '/library/',
		'pluginsDir'     => PATH_APP . '/plugins/',
		'baseUri'        => '/',
		'publicUrl'      => 'phalcon'
	),
	// 'mail' => array(
	// 	'fromName'  => 'Alinkhay.com',
	// 	'fromEmail' => 'noreply@alinkhay.com',
	// 	'smtp'      => array(
	// 		'server'   => 'smtp.gmail.com',
	// 		'port'     => 587,
	// 		'security' => 'tls',
	// 		'username' => '',
	// 		'password' => ''
	// 	)
	// )
));