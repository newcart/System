<?php

namespace Newcart\System\ComposerInstallers;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class OpencartInstaller extends LibraryInstaller
{
    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        return 'core/';
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        return 'newcart-opencart' === $packageType;
    }
}