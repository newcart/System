<?php

//Load configs
use Noodlehaus\Config;
$config = new Config(DIR_ROOT . '/config');

$GLOBALS['config'] = $config;

// VirtualQMOD
class_alias('Newcart\System\Vqmod\Vqmod', 'Vqmod');