<?php

namespace Newcart\Tool\Commands;

use Newcart\Tool\Helper\Util;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class ListExtensionCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('extension:list')
            ->setDescription('List extension');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $extensions = glob(Util::pathExtension() . '*', GLOB_ONLYDIR);

        $table = new Table($output);

        $table
            ->setHeaders(array('Extension Path'));

        foreach ($extensions as $dir) {
            $extension = str_replace(dirname($dir), '', $dir);
            $table->addRow([str_replace('/', '', $extension)]);
        }

        $table->render();

    }
}