<?php


namespace OxidCommunity\ModuleInstaller\Composer;


use Composer\Command\RequireCommand;
use Composer\Factory;
use Composer\IO\NullIO;
use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginEvents;
use Composer\Repository\CompositeRepository;
use Composer\Repository\PlatformRepository;
use Composer\Repository\RepositoryInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;

class ComposerApi
{
    private $io;
    private $output;

    public function __construct()
    {
        $this->io = new NullIO();
        $this->output = new NullOutput();
        // if running in Oxid EShop the path for composer must be set
        if (function_exists('getShopBasePath') && empty(getenv('HOME'))) {
            $path = getShopBasePath() . "/../";
            putenv("HOME=$path");
        }
    }

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
        $nullIo = $this->io;
        

        $composer = Factory::create($nullIo, array(), $noPlugins);
        $localRepo = $composer->getRepositoryManager()->getLocalRepository();
        $installedRepo = new CompositeRepository(array($localRepo, $platformRepo));
        $repos = new CompositeRepository(array_merge(array($installedRepo), $composer->getRepositoryManager()->getRepositories()));

        $null = $this->output;
        $commandEvent = new CommandEvent(PluginEvents::COMMAND, 'search', $input, $null);
        $composer->getEventDispatcher()->dispatch($commandEvent->getName(), $commandEvent);
        $onlyName = !isset($type);
        $flags = $onlyName ? RepositoryInterface::SEARCH_NAME : RepositoryInterface::SEARCH_FULLTEXT;
        $results = $repos->search($search, $flags, $type);
        return $results;

    }

    public function requirePackage($package){

        $requireCommand = new RequireCommand();
        $app = new ComposerApplication();
        $this->output = new DirectOutput();
        $requireCommand->setApplication($app);
        $definition = $requireCommand->getDefinition();
        $definition->addOption(new InputOption('--profile', null, InputOption::VALUE_NONE, 'Display timing and memory usage information'));
        $definition->addOption(new InputOption('--no-plugins', null, InputOption::VALUE_NONE, 'Whether to disable plugins.'));
        $definition->addOption(new InputOption('--working-dir', '-d', InputOption::VALUE_REQUIRED, 'If specified, use the given directory as working directory.'));

        //$input = new ArrayInput(['--no-update' => null],$definition);
        $input = new ArrayInput([],$definition);
        $requireCommand->run($input,$this->output);

    }

    public function list()
    {

    }

}