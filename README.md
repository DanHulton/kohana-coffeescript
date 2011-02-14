# CoffeeScript

*CoffeeScript compiler*

- **Module Version:** 1.0.0
- **Module URL:** <http://github.com/DanHulton/kohana-coffeescript>
- **Compatible Kohana Version(s):** 3.1.x

## Description

"CoffeeScript is a little language that compiles into JavaScript. Underneath all of those embarrassing braces and semicolons, JavaScript has always had a gorgeous object model at its heart. CoffeeScript is an attempt to expose the good parts of JavaScript in a simple way." -- http://jashkenas.github.com/coffee-script/

However, working with CoffeeScript requires you compile into javascript before it is executable in the browser.  This module will compile and minify CoffeeScript files at runtime and cache the results so you don't have to worry about adding this step to your deploy and deploy testing.

Bonus benefit: You can add regular JavaScript files to the compilation, turning all your application and module code into one minified .js file.

## Requirements & Installation

1. Set up a command-line CoffeeScript compiler (see below for details).
2. Download or clone this repository to your Kohana modules directory.
3. Modify the "command" argument in config/coffeescript.php to match your commind-line CoffeeScript compiler. 
4. Enable the module in your 'bootstrap.php' file.
5. Compile scripts directly into a template variable or a view.

## Example View

`application/views/example.php`

	<html>
	<head>
		<?=$scripts?>
	</head>
	...

## Example Controller

`application/classes/controller/example.php`

	public function action_index() {
		$this->response->body(View::factory('example', array(
			'scripts' => CoffeeScript::compile(array(
				APPPATH . 'js/jquery-autocomplete-plugin.js',
				APPPATH . 'js/sample.coffee'
			))
		)));
	}

## Integration with head.js

If you use head.js or another javascript loader that requires you pass the name of the javascript file instead of creating a link directly into the document, you can simply pass an extra variable to compile(), like so:

	CoffeeScript::compile(array(
		APPPATH . 'js/jquery-autocomplete-plugin.js',
		APPPATH . 'js/sample.coffee'
	), CoffeeScript::FORMAT_FILENAME);
	
Which returns:

	/js/db554181f11f9189951cea98f3b4b697-1288017184.js
	
Instead of the tag:

	<script src="/js/js-cache/db554181f11f9189951cea98f3b4b697-1288017184.js"></script>

## Command-line CoffeeScript compilers

- [CoffeeScript-Compiler-for-Windows](https://github.com/alisey/CoffeeScript-Compiler-for-Windows) The CLI compiler this script was written and tested with.
- [JCoffeeScript](https://github.com/yeungda/jcoffeescript) A compiler that runs via Java.
- [RubyCoffeeScript](https://github.com/josh/ruby-coffee-script) A Ruby gem-based compiler.  Handy if you already have Ruby installed.

## Thanks to

- [Zeelot](https://github.com/Zeelot/) One of my Kohana heroes, and from whom I shamelessly stole this README format.
- [Douglas Crockford](http://www.crockford.com/) My big Javascript hero, and author of the original JsMin.
- [Ryan Grove](http://wonko.com/) Author of the PHP conversion of Crockford's JsMin script that I use in the `vendor` directory.
- [Mon Geslani](https://github.com/mongeslani/kohana-less) Author of Kohana LESS compiler module, from which I took the original inspiration.

Last but not least:

- [Jeremy Ashkenas](https://github.com/jashkenas) Thanks for CoffeeScript!