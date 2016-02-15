<?php

namespace Newcart\System\Libraries;


class Theme
{
    /**
     * Opencart Registry
     * @var \Registry
     */
    private $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    /**
     * Get asset path
     * @param string $src
     * @param bool $minify
     * @return string
     */
    public function asset($src, $minify = false)
    {
        if (!IS_ADMIN) {

            $config = $this->registry->get('config');
            if (file_exists(DIR_TEMPLATE . $this->getName() . '/' . $config->get('assets_path') . '/' . $src)) {
                return $config->get('theme_path') . '/' . $this->getName() . '/' . $config->get('assets_path') . '/' . $src;
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
        return $this->registry->get('config')->get('config_template');
    }
}