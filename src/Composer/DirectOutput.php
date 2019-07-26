<?php


namespace OxidCommunity\ModuleInstaller\Composer;


use Symfony\Component\Console\Output\Output;

class DirectOutput extends Output
{

    protected function doWrite($message, $newline)
    {
        print $message;
    }
}