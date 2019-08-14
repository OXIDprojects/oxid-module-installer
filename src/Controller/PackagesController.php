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

    public function updateSelectedAction()
    {
        $composerApi = new ComposerApi();
        $Packages = json_decode(file_get_contents('php://input'), true);

        if(empty($Packages)) {
            return new JsonResponse([
                'error' => 'No packages selected!'
            ]);
        }

        foreach($Packages as $key => $Package) {
            switch($Package['installer']['type']) {
                case 'delete':
                    // $composerApi->removePackage($Package['name']);
                    $composerApi->updatePackage($Package);
                break;
                case 'update':
                    $composerApi->updatePackage($Package);
                break;
            }
        }

        return new JsonResponse([
            'status' => 'update selected package'
        ]);
    }

    public function deleteAction()
    {
        return new JsonResponse([
            'status' => 'delete package'
        ]);
    }

}