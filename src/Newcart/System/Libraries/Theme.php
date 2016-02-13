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
     * Pega o caminho do asset do tema
     * @param $path
     */
    public function asset($path)
    {

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