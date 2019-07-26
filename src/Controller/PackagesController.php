<?php

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstaller\Controller;

use OxidCommunity\ModuleInstaller\Composer\ComposerApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PackagesController extends Controller
{

    public function indexAction()
    {
        $request = Request::createFromGlobals();
        $search = $request->query->get('search') ?: '';
        $type = $request->query->get('type') ?: null;
        $composerApi = new ComposerApi();
        $result = $composerApi->search($search,$type);
        return new JsonResponse($result);
    }

    public function newAction()
    {
        $composerApi = new ComposerApi();
        ob_implicit_flush();
        print "<!DOCTYPE html>
        <head><style>error{color:red;}</style></head>
        <body>starte:...<br>";
        ob_flush();
        $composerApi->requirePackage('composer/composer');
        print "</body>";
        exit(0);
        return new JsonResponse([
            'status' => 'new package'
        ]);
    }

    public function showAction($item)
    {
        return new JsonResponse([
            'status' => 'show package'
        ]);
    }

    public function updateAction()
    {
        return new JsonResponse([
            'status' => 'update package'
        ]);
    }

    public function deleteAction()
    {
        return new JsonResponse([
            'status' => 'delete package'
        ]);
    }

}