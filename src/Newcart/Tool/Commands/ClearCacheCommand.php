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
            ->setDescription('Clear cache');
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
            DIR_CACHE . '*',
            DIR_LOGS . '*',
            DIR_VQMOD_STORAGE . 'checked.cache',
            DIR_VQMOD_STORAGE . 'mods.cache',
            DIR_VQMOD_CACHE . '*',
            DIR_VQMOD_LOGS . '*',
        ];

        foreach ($paths as $path) {
            @array_map('unlink', glob($path));
        }

        $output->writeln('Cache successfully clean.');

        return true;
    }
}