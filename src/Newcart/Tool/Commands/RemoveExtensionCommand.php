<?php

namespace Newcart\Tool\Commands;

use Newcart\Tool\Helper\Util;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveExtensionCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('extension:remove')
            ->setDescription('Remove extension')
            ->addArgument(
                'extension_path',
                InputArgument::REQUIRED,
                'Extension path'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $extension = $input->getArgument('extension_path');

        $extension = Util::pathExtension() . $extension;

        $style = new OutputFormatterStyle('black', 'cyan');
        $output->getFormatter()->setStyle('fire', $style);

        //unistall general files
        $this->removeGeneralFiles($extension, $output);

        //unistall theme files
        $this->removeThemeFiles($extension, $output);

        $output->writeln('<fire>Arquivos em vermelho não foram encontrados para remoção.</>');
    }

    /**
     * Remove os arquivos gerais
     * @param $extension
     * @param $output
     */
    private function removeGeneralFiles($extension, $output)
    {
        $files = Util::getFiles($extension);

        if (count($files)) {
            $table = new Table($output);

            $table
                ->setHeaders(array('General Files'));

            foreach ($files as $file) {

                $dest = str_replace($extension, Util::pathRoot(), $file);
                $dir = dirname($dest);

                if (file_exists($dest)) {
                    $table->addRow(['<info>' . str_replace(Util::pathExtension(), '', $file) . '</info>']);
                } else {
                    $table->addRow(['<error>' . str_replace(Util::pathExtension(), '', $file) . '</error>']);
                }

                @unlink($dest);

                //limpa a pasta se estiver vazia
                $dir_status = glob($dir . '/*');
                if (empty($dir_status)) {
                    @rmdir($dir);
                }
            }

            $table->render();
        }
    }

    /**
     * remove arquivos do tema
     * @param $extension
     * @param $output
     */
    private function removeThemeFiles($extension, $output)
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

                    if (file_exists($dest)) {
                        $table->addRow(['<info>' . $dest . '</info>']);
                    } else {
                        $table->addRow(['<error>' . $dest . '</error>']);
                    }

                    @unlink($dest);

                    //limpa a pasta se estiver vazia
                    $dir_status = glob($dir . '/*');
                    if (empty($dir_status)) {
                        @rmdir($dir);
                    }
                }

            }

            $table->render();
        }
    }
}