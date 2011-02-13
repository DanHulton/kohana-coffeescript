<?php defined('SYSPATH') OR die('No direct access allowed.');

$cscompile_path = Kohana::config('cscompile.path');

if ( ! is_writable($cscompile_path)) {
    throw new Kohana_Exception('Directory :dir must be writable.',
        array(':dir' => Debug::path($cscompile_path)));
}

require_once 'vendor/jsmin.php';