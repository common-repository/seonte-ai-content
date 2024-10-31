<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once(ABSPATH . '/wp-load.php');
require_once(ABSPATH . 'wp-includes/pluggable.php');
require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/misc.php');
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');

function sacas_uri_to_array($uri){
	$result = array();
 
	parse_str(substr($uri, strpos($uri, '?') + 1), $result);
	@list($result['user'], $result['page']) = @explode('/', trim($uri, '/'));
  
	return $result;
}
 
function sacas_create_page( $prompt ){
    $settings = get_option('sacas_options');
		$api_key = $settings['api_key'];

		$open_ai = OpenAI::client($api_key);
	
		$complete = $open_ai->chat()->create([
			'model' => 'gpt-4o',
			'messages' => [
			 
				[
					"role" => "user",
					"content" => $prompt
				],
			],
			'temperature' => 1,
			'max_tokens' => 3000,
			'frequency_penalty' => 0,
			'presence_penalty' => 0,
		]);

	$out_text = $complete['choices'][0]['message']['content'];
	
    return $out_text;
}

function sacas_generate_categories( $cat_number  ){
    $settings = get_option('sacas_options');
		$api_key = $settings['api_key'];

		$open_ai = OpenAI::client($api_key);
 
        $prompt = 'Generate '.sprintf(esc_html__( '%s', 'seonte-ai-content' ), esc_html($cat_number)).' categories based on title: '.get_option('home').' and return as JSON';

		$complete = $open_ai->chat()->create([
			'model' => 'gpt-4o',
			'messages' => [
			 
				[
					"role" => "user",
					"content" => $prompt
				],
			],
			'temperature' => 1,
			'max_tokens' => 3000,
			'frequency_penalty' => 0,
			'presence_penalty' => 0,
		]);
	
	$out_text = $complete['choices'][0]['message']['content'];
	
    return $out_text;
}
