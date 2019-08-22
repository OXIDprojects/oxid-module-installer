<?php

namespace OxidCommunity\ModuleInstaller\Composer;

use Composer\Composer;
use Composer\Config\JsonConfigSource;
use Composer\Json\JsonManipulator;
use Composer\Factory;
use Composer\IO\NullIO;
use Composer\Json\JsonFile;
use Composer\Package\PackageInterface;
use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginEvents;
use Composer\Repository\ArrayRepository;
use Composer\Repository\CompositeRepository;
use Composer\Repository\PlatformRepository;
use Composer\Repository\RepositoryInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class ComposerApi
{
    private static $composer = null;
    private static $path = null;

    public function setEnv()
    {
        putenv('COMPOSER_CACHE_DIR='.$this->getRootPath().'/kernel/var/composer/');
        putenv('COMPOSER_HOME=' . $this->getRootPath() . '/vendor/bin/composer');
    }

    public function getRootPath($force = false)
    {
        if (!$force && !empty(static::$path)) {
            return static::$path;
        }

        $Phar = \Phar::running();
        if (empty($Phar)) {
            $Phar = dirname(__DIR__);
        }

        static::$path = preg_replace('#phar://|[\\\/]oxid.phar.php|[\\\/]oxid.phar|[\\\/]web|[\\\/]src|[\\\/]public#is', '', $Phar);
        return static::$path;
    }

    public function getRootComposerJson()
    {
        return $this->getRootPath() . DIRECTORY_SEPARATOR . 'composer.json';
    }

    public function getComposer($io = null)
    {
        if (!empty(static::$composer)) {
            return static::$composer;
        }
        static::$composer = (new Factory())->createComposer($io ?: new NullIO(), $this->getRootComposerJson(), false, $this->getRootPath());

        return static::$composer;
    }

    public function removeRepository(array $Repository)
    {
        $composer = $this->getComposer();
        $configFile = new JsonFile($this->getRootComposerJson(), null, new NullIO());

        $Repositories = $composer->getConfig()->getRepositories();
        $DeletableRepo = [];
        foreach ($Repositories as $key => $repo) {
            if ($repo['url'] === $Repository['url']) {
                $DeletableRepo = [$key => false];
            }
        }
        unset($repo);

        $composerJson = $configFile->read();
        foreach ($composerJson['repositories'] as $key => &$repo) {
            if ($repo['url'] === $Repository['url']) {
                $json = new JsonConfigSource($configFile);
                $json->removeRepository($key);
                unset($composerJson['repositories'][$key]);
            }
        }
        unset($repo);
        $composer->getConfig()->merge(['repositories' => $DeletableRepo]);
        unset($composerJson['repositories']['packagist.org']);
        return $composerJson['repositories'];
    }

    public function addRepository(array $Repository)
    {
        $composer = $this->getComposer();
        $composer->getConfig()->merge(['repositories' => [$Repository]]);
        $configFile = new JsonFile($this->getRootComposerJson(), null, new NullIO());

        $composerJson = $configFile->read();
        foreach ($composerJson['repositories'] as $key => $repo) {
            if ($repo['url'] === $Repository['url']) {
                unset($composerJson['repositories']['packagist.org']);
                return $composerJson['repositories'];
            }
        }

        $json = new JsonConfigSource($configFile);
        $json->addRepository(count($composerJson['repositories']), $Repository);

        $composerJson['repositories'] = $composer->getConfig()->getRepositories();

        unset($composerJson['repositories']['packagist.org']);
        return $composerJson['repositories'];
    }

    public function getRepositories()
    {
        $arrRepository = [];
        $Repositories = $this->getComposer()->getRepositoryManager()->getRepositories();
        foreach ($Repositories as $Repository) {
            $config = $Repository->getRepoConfig();
            if ($config['url'] === 'https://repo.packagist.org') {
                continue;
            }

            $arrRepository[] = $config;
        }

        return $arrRepository;
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
                if (!empty($Extra['oxideshop']) || !empty($Extra['oxid-kernel-plugin']) || $package->getType() === 'oxideshop-module') {
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
            'extra' => $package->getExtra(),
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
            if (function_exists('getShopBasePath')) {
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

    public function removePackage($PackageName)
    {
        $composer = $this->getComposer();
        $configFile = new JsonFile($this->getRootComposerJson(), null, new NullIO());
        $contents = file_get_contents($configFile->getPath());

        $manipulator = new JsonManipulator($contents);

        $removeKey = 'require';
        
        if (!$manipulator->removeSubNode($removeKey, $PackageName)) {
            return false;
        }

        file_put_contents($configFile->getPath(), $manipulator->getContents());

        return true;
    }

    public function updatePackage(array $Package)
    {
        $composer = $this->getComposer();
        $configFile = new JsonFile($this->getRootComposerJson(), null, new NullIO());
        $contents = file_get_contents($configFile->getPath());

        $manipulator = new JsonManipulator($contents);

        $requireKey = 'require';
        if (!$manipulator->addLink($requireKey, $Package['name'], $Package['version'], false)) {
            return false;
        }

        file_put_contents($configFile->getPath(), $manipulator->getContents());

        return true;
    }
}
