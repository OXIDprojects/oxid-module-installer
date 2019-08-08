<?php

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstaller\Controller;

use OxidCommunity\ModuleInstaller\Composer\ComposerApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class RepositoriesController extends Controller
{

    public function indexAction()
    {
        return new JsonResponse([
            'status' => 'index',
            'repositories' => (new ComposerApi())->getRepositories()
        ]);
    }

    public function newAction()
    {
        $Data = json_decode(file_get_contents('php://input'), true);

        return new JsonResponse([
            'status' => 'new repository',
            'repositories' => (new ComposerApi())->addRepository($Data['repository'])
        ]);
    }

    public function showAction($item)
    {
        return new JsonResponse([
            'status' => 'show repository'
        ]);
    }

    public function updateAction()
    {
        return new JsonResponse([
            'status' => 'update repository'
        ]);
    }

    public function deleteAction()
    {
        $Data = json_decode(file_get_contents('php://input'), true);

        return new JsonResponse([
            'status' => 'new repository',
            'repositories' => (new ComposerApi())->removeRepository($Data['repository'])
        ]);
    }

}