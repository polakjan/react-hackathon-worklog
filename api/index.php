<?php

require_once 'lib/system.class.php';
require_once 'lib/db.class.php';
require_once 'lib/user.class.php';
require_once 'lib/request.class.php';

user::init();

$uri = system::getPageUri();

$uri_parts = explode('/', $uri.'/');

$controller_name = $uri_parts[0] ?: 'index';
$action_name = $uri_parts[1] ?: 'index';

$controller_filename = $controller_name.'.controller.php';
$controller_file = 'controllers/'.$controller_filename;
$controller_class = system::camelize($controller_name).'Controller';

if(file_exists($controller_file))
{
    include $controller_file;
    $controller = new $controller_class();
}

if(!empty($controller) && is_callable([$controller, $action_name]))
{
    $output = call_user_func_array(array($controller, $action_name), array_slice($uri_parts, 2));
}
else
{
    $output = [
        'message' => '404: page not found'
    ];

    header("HTTP/1.0 404 Not Found");
}

system::sendJsonHeaders();

echo json_encode($output);

exit();