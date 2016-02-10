<?php

namespace Newcart\System\Util;


class Util
{
    public static function requireConstants()
    {
        $root = realpath(__DIR__ . '/../../../../../../../');

        require_once $root . '/config/app.php';
        require_once $root . '/core/constants.php';
    }
}