<?php


namespace OxidCommunity\ModuleInstaller\Composer;


use Composer\Console\Application;

class ComposerApplication extends Application
{
    public function __construct()
    {
        parent::__construct();
        $this->io = new IO();
    }
}