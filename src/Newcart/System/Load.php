<?php

//Load configs
use Noodlehaus\Config;
$config = new Config(DIR_ROOT . '/config');

$GLOBALS['config'] = $config;

//get constants
require_once __DIR__ . '/constants.php';

// VirtualQMOD
if ($config->get('enable_vqmod')) {
    class_alias('Newcart\System\Vqmod\Vqmod', 'Vqmod');
    Vqmod::bootup(DIR_STORAGE);
}

//Bootstrap
require_once(DIR_APPLICATION . 'bootstrap.php');