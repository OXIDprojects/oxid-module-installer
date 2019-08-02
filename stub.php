<?php

error_reporting(-1);

if (function_exists('ini_set')) {
    @ini_set('display_errors', 1);
    @ini_set('display_startup_errors', 1);
    @ini_set('opcache.enable', '0');
}

if (!extension_loaded('Phar')) {
    die('The PHP Phar extension is not enabled.');
}

if (false !== ($suhosin = ini_get('suhosin.executor.include.whitelist'))) {
    $allowed = array_map('trim', explode(',', $suhosin));

    if (!in_array('phar', $allowed) && !in_array('phar://', $allowed)) {
        die('The Suhosin extension does not allow to run .phar files.');
    }
}

if (false !== ($multibyte = ini_get('zend.multibyte')) && '' !== $multibyte && 0 !== (int) $multibyte && 'Off' !== $multibyte) {
    $unicode = ini_get(version_compare(phpversion(), '5.4', '<') ? 'detect_unicode' : 'zend.detect_unicode');

    if ('' !== $unicode && 0 !== (int) $unicode && 'Off' !== $unicode) {
        die('The detect_unicode setting needs to be disabled in your php.ini.');
    }
}

unset($multibyte, $unicode);

if ('cgi-fcgi' === php_sapi_name() && extension_loaded('eaccelerator') && ini_get('eaccelerator.enable')) {
    die('The PHP eAccelerator extension cannot handle .phar files.');
}

if (function_exists('date_default_timezone_set') && function_exists('date_default_timezone_get')) {
    /** @noinspection UsageOfSilenceOperatorInspection */
    date_default_timezone_set(@date_default_timezone_get());
}

function rewrites() {
    // The function argument is unreliable across servers, Nginx for example is always empty
    list(,$url) = explode(basename(__FILE__), $_SERVER['REQUEST_URI'], 2);

    if (strpos($url, '..')) {
        return false;
    }

    if ('' === $url) {
        header('Location: /'.basename(__FILE__).'/');
        exit;
    }

    if (!empty($url) && is_file('phar://'.__FILE__.'/dist'.$url)) {
        return 'dist'.$url;
    }
    if (!empty($url) && is_file('phar://'.__FILE__.'/public/dist'.$url)) {
        return 'public/dist'.$url;
    }
    if (!empty($url) && is_file('phar://'.__FILE__.'/public'.$url)) {
        return 'public'.$url;
    }

    return '/public/index.php';
}

Phar::webPhar(
    null,
    'public/index.html',
    null,
    array(
        'log' => 'text/plain',
        'txt' => 'text/plain',
        'php' => Phar::PHP, // parse as PHP
        'css' => 'text/css',
        'gif' => 'image/gif',
        'html' => 'text/html',
        'ico' => 'image/x-ico',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'js' => 'application/x-javascript',
        'png' => 'image/png',
        'svg' => 'image/svg+xml',
        'json' => 'application/json'
    ),
    'rewrites'
);
__HALT_COMPILER();