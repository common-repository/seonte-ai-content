<?php
/*
Plugin Name: Seonte AI Content and SEO
Description: AutoContent AI for WordPress: Easily Create Text, Images and Comments on AutoPilot - This plugin provides you way of creating automated AI content based on one or more keywords. First you generate one ore more titles based on a keyword that it is editable (that is initially the site's name) and then the plugin creates a new article post, with the help of openai.com API, one per day for example taking one title from the list of titles that was created previously.
Version: 1.1.2
Author: SAVVY HUB S.R.L.
Author URI: https://www.nalery.com/
Tested up to: 6.6.1
Requires PHP: 8.2
Stable tag: 1.1.2
License: GPLv3
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// core initiation
if( !class_Exists('sacasMainStart') ){
	class sacasMainStart{
		public $locale;
		function __construct( $locale, $includes, $path ){
			$this->locale = $locale;
			
			// include files
			foreach( $includes as $single_path ){
				include( $path.$single_path );				
			}
			// calling localization
			add_action('plugins_loaded', array( $this, 'myplugin_init' ) );

			register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
			
			register_uninstall_hook(__FILE__, 'plugin_uninstall');
		}

		function plugin_activation(){
			flush_rewrite_rules();
		}
		
		function plugin_uninstall(){
			 
		}

		function myplugin_init() {
		 	$plugin_dir = basename(dirname(__FILE__));
		 	load_plugin_textdomain( $this->locale , false, $plugin_dir );
		}
	}
	
	
}



// initiate main class
 
$files_array =  array(
	'modules/class-form-elements.php',
	'modules/scripts.php',
	'modules/ajax.php',
	'modules/hooks.php',
	'modules/functions.php',
	'modules/settings.php',
);

if( !class_exists('ComposerAutoloaderInit451fa8820a68e533c1cba8db0d383730') ){
	$files_array[] = 'modules/inc/openai-client/vendor/autoload.php';
}

$obj = new sacasMainStart('seonte-ai-content', $files_array, dirname(__FILE__).'/' );
 
 
if( !function_exists('sacasvd') ){
	function sacasvd( $variable ){
		var_dump( $variable );
	}
}

if( !function_exists('sacasve') ){
	function sacasve( $variable ){
		var_export( $variable, true );
	}
}

?>