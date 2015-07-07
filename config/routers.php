<?php
/**
 * @author JosT <trucbvt@gmail.com>
 * @date   Jun 21 2015
 */
use Phalcon\Mvc\Router;
// Create the router
$router = new Router();
$di->set('router', function () {
    $router = new Router();
    //Define a route
	$router->add(
	    "/dang-nhap",
	    array(
	        "controller" => "login",
	        "action"     => "index"
	    )
	);
	$router->add(
	    "/bai-viet/([a-zA-Z0-9_-]+)",
	    array(
	        "controller" => "index",
	        "action"     => "detail",
	        "params"     => 2
	    )
	);
	$router->add(
	    "/danh-muc/([a-zA-Z0-9_-]+)",
	    array(
	        "controller" => "index",
	        "action"     => "list",
	        "params"     => 2
	    )
	);
    return $router;
});