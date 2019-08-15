<?php

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstaller\Controller;

use Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use OxidCommunity\ModuleInstaller\Composer\ComposerApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Output\BufferedOutput;


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
                    $composerApi->removePackage($Package['name']);
                break;
                case 'update':
                    $composerApi->updatePackage($Package);
                break;
            }
        }

        $composerApi->setEnv();

        // call `composer update` command programmatically
        // $Input = new ArrayInput(array('command' => 'update'));
        // $Application = new Application();
        // $Application->setAutoExit(false);
        // $Output = new BufferedOutput();
        // $Application->run($Input, $Output);

        return new JsonResponse([
            'status' => 'update selected package',
            // 'run' => $Output->fetch()
        ]);
    }

    public function deleteAction()
    {
        return new JsonResponse([
            'status' => 'delete package'
        ]);
    }

}