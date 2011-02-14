<?php defined('SYSPATH') OR die('No direct access allowed.');

$cs_path = Kohana::config('CoffeeScript.path');

if ( ! is_writable($cs_path)) {
    throw new Kohana_Exception('Directory :dir must be writable.',
        array(':dir' => Debug::path($cs_path)));
}

require_once 'vendor/jsmin.php';