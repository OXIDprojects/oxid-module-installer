<?php

declare (strict_types = 1);

namespace OxidCommunity\ModuleInstaller\Controller;

use OxidCommunity\ModuleInstaller\Composer\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use OxidCommunity\ModuleInstaller\Composer\ComposerApi;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Console\Output\BufferedOutput;
use OxidCommunity\ModuleInstaller\Composer\Command\UpdateCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Output\ConsoleOutput;          
use Composer\IO\ConsoleIO;

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
        $Output = $this->composerUpdate(new ComposerApi());
        return new JsonResponse([
            'status' => 'update package',
            'run' => stream_get_contents(fopen('php://stdin', 'r'), 1)
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

        $this->composerUpdate($composerApi);

        return new JsonResponse([
            'status' => 'update selected package',
            // 'run' => $Output->fetch()
        ]);
    }

    private function composerUpdate(ComposerApi $composerApi)
    {
        ini_set('memory_limit', '4G');
        ini_set('max_execution_time', '0');
        $composerApi->setEnv();

        $input = new ArgvInput(array());
        $output = new ConsoleOutput();
        $helper = new HelperSet();
        $io = new ConsoleIO($input, $output, $helper);

        // die("<pre>" . __METHOD__ .":\n" . print_r($input, true));
        chdir($composerApi->getRootPath());
        $update = new UpdateCommand();
        $update->setComposer($composerApi->getComposer($io));
        $update->run($input, $output);

        return $output->getStream();

        
        
        $Input = new \Symfony\Component\Console\Input\StringInput('update -vvv -n --no-dev --prefer-dist ');
        $Output = new \Symfony\Component\Console\Output\StreamOutput(fopen('php://output','w'));
        

        $Application = new Application();
        $Application->setComposer($composerApi->getComposer());
        $Application->setAutoExit(false);
        // $Output = new BufferedOutput();
        $Application->run($Input, $Output);

        return $Output;
    }

    public function deleteAction()
    {
        return new JsonResponse([
            'status' => 'delete package'
        ]);
    }

}