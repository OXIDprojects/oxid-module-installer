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
        $type = $request->query->get('type') ?: 'oxid-module';
        $composerApi = new ComposerApi();
        $result = $composerApi->search($search,$type);
        return new JsonResponse($result);
    }

    public function newAction()
    {
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