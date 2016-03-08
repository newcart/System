<?php

namespace Newcart\System\Modification\System\Engine;

use Newcart\System\Helper\Util;

class Action
{
    private $file;
    private $class;
    private $method;
    private $args = array();

    public function __construct($route, $args = array())
    {
        $parts = explode('/', str_replace('../', '', (string)$route));

        // Break apart the route
        while ($parts) {

            global $registry;

            //load extension controller
            $extensions_file = glob(DIR_ROOT . '/' . $registry->get('config')->get('extension_path') . '/*/*/' . $registry->get('config')->get('environment') . '/controller/' . implode('/', $parts) . '.php');
            if ($extensions_file && is_array($extensions_file) && count($extensions_file)) {
                $file = $extensions_file[0];
            } else {
                $file = DIR_APPLICATION . 'controller/' . implode('/', $parts) . '.php';
            }

            if (is_file($file)) {
                $this->file = $file;

                $this->class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', implode('/', $parts));
                break;
            } else {
                $this->method = array_pop($parts);
            }
        }

        if (!$this->method) {
            $this->method = 'index';
        }

        $this->args = $args;
    }

    public function execute($registry) {
        // Stop any magical methods being called
        if (substr($this->method, 0, 2) == '__') {
            return false;
        }

        if (is_file($this->file)) {
            include_once($this->file);

            $class = $this->class;

            $controller = new $class($registry);

            if (is_callable(array($controller, $this->method))) {
                return call_user_func(array($controller, $this->method), $this->args);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param array $args
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }
}