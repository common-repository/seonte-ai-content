<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Schedule the event to run every 5 minutes
add_action('my_cron_hook', 'sacas_cron');
if (!wp_next_scheduled('my_cron_hook')) {
    $bool = wp_schedule_event(time(), 'daily', 'my_cron_hook');
	if ($bool) {
		//echo 'event setup successfully.';
	} else {
		//echo $bool;
	}
} else {
	//$timestamp = wp_next_scheduled('my_cron_hook');
	//echo date('Y-m-d H:i:s', $timestamp);
}

function sacas_cron(){
	$main_settings = get_option('sacas_options');
	$cron_interval = $main_settings['cron_interval'];
			
	$api_key = $main_settings['api_key'];
	$open_ai = OpenAI::client($api_key);
			
	$settings = get_option('sacastitles_options');
			
	if( !isset($settings['generated_title']) ){
		return true;
	}
		
	$all_titles = explode( "\n", $settings['generated_title'] ) ;
	$all_titles = array_filter($all_titles);

	if( count($all_titles) == 0 ){
		return true;
	}

	$title_to_use = $all_titles[0];

	unset( $all_titles[0] );

	$settings['generated_title'] = implode( "\n", $all_titles );
	update_option('sacastitles_options', $settings);

	$query = new WP_Query(
		array(
			'post_type'              => 'post',
			'title'                  => $title_to_use,
			'post_status'            => 'published',
			'posts_per_page'         => 1,
			'no_found_rows'          => true,
			'ignore_sticky_posts'    => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'orderby'                => 'post_date ID',
			'order'                  => 'ASC',
		)
	);

	if ( ! empty( $query->post ) ) {
		//we don't add the new post as there is duplicate title
	} else {
		$nr_words_per_article = $main_settings['nr_words_per_article'];

		if ($nr_words_per_article != 0) {
			$prompt = 'Please, write a '.$nr_words_per_article.' words article based on title: "'.$title_to_use.'".';
		} else {
			$prompt = 'Please, write article based on title: "'.$title_to_use.'".';
		}
					
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

		if( $out_text ){
			$post_id = wp_insert_post([
				'post_title' => $title_to_use,
				'post_content' => $out_text,
				'post_status' => 'publish'
			]);
		}
	}
	wp_reset_postdata();
}
 
?>