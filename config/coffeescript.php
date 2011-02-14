<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
    /**
     * Minify the resulting javascript files.
     * SLOW - Only use true when deployed.
     */
	'compress'      => false,
    
    /**
     * Path where compiled files are stored.
     */
	'path'      => "js/",
    
    /**
     * The command to execute to compile a CoffeeScript file
     */
    'command'       => 'D:\xampp\coffeescript\coffee.bat :src 2>&1',
);