<?php


namespace OxidCommunity\ModuleInstaller\Composer;


use Composer\Factory;
use Composer\IO\NullIO;
use Composer\IO\BufferIO;
use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginEvents;
use Composer\Repository\CompositeRepository;
use Composer\Repository\PlatformRepository;
use Composer\Repository\RepositoryInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Composer\Package\PackageInterface;

use Composer\Composer;
use Composer\Repository\VcsRepository;
use Composer\Package\CompletePackage;
use Composer\Repository\ArrayRepository;

class ComposerApi
{
    

    public function getComposer()
    {
        $Phar = \Phar::running();
        if(empty($Phar)) {
            $Phar = dirname(__DIR__);
        }
        
        $Path = preg_replace('#phar://|[\\\/]oxid.phar.php|[\\\/]oxid.phar|[\\\/]web|[\\\/]src|[\\\/]public#is' , '', $Phar);
        return (new Factory())->createComposer(new NullIO(), $Path . DIRECTORY_SEPARATOR . 'composer.json', false, $Path);
    }

    public function getRootPackages()
    {
        $composer = $this->getComposer();

        $packages = $composer->getRepositoryManager()->getLocalRepository()->getPackages();
        
        $package = $this->getComposer()->getPackage();
        $repos = $installedRepo = new ArrayRepository(array($package));
        $rootRequires = $this->getRootRequires();
        $arrayTree = array('oxid' => [], 'other' => []);
        
        foreach ($packages as $package) {
            if (in_array($package->getName(), $rootRequires, true)) {
                $Extra = $package->getExtra();
                if(!empty($Extra['oxideshop']) || !empty($Extra['oxid-kernel-plugin']) || $package->getType() === 'oxideshop-module') {
                    $arrayTree['oxid'][] = $this->generatePackageTree($package, $installedRepo, $repos);
                } else {
                    $arrayTree['other'][] = $this->generatePackageTree($package, $installedRepo, $repos);
                }
            }
        }
        
        return $arrayTree;
    }

    protected function getRootRequires()
    {
        $rootPackage = $this->getComposer()->getPackage();

        return array_diff(array_map(
            'strtolower',
            array_keys(array_merge($rootPackage->getRequires(), $rootPackage->getDevRequires()))
        ), ['composer/composer', 'php']);
    }

    /**
     * Generate the package tree
     *
     * @param  PackageInterface $package
     * @param  RepositoryInterface     $installedRepo
     * @param  RepositoryInterface     $distantRepos
     * @return array
     */
    protected function generatePackageTree(
        PackageInterface $package,
        RepositoryInterface $installedRepo,
        RepositoryInterface $distantRepos
    ) {
        $requires = $package->getRequires();
        ksort($requires);
        $children = array();
        foreach ($requires as $requireName => $require) {
            $packagesInTree = array($package->getName(), $requireName);

            $treeChildDesc = array(
                'name' => $requireName,
                'version' => $require->getPrettyConstraint(),
            );

            // $deepChildren = $this->addTree($requireName, $require, $installedRepo, $distantRepos, $packagesInTree);

            // if ($deepChildren) {
            //     $treeChildDesc['requires'] = $deepChildren;
            // }

            $children[] = $treeChildDesc;
        }
        $tree = array(
            'name' => $package->getPrettyName(),
            'type' => $package->getType(),
            'version' => $package->getPrettyVersion(),
            'description' => $package->getDescription(),
            'extra' => $package->getExtra()
        );

        if ($children) {
            $tree['requires'] = $children;
        }

        return $tree;
    }

    /**
     * @param $search string searchwords whitespace to separate

     * @params $type string the package type we searching
     * @return array list of packages with name and description
     */
    public function search($search, $type = null)
    {
        // if(defined('INDEXED_KERNEL')) {
        //     return [];
        // }
        
        $input = new ArrayInput([]);

        // init repos
        $platformRepo = new PlatformRepository();

        $noPlugins = false;
        $nullIo = new NullIO();
        
        // in Oxid EShop Path is missing
        if (empty(getenv('HOME'))) {
            if(function_exists('getShopBasePath')) {
                $path = getShopBasePath() . '/../';
            } else {
                $path = dirname(__DIR__) . '/../';
            }
            putenv("HOME=$path");
        }
        
        $composer = Factory::create($nullIo, array(), $noPlugins);
        $localRepo = $composer->getRepositoryManager()->getLocalRepository();
        $installedRepo = new CompositeRepository(array($localRepo, $platformRepo));
        $repos = new CompositeRepository(array_merge(array($installedRepo), $composer->getRepositoryManager()->getRepositories()));

        $null = new NullOutput();
        $commandEvent = new CommandEvent(PluginEvents::COMMAND, 'search', $input, $null);
        $composer->getEventDispatcher()->dispatch($commandEvent->getName(), $commandEvent);
        $onlyName = false;
        $flags = $onlyName ? RepositoryInterface::SEARCH_NAME : RepositoryInterface::SEARCH_FULLTEXT;
        return $repos->search($search, $flags, $type);
    }
}
