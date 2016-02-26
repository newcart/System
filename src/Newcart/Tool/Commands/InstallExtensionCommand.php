<?php

namespace Newcart\Tool\Commands;

use Newcart\Tool\Helper\Util;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class InstallExtensionCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('extension:install')
            ->setDescription('Install extension')
            ->addArgument(
                'extension_path',
                InputArgument::REQUIRED,
                'Extension path'
            );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //pega o nome da extensao passada no paramentro do comando
        $name = $input->getArgument('extension_path');

        //pega o caminho completo da extensao
        $extension = Util::pathExtension() . $name;

        $style = new OutputFormatterStyle('black', 'cyan');
        $output->getFormatter()->setStyle('fire', $style);

        //install general files
        $this->installGeneralFiles($extension, $output);

        //install theme files
        $this->installThemeFiles($extension, $output);

        $output->writeln('<fire>Arquivos marcados em amarelo ja existiam no projeto e foram sobreescritos.</>');

    }

    /**
     * Instala arquivos gerais da extensao
     * @param $extension
     * @param $output
     */
    private function installGeneralFiles($extension, $output)
    {
        $files = Util::getFiles($extension);

        if(count($files)) {
            $table = new Table($output);

            $table
                ->setHeaders(array('General Files'));

            foreach ($files as $file) {
                $dest = str_replace($extension, Util::pathRoot(), $file);
                $dir = dirname($dest);

                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }

                if (!file_exists($dest)) {
                    $table->addRow(['<info>' . str_replace(Util::pathExtension(), '', $file) . '</info>']);
                } else {
                    $table->addRow(['<comment>' . str_replace(Util::pathExtension(), '', $file) . '</comment>']);
                }

                @copy($file, $dest);
            }

            $table->render();
        }
    }

    /**
     * instala arquivos do tema se tiver na extensao
     * @param $extension
     * @param $output
     */
    private function installThemeFiles($extension, $output)
    {
        $theme_files = Util::getFilesTheme($extension);
        if (count($theme_files)) {

            $table = new Table($output);

            $table
                ->setHeaders(array('Theme Files'));

            $themes = Util::getThemesPath();
            foreach ($themes as $theme) {
                foreach ($theme_files as $theme_file) {
                    $dest = str_replace($extension . '/theme/', $theme . '/', $theme_file);

                    $dir = dirname($dest);

                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }

                    if (!file_exists($dest)) {
                        $table->addRow(['<info>' . $dest . '</info>']);
                    } else {
                        $table->addRow(['<comment>' . $dest . '</comment>']);
                    }

                    @copy($theme_file, $dest);
                }
            }

            $table->render();
        }
    }
}