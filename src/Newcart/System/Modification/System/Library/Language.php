<?php

namespace Newcart\System\Modification\System\Library;


class Language
{
    public function getDefault()
    {
        global $registry;

        $default = $registry->get('config')->get('default_language');
        return $default ? $default : 'english';
    }

    public function load($filename, $directory, $default)
    {
        global $registry;

        //load extension language
        $file = DIR_ROOT . '/' . $registry->get('config')->get('extension_path') . '/*/*/' . $registry->get('config')->get('environment') . '/language/' . $directory . '/' . $filename . '.php';
        $file_extensions = glob($file);

        foreach ($file_extensions as $file_extension) {
            if (file_exists($file_extension)) {
                require($file_extension);
            }
        }

        //load extension language default
        $file = DIR_ROOT . '/' . $registry->get('config')->get('extension_path') . '/*/*/' . $registry->get('config')->get('environment') . '/language/' . $default . '/' . $filename . '.php';
        $file_extensions = glob($file);

        foreach ($file_extensions as $file_extension) {
            if (file_exists($file_extension)) {
                require(Vqmod::modCheck($file_extension));
            }
        }

        //load theme language
        $file_theme = DIR_TEMPLATE . $registry->get('config')->get('config_template') . '/language/' . $default . '/' . $filename . '.php';

        if (file_exists($file_theme)) {
            require_once($file_theme);
        }

        $file_theme = DIR_TEMPLATE . $registry->get('config')->get('config_template') . '/language/' . $directory . '/' . $filename . '.php';

        if (file_exists($file_theme)) {
            require_once($file_theme);
        }
    }
}