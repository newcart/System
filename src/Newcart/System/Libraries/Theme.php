<?php

namespace Newcart\System\Libraries;

/**
 * Class Theme
 * @description Library desenvolvida para
 * ajudar o desenvolvedor no tema
 * @package Newcart\System\Libraries
 */
class Theme
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
     * Get asset path
     * @param string $src
     * @param bool $minify
     * @return string
     * @todo minify files css and js
     */
    public function asset($src, $minify = false)
    {
        if (!$this->getConfig('is_admin')) {

            if (file_exists(DIR_TEMPLATE . $this->getName() . '/' . $this->getConfig('assets_path') . '/' . $src)) {
                return $this->getConfig('theme_path') . '/' . $this->getName() . '/' . $this->getConfig('assets_path') . '/' . $src;
            }

        } else if (file_exists(DIR_TEMPLATE . '../' . $src)) {

            return 'admin/view/' . $src;

        }

        return $src;
    }

    /**
     * Pega o nome do tema atual
     * @return mixed
     */
    public function getName()
    {
        return $this->getConfig('config_template');
    }

    /**
     * Get theme url
     * @return string url
     */
    public function getUrl()
    {
        if ($this->getConfig('config_secure')) {
            return HTTPS_SERVER . '' . $this->getConfig('theme_path') . '/' . $this->getName() . '/';
        }

        return HTTP_SERVER . '' . $this->getConfig('theme_path') . '/' . $this->getName() . '/';
    }

    public function getThemes()
    {

    }
}