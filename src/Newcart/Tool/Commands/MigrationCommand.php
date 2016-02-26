<?php

namespace Newcart\Tool\Commands;

use Newcart\Tool\Helper\Util;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('migration')
            ->addOption('env', 'e', InputOption::VALUE_OPTIONAL, 'Env', null)
            ->setDescription('Migration');

    }

    /**
     * Executa todos os migrations do phinx
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migrations = [];

        //migrations app
        $migrations[] = APPDIR . 'migrations';

        //find migrations extensions
        $migrations_extensions = glob(Util::pathExtension() . '*/app/migrations', GLOB_ONLYDIR);

        $migrations = array_merge($migrations, $migrations_extensions);

        foreach ($migrations as $migration) {

            $migration = str_replace('//', '/', $migration);
            $_SERVER['PHINX_MIGRATION_PATH'] = $migration;

            //init phinx
            $app = new \Phinx\Console\PhinxApplication();
            $wrap = new \Phinx\Wrapper\TextWrapper($app);

            //set parser
            $wrap->setOption('parser', 'php');

            //set config file
            $wrap->setOption('configuration', APPDIR . 'phinx.php');

            // Get the environment and target version parameters.
            $env = ENV;

            //execute migrate
            $output_func = $wrap->getMigrate($env);

            $output->writeln($output_func);

        }

        return true;
    }
}