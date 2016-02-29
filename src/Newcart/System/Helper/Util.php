<?php

namespace Newcart\System\Helper;


class Util
{
    /**
     * Opencart Registry
     * @var \Registry
     */
    private $registry;

    public function __construct($registry = null)
    {
        if(!$registry) {
            global $registry;
        }

        $this->registry = $registry;
    }

    public static function requireConstants()
    {
        require_once __DIR__ . '/../constants.php';
    }

    /**
     * Shorcut get config
     * @param $key
     * @return mixed
     */
    public static function getConfig($key)
    {
        return self::getRegistry()->get('config')->get($key);
    }

    /**
     * Get registry opencart
     * @return \Registry
     */
    public static function getRegistry()
    {
        $static = new Static;
        return $static->registry;
    }

    /**
     * Get cache class
     * @return \Cache|null
     */
    public static function getCache()
    {
        return self::getRegistry()->get('cache');
    }

    /**
     * Caminho da raiz do projeto
     * @return string
     */
    public static function pathRoot()
    {
        return DIR_ROOT;
    }

    /**
     * Arquivos a serem ignorados
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
}