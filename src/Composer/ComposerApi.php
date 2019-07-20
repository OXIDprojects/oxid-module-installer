<?php


namespace OxidCommunity\ModuleInstaller\Composer;


use Composer\Factory;
use Composer\IO\NullIO;
use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginEvents;
use Composer\Repository\CompositeRepository;
use Composer\Repository\PlatformRepository;
use Composer\Repository\RepositoryInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class ComposerApi
{


    /**
     * @param $search string searchwords whitespace to separate

     * @params $type string the package type we searching
     * @return array list of packages with name and description
     */
    public function search(string $search, $type = null)
    {
        $input = new ArrayInput([]);

        // init repos
        $platformRepo = new PlatformRepository();

        $noPlugins = false;
        $nullIo = new NullIO();
        
        // if running in Oxid EShop the path for composer must be set
        if (function_exists('getShopBasePath') && empty(getenv('HOME'))) {
            $path = getShopBasePath() . "/../";
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
        $results = $repos->search($search, $flags, $type);
        return $results;

    }

    public function list()
    {

    }

}