<?php

namespace Newcart\System\TwigExtensions;

use Newcart\System\Libraries\Theme;

/**
 * Metastore Extension class.
 *
 * This class is used by Metastore as a twig extension and must not be used directly.
 *
 */
class Ecommerce extends \Twig_Extension
{
    protected $registry;
    protected $is_admin;

    /**
     * @param \Registry $registry
     */
    public function __construct(\Registry $registry)
    {
        $this->registry = $registry;

        $this->is_admin = IS_ADMIN;
    }

    /**
     * Get Theme library intance
     * @return Theme
     */
    private function getThemeInstance()
    {
        return new Theme($this->registry);
    }


    /****************************
     ******** FUCTIONS **********
     ****************************/

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('link', array($this, 'linkFunction')),
            new \Twig_SimpleFunction('lang', array($this, 'langFunction')),
            new \Twig_SimpleFunction('config', array($this, 'configFunction')),
            new \Twig_SimpleFunction('logged', array($this, 'loggedFunction')),
            new \Twig_SimpleFunction('paginate', array($this, 'paginateFunction')),
            new \Twig_SimpleFunction('image', array($this, 'imageFunction')),
            new \Twig_SimpleFunction('asset', array($this, 'assetFunction')),
            new \Twig_SimpleFunction('load', array($this, 'loadFunction')),
            new \Twig_SimpleFunction('customer', array($this, 'customerFunction')),
            new \Twig_SimpleFunction('can_access', array($this, 'canAccessFunction')),
            new \Twig_SimpleFunction('can_modify', array($this, 'canModifyFunction')),
            new \Twig_SimpleFunction('session', array($this, 'sessionFunction')),
        );
    }

    /**
     * Url link function
     *
     * @param null $route
     * @param array $args
     * @param bool $secure
     *
     * @return string
     */
    public function linkFunction($route = null, $secure = false, $args = array())
    {
        $url = $this->registry->get('url');
        $session = $this->registry->get('session');
        $token = isset($session->data['token']) ? $session->data['token'] : null;

        if ($this->is_admin && $token) {
            $args['token'] = $token;
        }

        if (is_array($args)) {
            $args = http_build_query($args);
        }

        if (!empty($route)) {
            return $url->link($route, $args, $secure);
        } else if ($secure) {
            return !empty($args) ? HTTPS_SERVER . 'index.php?' . $args : HTTPS_SERVER;
        }

        return !empty($args) ? HTTP_SERVER . 'index.php?' . $args : HTTP_SERVER;
    }

    /**
     * Get Language
     *
     * @param      $key
     * @param null $file
     *
     * @return mixed
     */
    public function langFunction($key, $file = null)
    {
        $language = $this->registry->get('language');

        if ($file) {
            $language->load($file);
        }

        return $language->get($key);
    }

    /**
     * Get session any of store
     *
     * @param string $key
     * @return mixed|null|string
     */
    public function sessionFunction($key = '')
    {
        if ($key) {
            $session = $this->registry->get('session');
            return isset($session->data[$key]) ? $session->data[$key] : '';
        } else {
            return $this->registry->get('session');
        }
    }

    /**
     * Get config
     *
     * @param      $key
     * @param null $file
     *
     * @return mixed
     */
    public function configFunction($key, $file = null)
    {
        $config = $this->registry->get('config');

        if ($file) {
            $config->load($file);
        }

        return $config->get($key);
    }

    /**
     * Check customer is logged
     *
     * @return bool
     */
    public function loggedFunction()
    {
        $customer = $this->registry->get('customer');
        return (boolean)$customer->isLogged();
    }

    /**
     * Check user has permission from access
     *
     * @param $value
     * @return bool
     */
    public function canAccessFunction($value)
    {
        $user = $this->registry->get('user');

        if ($user) {
            return $user->hasPermission('access', $value);
        }

        return false;
    }

    /**
     * Check user has permission from modify
     *
     * @param $value
     *
     * @return bool
     */
    public function canModifyFunction($value)
    {
        $user = $this->registry->get('user');

        if ($user) {
            return $user->hasPermission('modify', $value);
        }

        return false;
    }

    /**
     * Get Customer object
     * @return mixed|null
     */
    public function customerFunction()
    {
        return $this->registry->get('customer');
    }

    /**
     * Get and resize images
     * @param        $filename
     * @param string $context
     *
     * @param null $width
     * @param null $height
     *
     * @return string|void
     */
    public function imageFunction($filename, $context = 'product', $width = null, $height = null)
    {
        if (!is_file(DIR_IMAGE . $filename)) {
            return;
        }

        $request = $this->registry->get('request');
        $config = $this->registry->get('config');

        if (!$width) {
            $width = $config->get('config_image_' . $context . '_width');
        }

        if (!$height) {
            $height = $config->get('config_image_' . $context . '_height');
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        $old_image = $filename;
        $new_image = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;

        if (!is_file(DIR_IMAGE . $new_image) || (filectime(DIR_IMAGE . $old_image) > filectime(DIR_IMAGE . $new_image))) {
            $path = '';

            $directories = explode('/', dirname(str_replace('../', '', $new_image)));

            foreach ($directories as $directory) {
                $path = $path . '/' . $directory;

                if (!is_dir(DIR_IMAGE . $path)) {
                    @mkdir(DIR_IMAGE . $path, 0777);
                }
            }

            list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);

            if ($width_orig != $width || $height_orig != $height) {
                $image = new \Image(DIR_IMAGE . $old_image);
                $image->resize($width, $height);
                $image->save(DIR_IMAGE . $new_image);
            } else {
                copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
            }
        }

        if ($request->server['HTTPS']) {
            return $this->is_admin ? HTTPS_CATALOG . 'image/' . $new_image : HTTPS_SERVER . 'image/' . $new_image;
        } else {
            return $this->is_admin ? HTTP_CATALOG . 'image/' . $new_image : HTTP_SERVER . 'image/' . $new_image;
        }
    }

    /**
     * Get asset path
     *
     * @param string $src
     * @param bool $minify
     * @return string
     */
    public function assetFunction($src, $minify = false)
    {
        return $this
            ->getThemeInstance()
            ->asset($src, $minify);
    }

    /**
     * @todo terminar minificacao dos assets
     * @param $path
     */
    private function minify($path)
    {
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if ($ext == 'css') {
            $minifier = new \MatthiasMullie\Minify\CSS($path);
        } else if ($ext == 'js') {
            $minifier = new \MatthiasMullie\Minify\JS($path);
        }

    }

    /**
     * Load controller method
     *
     * @param $file
     *
     * @return mixed
     */
    public function loadFunction($file)
    {
        $loader = $this->registry->get('load');

        return $loader->controller($file);
    }

    /**
     * Make and return pagination
     *
     * @param       $total
     * @param null $route
     * @param array $args
     * @param null $template
     *
     * @return string
     */
    public function paginateFunction($total, $limit = 5, $route = null, $args = array(), $template = null)
    {
        $request = $this->registry->get('request');
        $page = isset($request->get['page']) ? $request->get['page'] : 1;
        $secure = $request->server['HTTPS'];

        $pagination = new \Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $limit;

        $args['page'] = '{page}';

        $pagination->url = $this->linkFunction($route, $args, $secure);

        if ($template) {
            $loader = $this->registry->get('load');

            return $loader->view($template, get_object_vars($pagination));
        } else {
            return $pagination->render();
        }
    }



    /***************************
     ******** FILTERS **********
     ***************************/

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('money', array($this, 'moneyFilter')),
            new \Twig_SimpleFilter('tax', array($this, 'taxFilter')),
            new \Twig_SimpleFilter('len', array($this, 'lenFilter')),
            new \Twig_SimpleFilter('wei', array($this, 'weiFilter')),
            new \Twig_SimpleFilter('truncate', array($this, 'truncateFilter')),
            new \Twig_SimpleFilter('encrypt', array($this, 'encryptFilter')),
            new \Twig_SimpleFilter('decrypt', array($this, 'decryptFilter')),
        );
    }

    /**
     * @param        $number
     * @param string $currency
     * @param string $value
     * @param bool $format
     *
     * @return mixed
     */
    public function moneyFilter($number, $currency = '', $value = '', $format = true)
    {
        $lib = $this->registry->get('currency');

        return $lib->format($number, $currency, $value, $format);
    }

    /**
     * @param      $value
     * @param      $tax_class_id
     * @param bool $calculate
     *
     * @return mixed
     */
    public function taxFilter($value, $tax_class_id, $calculate = true)
    {
        $tax = $this->registry->get('tax');

        return $tax->calculate($value, $tax_class_id, $calculate);
    }

    /**
     * @param        $value
     * @param        $length_class_id
     * @param string $decimal_point
     * @param string $thousand_point
     *
     * @return mixed
     */
    public function lenFilter($value, $length_class_id, $decimal_point = '.', $thousand_point = ',')
    {
        $length = $this->registry->get('length');

        return $length->format($value, $length_class_id, $decimal_point, $thousand_point);
    }

    /**
     * @param        $value
     * @param        $weight_class_id
     * @param string $decimal_point
     * @param string $thousand_point
     *
     * @return mixed
     */
    public function weiFilter($value, $weight_class_id, $decimal_point = '.', $thousand_point = ',')
    {
        $weight = $this->registry->get('weight');

        return $weight->format($value, $weight_class_id, $decimal_point, $thousand_point);
    }

    /**
     * @param        $value
     * @param string $end
     * @param null $limit
     *
     * @return string
     */
    public function truncateFilter($value, $end = '...', $limit = null)
    {
        $config = $this->registry->get('config');

        if (!$limit) {
            $limit = $config->get('config_product_description_length');
        }

        $str = strip_tags(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));

        if (strlen($str) > $limit) {
            $str = utf8_substr($str, 0, $limit) . $end;
        }

        return $str;
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function encryptFilter($value)
    {
        $config = $this->registry->get('config');

        $encription = new \Encryption($config->get('config_encription'));

        return $encription->encrypt($value);
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function decryptFilter($value)
    {
        $config = $this->registry->get('config');

        $encription = new \Encryption($config->get('config_encription'));

        return $encription->decrypt($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        $document = $this->registry->get('document');

        $globals = array(
            'document_title' => $document->getTitle(),
            'document_description' => $document->getDescription(),
            'document_keywords' => $document->getKeywords(),
            'document_links' => $document->getLinks(),
            'document_styles' => $document->getStyles(),
            'document_scripts' => $document->getScripts(),
            'route' => isset($this->registry->get('request')->get['route']) ? $this->registry->get('request') : '',
        );

        if ($this->is_admin) {
            $user = $this->registry->get('user');
            $globals['user'] = $user;
            $globals['is_logged'] = $user->isLogged();
        } else {
            $customer = $this->registry->get('customer');
            $globals['customer'] = $customer;
            $globals['is_logged'] = $customer->isLogged();

            $affiliate = $this->registry->get('affiliate');
            $globals['affiliate'] = $affiliate;
            $globals['is_affiliate_logged'] = $affiliate->isLogged();

            $globals['cart'] = $this->registry->get('cart');
        }

        return $globals;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'opencart';
    }
}
