<?php

namespace Newcart\Tool\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCacheCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('clear:cache')
            ->setDescription('Clear all cache');
    }

    /**
     * Limpa os caches
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $paths = [
            DIR_CACHE,
            DIR_LOGS,
            DIR_VQMOD_CACHE . 'checked.cache',
            DIR_VQMOD_CACHE . 'mods.cache',
            DIR_VQMOD_CACHE . 'logs/',
            DIR_VQMOD_CACHE . 'vqcache/',
            DIR_IMAGE . 'cache/'
        ];

        foreach ($paths as $path) {
            $this->recursiveDelete($path);
        }

        $output->writeln('<info>Cache successfully clean.</info>');

        return true;
    }

    /**
     * Delete a file or recursively delete a directory
     *
     * @param string $str Path to file or directory
     */
    private function recursiveDelete($str)
    {
        if (is_file($str)) {
            return @unlink($str);
        } elseif (is_dir($str)) {
            $scan = glob(rtrim($str, '/') . '/*');
            foreach ($scan as $index => $path) {
                $this->recursiveDelete($path);
            }
        }
    }
}