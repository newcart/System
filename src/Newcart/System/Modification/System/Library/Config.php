<?php

namespace Newcart\System\Modification\System\Library;


class Config
{
    public function load($file, $filename)
    {
        if (file_exists($file)) {
            $_ = array();

            require(Vqmod::modCheck($file));

            $this->data = array_merge($this->data, $_);
        } else {

            global $registry;

            //load extension config
            $file = DIR_ROOT . $registry->get('config')->get('extension_path') . '/*/app/system/config/' . $filename . '.php';
            $file_extensions = glob($file);

            if (isset($file_extensions[0]) && file_exists($file_extensions[0])) {
                $_ = array();

                require(Vqmod::modCheck($file_extensions[0]));

                $this->data = array_merge($this->data, $_);
            } else {
                trigger_error('Error: Could not load config ' . $filename . '!');
                exit();
            }
        }
    }
}