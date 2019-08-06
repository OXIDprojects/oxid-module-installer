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
        return new JsonResponse([
            'status' => 'index',
            'packages' => (new ComposerApi())->getRootPackages()
        ]);
    }

    public function newAction()
    {
        return new JsonResponse([
            'status' => 'new package'
        ]);
    }

    public function searchAction($item)
    {
        $composerApi = new ComposerApi();
        $result = $composerApi->search($item);

        return new JsonResponse([
            'status' => 'show package',
            'term' => $item,
            'packages' => $result
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