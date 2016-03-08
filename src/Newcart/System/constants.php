<?php

define('DB_PREFIX', $config->get('db_prefix'));

//parse_str($_SERVER['QUERY_STRING'], $output);

$config->set('is_admin', $config->get('environment') == 'admin' ? true : false);

//get domain name
define('DOMAINNAME', isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null);

//define core directory
define('DIR_CORE', realpath(DIR_ROOT . '/core/'));

$base_url = DOMAINNAME . $_SERVER['SCRIPT_NAME'];
$base_url = str_replace(['/core/admin/', '/core', '/index.php'], '/', $base_url);
define('BASEURL', $base_url);

// HTTP
if ($config->get('is_admin')) {
    define('HTTP_SERVER', 'http://' . BASEURL . 'core/admin/');
    define('HTTP_SERVER_DYNAMIC', 'http://' . BASEURL . $config->get('admin_path') . '/');
} else {
    define('HTTP_SERVER', 'http://' . BASEURL);
}

// HTTPS
if ($config->get('is_admin')) {
    define('HTTPS_SERVER', 'https://' . BASEURL . 'core/admin/');
    define('HTTPS_SERVER_DYNAMIC', 'https://' . BASEURL . $config->get('admin_path') . '/');
} else {
    define('HTTPS_SERVER', 'https://' . BASEURL);
}

define('HTTP_CATALOG', 'http://' . BASEURL);
define('HTTPS_CATALOG', 'https://' . BASEURL);

// DIR
if ($config->get('is_admin')) {
    define('DIR_TEMPLATE', DIR_CORE . '/' . $config->get('environment') . '/view/template/');
} else {
    define('DIR_TEMPLATE', DIR_ROOT . '/' . $config->get('theme_path') . '/');
}

define('DIR_APPLICATION', DIR_CORE . '/' . $config->get('environment') . '/');
define('DIR_SYSTEM', DIR_CORE . '/system/');
define('DIR_STORAGE', DIR_ROOT . '/storage');
define('DIR_LANGUAGE', DIR_CORE . '/' . $config->get('environment') . '/language/');
define('DIR_CONFIG', DIR_ROOT . '/config/');
define('PATH_IMAGE', $config->get('image_path') . '/');
define('DIR_IMAGE', DIR_ROOT . '/' . PATH_IMAGE);
define('DIR_CACHE', DIR_STORAGE . '/cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . '/download/');
define('DIR_UPLOAD', DIR_STORAGE . '/upload/');
define('DIR_MODIFICATION', DIR_STORAGE . '/modification/');
define('DIR_LOGS', DIR_STORAGE . '/logs/');
define('DIR_CATALOG', DIR_CORE . '/catalog/');
define('DIR_VQMOD_CACHE', DIR_STORAGE . '/vqmod/');

define('DIR_EXTENSIONS', DIR_ROOT . '/' . $config->get('extension_path'));