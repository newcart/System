<?php

namespace Newcart\Tool\Helper;


class Util
{
    /**
     * Caminho onde a extensoes vao ficar
     * @todo fazer via arquivo de configuracao
     * @return string
     */
    public static function pathExtension()
    {
        return ROOTDIR . '/extensions/';
    }

    /**
     * Caminho da raiz do projeto
     * @todo fazer via arquivo de configuracao
     * @return string
     */
    public static function pathRoot()
    {
        return ROOTDIR . '/';
    }

    /**
     * Arquivos a serem ignorados
     * @todo fazer via arquivo de configuracao
     * @return array
     */
    public static function filesIgnored()
    {
        return [
            'composer.json',
            '.gitignore',
            'composer.lock',
            'README.md',
            'changelog.md',
            '.git',
            '.git/',
            'theme'
        ];
    }

    /**
     * Return a list of files inside directory extension
     * @param $folder extension
     * @return array files list
     */
    public static function getFiles($folder)
    {
        $output = array();
        foreach (scandir($folder) as $file) {

            if ($file == '.' || $file == '..') {
                continue;
            }

            if (!in_array($file, self::filesIgnored())) {
                if (is_dir("$folder/$file")) {
                    $output = array_merge($output, self::getFiles("$folder/$file"));
                } else {
                    $output[] = "$folder/$file";
                }
            }
        }

        return $output;
    }

    /**
     * Pega os arquivos do tema da extensao
     * @param $folder for extension
     * @return array
     */
    public static function getFilesTheme($folder)
    {
        if (is_dir($folder . '/' . 'theme')) {
            return self::getFiles($folder . '/' . 'theme');
        }
    }

    /**
     * Pega os caminhos dos temas intalados
     * @return array
     */
    public static function getThemesPath()
    {
        return glob(DIR_TEMPLATE . '*');
    }
}