<?php

/**
 * Registra o theme library
 */
use Newcart\System\Libraries\Theme;
$theme = new Theme($registry);
$registry->set('theme', $theme);