<?php

namespace Newcart\System\Libraries;


class Extension
{
    /**
     * Opencart Registry
     * @var \Registry
     */
    private $registry;

    public function __construct($registry = null)
    {
        if (!$registry) {
            global $registry;
        }

        $this->registry = $registry;
    }

    /**
     * Shorcut get config
     * @param $key
     * @return mixed
     */
    private static function getConfig($key)
    {
        $static = new Static;
        return $static->registry->get('config')->get($key);
    }

    /**
     * Get all extensions
     */
    public static function getAll()
    {
        $static = new Static();

        if ($static->getCache()->get('extensions')) {
            return $static->getCache()->get('extensions');
        } else {
            $extensions = [];

            $extensions_path = glob(DIR_ROOT . '/extensions/*/*/', GLOB_ONLYDIR);
            foreach ($extensions_path as $extension_path) {

                $name = basename($extension_path);
                $vendor = basename(str_replace($name, '', $extension_path));

                $extensions[$vendor . '/' . $name]['path'] = $extension_path;
            }

            $static->getCache()->set('extensions', $extensions);

            return $extensions;
        }
    }

    /**
     * Get cache class
     * @return \Cache|null
     */
    private function getCache()
    {
        return $this->registry->get('cache');
    }
}