<?php
use Vtk13\LibSql\Mysql\Mysql;

require_once 'vendor/autoload.php';

$db = new Mysql('localhost', 'root', '', 'tt');

$router = new \Vtk13\Mvc\Handlers\ControllerRouter('Tt\\Controller\\', '/', 'track');
$response = $router->handle(\Vtk13\Mvc\Http\Request::createFromGlobals());
header($response->getStatusLine());
foreach ($response->getHeaders() as $name => $value) {
    header("{$name}: {$value}");
}
echo $response->getBody();
