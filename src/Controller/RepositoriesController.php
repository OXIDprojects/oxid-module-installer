<?php

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstaller\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class RepositoriesController extends Controller
{

    public function indexAction()
    {
        return new JsonResponse([
            'status' => 'list all repositories'
        ]);
    }

    public function newAction()
    {
        return new JsonResponse([
            'status' => 'new repository'
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
        return new JsonResponse([
            'status' => 'delete repository'
        ]);
    }

}