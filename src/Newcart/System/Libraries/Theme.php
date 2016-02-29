<?php

namespace Newcart\System\Libraries;

use \Newcart\System\Helper\Util;

/**
 * Class Theme
 * @description Library desenvolvida para
 * ajudar o desenvolvedor no tema
 * @package Newcart\System\Libraries
 */
class Theme
{
    public function __construct()
    {
    }

    /**
     * Get asset path
     * @param string $src
     * @param bool $minify
     * @return string
     * @todo minify files css and js
     */
    public function asset($src, $minify = false)
    {
        if (!Util::getConfig('is_admin')) {

            if (file_exists(DIR_TEMPLATE . $this->getName() . '/' . Util::getConfig('assets_path') . '/' . $src)) {
                return Util::getConfig('theme_path') . '/' . $this->getName() . '/' . Util::getConfig('assets_path') . '/' . $src;
            }

        } else if (file_exists(DIR_APPLICATION . $src)) {

            return '//' . BASEURL . 'core/admin/' . $src;

        }

        return $src;
    }

    /**
     * Pega o nome do tema atual
     * @return mixed
     */
    public function getName()
    {
        return Util::getConfig('config_template');
    }

    /**
     * Get theme url
     * @return string url
     */
    public function getUrl()
    {
        if (Util::getConfig('config_secure')) {
            return HTTPS_SERVER . '' . Util::getConfig('theme_path') . '/' . $this->getName() . '/';
        }

        return HTTP_SERVER . '' . Util::getConfig('theme_path') . '/' . $this->getName() . '/';
    }

    public function getThemes()
    {

    }

    /**
     * Pega os arquivos do tema da extensao
     * @param $folder for theme
     * @return array
     */
    public static function getFilesTheme($folder)
    {
        if (is_dir($folder . '/' . Util::getConfig('theme_path'))) {
            return Util::getFiles($folder . '/' . Util::getConfig('theme_path'));
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