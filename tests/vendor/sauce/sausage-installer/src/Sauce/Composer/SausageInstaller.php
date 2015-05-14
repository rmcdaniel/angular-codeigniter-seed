<?php

namespace Sauce\Composer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class SausageInstaller extends LibraryInstaller
{
    public function supports($packageType)
    {
        return 'sauce-sausage' === $packageType;
    }

    protected function preCodeUpdate($package)
    {
        $path = $this->getInstallPath($package);
        $config_file = $path.'/.sauce_config';
        $contents = null;

        if (is_file($config_file)) {
            $this->io->write("    Backing up Sauce config");
            $contents = file_get_contents($config_file);
        }

        return array($config_file, $contents);
    }

    protected function postCodeUpdate($config_file, $contents)
    {
        if ($contents) {
            $this->io->write("    Restoring Sauce config");
            file_put_contents($config_file, $contents);
        } elseif (getenv('SAUCE_USERNAME') && getenv('SAUCE_ACCESS_KEY')) {
            $this->io->write("    Generating Sauce config based on environment variables");
            file_put_contents($config_file, getenv('SAUCE_USERNAME').','.
                getenv('SAUCE_ACCESS_KEY'));
        } else {
            $this->io->write("<warning>    No Sauce config file found. Please run vendor/bin/sauce_config USERNAME API_KEY</warning>");
        }
    }

    public function updateCode(PackageInterface $initial, PackageInterface $target)
    {
        list($config_file, $contents) = $this->preCodeUpdate($initial);
        parent::updateCode($initial, $target);
        $this->postCodeUpdate($config_file, $contents);
    }

    public function installCode(PackageInterface $package)
    {
        list($config_file, $contents) = $this->preCodeUpdate($package);
        parent::installCode($package);
        $this->postCodeUpdate($config_file, $contents);
    }
}
