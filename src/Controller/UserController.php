<?php

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstaller\Controller;

use Lcobucci\JWT\Builder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    private $users = [
        [
            'id' => 1,
            'username' => 'test',
            'password' => 'test',
            'firstName' => 'Sascha',
            'lastName' => 'Weidner',
        ],
    ];

    public function authAction()
    {
        $Data = json_decode(file_get_contents('php://input'), true);
        foreach ($this->users as $user) {
            if (
                $user['username'] === $Data['username'] &&
                $user['password'] === $Data['password']
            ) {
                $token = (new Builder())->setIssuer('oxid-moduleinstaller')
                    ->setAudience('oxid-moduleinstaller')
                    ->setIssuedAt(time())
                    ->setExpiration(time() + 3600)
                    ->getToken();
                $user['token'] = '' . $token;
                return new JsonResponse($user);
            }
        }
        return new JsonResponse(['error' => 'Username or password is incorrect!']);
    }

    public function jwtAction()
    {
        return new JsonResponse($this->users);
    }

}
