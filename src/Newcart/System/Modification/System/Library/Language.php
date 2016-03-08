<?php

namespace Newcart\System\Modification\System\Library;


use Newcart\System\Helper\Util;
use Newcart\System\Libraries\Extension;
use Newcart\System\Libraries\Theme;

class Language
{
    public function getDefault()
    {
        $default = Util::getConfig('default_language');
        return $default ? $default : 'english';
    }

    public function load($filename, $directory, $default)
    {
        $data = [];
        $_ = [];
        
        //load extension language default
        $file = Extension::dirExtension() . '*/*/' . Util::getConfig('environment') . '/language/' . $default . '/' . $filename . '.php';
        $file_extensions = glob($file);

        foreach ($file_extensions as $file_extension) {
            if (file_exists($file_extension)) {
                require(\Vqmod::modCheck($file_extension));
            }
        }

        $data = array_merge($data, $_);

        //load extension language
        $file = Extension::dirExtension() . '*/*/' . Util::getConfig('environment') . '/language/' . $directory . '/' . $filename . '.php';
        $file_extensions = glob($file);

        foreach ($file_extensions as $file_extension) {
            if (file_exists($file_extension)) {
                require($file_extension);
            }
        }

        $data = array_merge($data, $_);

        //load theme language
        $file_theme = Theme::dirCurrentTheme() . '/language/' . $default . '/' . $filename . '.php';

        if (file_exists($file_theme)) {
            require($file_theme);
        }

        $data = array_merge($data, $_);

        $file_theme = Theme::dirCurrentTheme() . '/language/' . $directory . '/' . $filename . '.php';

        if (file_exists($file_theme)) {
            require($file_theme);
        }

        $data = array_merge($data, $_);
        
        return $data;
    }
}