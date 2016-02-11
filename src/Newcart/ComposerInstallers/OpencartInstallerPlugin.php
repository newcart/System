<?php

namespace Newcart\ComposerInstallers;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class OpencartInstallerPlugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new OpencartInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }
}