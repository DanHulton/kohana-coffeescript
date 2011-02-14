<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Compiles CoffeeScript files into one JavaScript file, optionally minifying it.
 */
class CsCompile_Core {
	// The formats that compile can return.
	const FORMAT_TAG 		= 'tag';
	const FORMAT_FILENAME	= 'filename';
	
	/**
	 * Compiles CoffeeScript files into one Javascript file.
	 *
	 * @param array  $files  The files to compile.
	 * @param string $format The format to return the compiled JS files in.
	 * 
	 * @return string
	 */
	public static function compile($files = array(), $format = self::FORMAT_TAG) {
		// Compiled contents of file
		$compiled = "";
		
        // Load config file
        $config = Kohana::config('cscompile');
        
        // If no files to compile, no tag necessary
        if (empty($files)) {
            return "";
        }
		
		// Get filename to save compiled files to
		$compiled_filename = self::get_filename($files, $config['path']);
		
		// If file doesn't exist already, files have changed, recompile them
		if ( ! file_exists($compiled_filename)) {
			// Loop through all files
			foreach ($files as $file) {
				// If file doesn't exist, log the fact and skip
				if ( ! file_exists($file)) {
					Kohana::$log->add("ERROR", "Could not find file: $file");
					continue;
				}
				
				// Get contents of file
				$contents = self::get_js_contents($file);
				
				// Compress if allowed
				if ($config['compress']) {
					$contents = JSMin::minify($contents);
				}
				
				// Append
				$compiled .= "\n$contents";
			}
			
			// Write new file
			file_put_contents($compiled_filename, $compiled);
		}
        
        switch ($format) {
			case self::FORMAT_TAG:
				$result = html::script($compiled_filename);
			break;
			
			case self::FORMAT_FILENAME:
				$result = $compiled_filename;
			break;
			
			default:
				throw new Exception("Unknown format: $format.");
		}
		
		return $result;
    }
	
	/**
	 * Get the contents of the provided file as javascript.
	 *
	 * @param string $file The name of the file to read.
	 *
	 * @return string
	 */
	protected static function get_js_contents($file) {
		$pathinfo = pathinfo($file);
		switch ($pathinfo['extension']) {
			case "js":
				$contents = file_get_contents($file);
			break;
			
			case "coffee":
				$contents = self::coffee_compile($file);
			break;
			
			default:
				throw new Kohana_Exception("Unknown file type: :ext.", array(':ext' => $pathinfo['extension']));
		}
		
		return $contents;
	}
	
	/**
	 * Compiles the provided coffeescript file, returns the compiled javascript.
	 *
	 * @param string $file The coffeescript file to compile.
	 *
	 * @return string
	 */
	protected static function coffee_compile($file) {
		exec(__(Kohana::config('cscompile.command'), array(':src' => $file)), $output);
		
		// First line is wrapper only on successful compile
		if ("(function() {" !== $output[0]) {
			// If unsuccessful, first line is error message
			throw new Kohana_Exception($output[0]);
		}
		
		return implode("\n", $output);
	}
	
	/**
	 * Gets the filename that will be used to save these files.
	 *
	 * @param array  $files The files to be compiled.
	 * @param string $path  The path to save the compiled file to.
	 *
	 * @return string
	 */
	protected static function get_filename($files, $path) {
        // Most recently modified file
        $last_modified = 0;
        
		foreach($files as $file) {
            // Check if this file was the most recently modified
            $last_modified = max(filemtime($file), $last_modified);
		}
		
		return $path . md5(implode("|", $files)) . "-$last_modified.js";
	}
}