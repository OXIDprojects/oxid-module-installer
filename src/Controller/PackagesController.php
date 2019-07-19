<?php

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstaller\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class PackagesController extends Controller
{

    public function indexAction()
    {
        return new JsonResponse([
            'status' => 'show all packages'
        ]);
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