<?php

namespace Newcart\System\Util;


class Util
{
    public static function requireConstants()
    {
        $root = realpath(__DIR__ . '/../../../../../../../');

        require_once __DIR__ . '/../constants.php';
    }
}