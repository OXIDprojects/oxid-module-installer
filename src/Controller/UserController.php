<?php

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstaller\Controller;

use OxidCommunity\ModuleInstaller\Composer\ComposerApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{

    public function authAction()
    {
        die("<pre>" . __METHOD__ .":\n" . print_r('ASDASD', true));
    }

    public function jwtAction()
    {
        return new JsonResponse([
            [
                'id' => 1,
                'username' => 'test',
                'password' => 'test',
                'firstName' => 'Sascha',
                'lastName' => 'Weidner',
            ]
        ]);
    }

}