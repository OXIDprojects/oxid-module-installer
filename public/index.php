<?php

define('INDEXED_KERNEL', true);

require "../vendor/autoload.php";

use OxidCommunity\SymfonyKernel\HttpKernel\Kernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

Debug::enable();
error_reporting(E_ERROR);

$_GET['_controller'] = 1;

Request::enableHttpMethodParameterOverride();
$kernel = new Kernel('prod', false);
$kernel->setProjectRoot(str_replace('source/modules/oxidcommunity/SymfonyKernel/Core', '', preg_replace('|\\\|is', '/', __DIR__)) . '/../');

$Phar = \Phar::running();
if(empty($Phar)) {
    $Phar = dirname(__DIR__);
}

$Path = preg_replace('#phar://|[\\\/]oxid.phar.php|[\\\/]oxid.phar|[\\\/]src|[\\\/]web|[\\\/]public#is' , '', $Phar);
$kernel->setCacheSourceDir($Path);
$kernel->setLogSourceDir($Path);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
//$responseStatusCode = $response->getStatusCode();

$response->send();
$kernel->terminate($request, $response);

