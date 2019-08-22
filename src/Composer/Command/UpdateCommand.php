<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OxidCommunity\ModuleInstaller\Composer\Command;

use Composer\Installer;
use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginEvents;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\Command\UpdateCommand AS ComposerUpdateCommand;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @author Nils Adermann <naderman@naderman.de>
 */
class UpdateCommand extends ComposerUpdateCommand
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = $this->getIO();

        if ($input->getOption('dev')) {
            $io->writeError('<warning>You are using the deprecated option "dev". Dev packages are installed by default now.</warning>');
        }
        
        $composer = $this->getComposer(true);

        $packages = $input->getArgument('packages');

        if ($input->getOption('interactive')) {
            $packages = $this->getPackagesInteractively($io, $input, $output, $composer, $packages);
        }


        if ($input->getOption('root-reqs')) {
            $require = array_keys($composer->getPackage()->getRequires());
            if (!$input->getOption('no-dev')) {
                $requireDev = array_keys($composer->getPackage()->getDevRequires());
                $require = array_merge($require, $requireDev);
            }

            if (!empty($packages)) {
                $packages = array_intersect($packages, $require);
            } else {
                $packages = $require;
            }
        }

        $composer->getDownloadManager()->setOutputProgress(!$input->getOption('no-progress'));

        $commandEvent = new CommandEvent(PluginEvents::COMMAND, 'update', $input, $output);
        $composer->getEventDispatcher()->dispatch($commandEvent->getName(), $commandEvent);

        $install = Installer::create($io, $composer);

        $config = $composer->getConfig();
        list($preferSource, $preferDist) = $this->getPreferredInstallOptions($config, $input);

        $optimize = $input->getOption('optimize-autoloader') || $config->get('optimize-autoloader');
        $authoritative = $input->getOption('classmap-authoritative') || $config->get('classmap-authoritative');
        $apcu = $input->getOption('apcu-autoloader') || $config->get('apcu-autoloader');

        $install
            ->setDryRun($input->getOption('dry-run'))
            ->setVerbose($input->getOption('verbose'))
            ->setPreferSource($preferSource)
            ->setPreferDist($preferDist)
            ->setDevMode(!$input->getOption('no-dev'))
            ->setDumpAutoloader(!$input->getOption('no-autoloader'))
            ->setRunScripts(!$input->getOption('no-scripts'))
            ->setSkipSuggest($input->getOption('no-suggest'))
            ->setOptimizeAutoloader($optimize)
            ->setClassMapAuthoritative($authoritative)
            ->setApcuAutoloader($apcu)
            ->setUpdate(true)
            ->setUpdateWhitelist($input->getOption('lock') ? array('lock') : $packages)
            ->setWhitelistTransitiveDependencies($input->getOption('with-dependencies'))
            ->setWhitelistAllDependencies($input->getOption('with-all-dependencies'))
            ->setIgnorePlatformRequirements($input->getOption('ignore-platform-reqs'))
            ->setPreferStable($input->getOption('prefer-stable'))
            ->setPreferLowest($input->getOption('prefer-lowest'))
        ;

        return $install->run();
    }
}
