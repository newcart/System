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
            ->setDescription('List all extensions installed.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $extensions = glob(Util::pathExtension() . '*/*', GLOB_ONLYDIR);

        $output->writeln('Extensions List');

        $table = new Table($output);

        $table
            ->setHeaders(['Vendor', 'Name']);

        foreach ($extensions as $extension_dir) {
            $name = str_replace(dirname($extension_dir), '', $extension_dir);
            $name = str_replace('/', '', $name);

            $vendor = str_replace('/' . $name, '', $extension_dir);
            $vendor = str_replace(dirname($vendor), '', $vendor);
            $vendor = str_replace('/', '', $vendor);

            $table->addRow([$vendor, $name]);
        }

        $table->render();
    }
}