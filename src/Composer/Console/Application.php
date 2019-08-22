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

namespace OxidCommunity\ModuleInstaller\Composer\Console;

use Composer\Composer;
use Composer\Console\Application as ComposerApplication;

class Application extends ComposerApplication
{
    public function setComposer(Composer $composer) {
        $this->composer = $composer;
    }
}