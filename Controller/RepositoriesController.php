<?php

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstall\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class RepositoriesController extends Controller
{

    public function indexAction()
    {
    }

    public function newAction()
    {
        return new JsonResponse([
            'status' => 'new article'
        ]);
    }

    public function showAction($item)
    {
        return new JsonResponse([
            'status' => 'show article'
        ]);
    }

    public function updateAction()
    {
        return new JsonResponse([
            'status' => 'update Article'
        ]);
    }

    public function deleteAction()
    {
        return new JsonResponse([
            'status' => 'delete Article'
        ]);
    }

}