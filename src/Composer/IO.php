<?php


namespace OxidCommunity\ModuleInstaller\Composer;


use Composer\IO\NullIO;

class IO extends NullIO
{

    /**
     * {@inheritDoc}
     */
    public function write($messages, $newline = true, $verbosity = self::NORMAL)
    {
        print $messages;
        ob_flush();
        if ($newline) {
            print "<br/>";
        }
    }

    /**
     * {@inheritDoc}
     */
    public function writeError($messages, $newline = true, $verbosity = self::NORMAL)
    {
        print "<error>";
        $this->write($messages , $newline, $verbosity);
        print "</error>";
    }

    /**
     * {@inheritDoc}
     */
    public function overwrite($messages, $newline = true, $size = 80, $verbosity = self::NORMAL)
    {
        print '<overwrite>';
        $this->write($messages , $newline, $verbosity);
        print '</overwrite>';
    }

    /**
     * {@inheritDoc}
     */
    public function overwriteError($messages, $newline = true, $size = 80, $verbosity = self::NORMAL)
    {
        print '<overwriteerror>';
        $this->write($messages , $newline, $verbosity);
        print '</overwriteerror>';
    }
}