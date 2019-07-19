<?php

namespace Sioweb\Oxid\Kernel\Legacy\Core;

require "vendor/autoload.php";

use Sioweb\Oxid\Kernel\HttpKernel\Kernel;
use Symfony\Component\HttpFoundation\Request;

$_GET['_controller'] = 1;

// Debug::enable(E_ERROR);
Request::enableHttpMethodParameterOverride();
$kernel = new Kernel('prod', false);



$kernel->setProjectRoot('/' . __DIR__);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
//$responseStatusCode = $response->getStatusCode();

$response->send();
$kernel->terminate($request, $response);

