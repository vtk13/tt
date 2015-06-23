<?php
require_once 'vendor/autoload.php';

session_start();

$router = new \Vtk13\Mvc\Handlers\ControllerRouter('Tt\\Controller\\', '/', 'index');
$response = $router->handle(\Vtk13\Mvc\Http\Request::createFromGlobals());

if (!headers_sent()) {
    header($response->getStatusLine());
    foreach ($response->getHeaders() as $name => $value) {
        header("{$name}: {$value}");
    }
    echo $response->getBody();
}
