<?php

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstaller\Controller;

use OxidCommunity\ModuleInstaller\Composer\ComposerApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    private $users = [
        [
            'id' => 1,
            'username' => 'test',
            'password' => 'test',
            'firstName' => 'Sascha',
            'lastName' => 'Weidner',
        ]
    ];

    public function authAction()
    {
        $Data = json_decode(file_get_contents('php://input'), true);
        foreach($this->users as $user) {
            if(
                $user['username'] === $Data['username'] &&
                $user['password'] === $Data['password']
            ) {
                $user['token'] = 'fake-jwt-token';
                die(json_encode($user));
            }
            die(json_encode(['error' => 'Username or password is incorrect!']));
        }
    }

    public function jwtAction()
    {
        return new JsonResponse($this->users);
    }

}