<?php

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstaller\Controller;

use OxidCommunity\ModuleInstaller\Composer\ComposerApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends Controller
{

    public function indexAction()
    {
        return $this->render('@OxidCommunityModuleInstaller/Index/index.html.twig', []);
        die("<pre>" . __METHOD__ .":\n" . print_r('ASDASD', true));
    }

}