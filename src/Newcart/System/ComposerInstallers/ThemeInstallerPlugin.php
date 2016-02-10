<?php

namespace Newcart\System\ComposerInstallers;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class ThemeInstallerPlugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new ThemeInstaller($io, $composer);
        $composer->getInstallationManager()->addInstaller($installer);
    }
}