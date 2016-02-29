<?php

namespace Newcart\System\Libraries;


use \Newcart\System\Helper\Util;

class Extension
{
    public function __construct() { }

    /**
     * Get all extensions
     */
    public static function getAll()
    {
        if (Util::getCache()->get('extensions')) {
            return Util::getCache()->get('extensions');
        } else {
            $extensions = [];

            $extensions_path = glob(self::dirExtension() . '*/*/', GLOB_ONLYDIR);
            foreach ($extensions_path as $extension_path) {

                $name = basename($extension_path);
                $vendor = basename(str_replace($name, '', $extension_path));

                $extensions[$vendor . '/' . $name]['path'] = $extension_path;
            }

            Util::getCache()->set('extensions', $extensions);

            return $extensions;
        }
    }

    /**
     * Diretorio onde a extensoes vao ficar
     * @return string
     */
    public static function dirExtension()
    {
        return DIR_ROOT . '/' . Util::getConfig('extension_path') . '/';
    }
}