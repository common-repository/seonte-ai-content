<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('wp_ajax_wizzard_processing', 'sacas_wizzard_processing');
add_action('wp_ajax_nopriv_wizzard_processing', 'sacas_wizzard_processing');

function sacas_wizzard_processing(){
	global $current_user, $wpdb;
	if( check_ajax_referer( 'ajax_call_nonce', 'security') ){
 
		if( !$_POST['processed_statuses'] ){
			$processed_statuses =  [];
		}else{
			$processed_statuses =  array_map( 'sanitize_text_field', $_POST['processed_statuses'] );
			
			// sanitizing
			if (is_array($processed_statuses)) {
				foreach ($processed_statuses as &$processed_status) {
					$processed_status = sprintf(esc_attr__('%s', 'seonte-ai-content'), esc_attr($processed_status));
				}
				unset($processed_status);
			} else {
				$processed_statuses = sprintf(esc_attr__('%s', 'seonte-ai-content'), esc_attr($processed_statuses));
			}
		}
		
		 
		$status_list = [
			'1' => 'Remove default posts',
			'2' => 'Remove default pages',
			'3' => 'Create About Us Page',
			'4' => 'Create Contact Us Page',
			'5' => 'Create Privacy Policy Page',
			'6' => 'Create ToS Page',
			'7' => 'Create Disclosure Page',
			'8' => 'Create Author Bio Page',
			'9' => 'Generate categories',
		];

		$current_status = false;
		$data = sacas_uri_to_array( '?'.sanitize_text_field( wp_unslash (  $_POST['formdata'] ) ) );
		
		if( !isset($data['create_pages'] ) ){
			$data['create_pages']  = [];
		}
		if( !isset($data['install_plugins'] ) ){
			$data['install_plugins']  = [];
		}

		if( $data['delete_posts'] == 'yes' && !in_array( 1, $processed_statuses ) ){
			
			$current_status = 1;
		
			$all_posts = get_posts();
			foreach( $all_posts as $s_post ){
				wp_delete_post( $s_post->ID, true );
			}
	
			$processed_statuses[] = $current_status;
			echo wp_json_encode( [ 'result' => 'success', 'status' => $status_list[$current_status], 'processed' => $processed_statuses ]);
			die();
		}
		if( $data['delete_pages'] == 'yes' && !in_array( 2, $processed_statuses ) ){
			$current_status = 2;
		
			$all_posts = get_posts([
				'post_type' => 'page'
			]);
			foreach( $all_posts as $s_post ){
				wp_delete_post( $s_post->ID, true );
			}
		
			$processed_statuses[] = $current_status;
			echo wp_json_encode( [ 'result' => 'success', 'status' => $status_list[$current_status], 'processed' => $processed_statuses ]);
			die();
		}
		
		if( in_array( 'about_us' , $data['create_pages'] ) && !in_array( 3, $processed_statuses ) ){
			$current_status = 3;
			
			/** locker */
			if( get_transient('sacas_we_working') ){
				die();
			}
			set_transient('sacas_we_working', '1', 60 );
			/** locker  END*/

			$prompt = "Please write a 350 words about us text for the following website, ".get_bloginfo('name');
			$page_content = sacas_create_page( $prompt );

			wp_insert_post([
				'post_type' => 'page',
				'post_title' => 'About Us',
				'post_content' => $page_content,
				'post_status' => 'publish'
			]);
		 
			$processed_statuses[] = $current_status;
			set_transient('sacas_we_working', '1', -1 );
			echo wp_json_encode( [ 'result' => 'success', 'status' => $status_list[$current_status], 'processed' => $processed_statuses ]);
			die();
		}
		if( in_array( 'contact_us' , $data['create_pages'] )  && !in_array( 4, $processed_statuses ) ){
			$current_status = 4;

			/** locker */
			if( get_transient('sacas_we_working') ){
				die();
			}
			set_transient('sacas_we_working', '1', 60 );
			/** locker  END*/

			wp_insert_post([
				'post_type' => 'page',
				'post_title' => 'Contact Us',
				'post_content' => '',
				'post_status' => 'publish'
			]);
			
 

			$processed_statuses[] = $current_status;
			set_transient('sacas_we_working', '1', -1 );
			echo wp_json_encode( [ 'result' => 'success', 'status' => $status_list[$current_status], 'processed' => $processed_statuses ]);
			die();
		}
		if( in_array( 'privacy_policy' , $data['create_pages'] ) && !in_array( 5, $processed_statuses ) ){
			$current_status = 5;

			/** locker */
			if( get_transient('sacas_we_working') ){
				die();
			}
			set_transient('sacas_we_working', '1', 60 );
			/** locker  END*/

			sacas_create_page('privacy_policy');

			$prompt = "Please write an privacy policy text for the following website ".get_option('home');
			$page_content = sacas_create_page( $prompt );

			wp_insert_post([
				'post_type' => 'page',
				'post_title' => 'Privacy Policy',
				'post_content' => $page_content,
				'post_status' => 'publish'
			]);

			$processed_statuses[] = $current_status;
			set_transient('sacas_we_working', '1', -1 );
			echo wp_json_encode( [ 'result' => 'success', 'status' => $status_list[$current_status], 'processed' => $processed_statuses ]);
			die();
		}
		if( in_array( 'terms_and_conditions' , $data['create_pages'] )  && !in_array( 6, $processed_statuses ) ){
			$current_status = 6;
		 

			/** locker */
			if( get_transient('sacas_we_working') ){
				die();
			}
			set_transient('sacas_we_working', '1', 60 );
			/** locker  END*/

			$prompt = "Please write a terms and conditions text for the following website ".get_option('home');
			$page_content = sacas_create_page( $prompt );

			wp_insert_post([
				'post_type' => 'page',
				'post_title' => 'Terms and conditions',
				'post_content' => $page_content,
				'post_status' => 'publish'
			]);

			$processed_statuses[] = $current_status;
			set_transient('sacas_we_working', '1', -1 );
			echo wp_json_encode( [ 'result' => 'success', 'status' => $status_list[$current_status], 'processed' => $processed_statuses ]);
			die();
		}
		if( in_array( 'disclosure' , $data['create_pages'] )  && !in_array( 7, $processed_statuses ) ){
			$current_status = 7;
			
			/** locker */
			if( get_transient('sacas_we_working') ){
				die();
			}
			set_transient('sacas_we_working', '1', 60 );
			/** locker  END*/
			$prompt = "Please write a disclosure text for the following website ".get_option('home');
			$page_content = sacas_create_page( $prompt );

			wp_insert_post([
				'post_type' => 'page',
				'post_title' => 'Disclosure',
				'post_content' => $page_content,
				'post_status' => 'publish'
			]);

			$processed_statuses[] = $current_status;
			set_transient('sacas_we_working', '1', -1 );
			echo wp_json_encode( [ 'result' => 'success', 'status' => $status_list[$current_status], 'processed' => $processed_statuses ]);
			die();
		}
		if( in_array( 'author_bio' , $data['create_pages'] )  && !in_array( 8, $processed_statuses ) ){
			$current_status = 8;

			/** locker */
			if( get_transient('sacas_we_working') ){
				die();
			}
			set_transient('sacas_we_working', '1', 60 );
			/** locker  END*/ 

			$prompt = "Please write author bio text for the following website ".get_option('home');
			$page_content = sacas_create_page( $prompt );

			wp_insert_post([
				'post_type' => 'page',
				'post_title' => 'Author Bio',
				'post_content' => $page_content,
				'post_status' => 'publish'
			]);

			$processed_statuses[] = $current_status;
			set_transient('sacas_we_working', '1', -1 );
			echo wp_json_encode( [ 'result' => 'success', 'status' => $status_list[$current_status], 'processed' => $processed_statuses ]);
			die();
		}

 		if( $data['create_cats'] == 'yes' && !in_array( 9, $processed_statuses ) ){
			
			$current_status = 9;
		
			$cats = sacas_generate_categories( $data['cat_numbers'] );
			$decoded = json_decode( $cats );
			
			$cats_list = [];
			if( $decoded ){
				if( $decoded->categories ){
					$cats_list = $decoded->categories;
				}else{
					$cats_list = $decoded;
				}
			}
	 
			if( count( $cats_list ) > 0 ){
				foreach( $cats_list as $s_cat ){
					$term = wp_insert_term( $s_cat, 'category' );
				}
				
			}

			$processed_statuses[] = $current_status;
			echo wp_json_encode( [ 'result' => 'success', 'status' => $status_list[$current_status], 'processed' => $processed_statuses ]);
			die();
		}
		
		echo wp_json_encode( [ 'result' => 'success', 'status' => 'finished'  ]);
			die();
	}
	die();
}


add_action('wp_ajax_generate_titles', 'sacas_generate_titles');
add_action('wp_ajax_nopriv_generate_titles', 'sacas_generate_titles');

function sacas_generate_titles(){
	global $current_user, $wpdb;
	if( check_ajax_referer( 'ajax_call_nonce', 'security') ) {

		$settings = get_option('wsw_options');
		$api_key = $settings['api_key'];

		$title = sanitize_text_field( $_POST['title'] );
		$nr_titles = sanitize_text_field( $_POST['nr_titles'] );
		
		$open_ai_client = OpenAI::client($api_key);
		
		$complete = $open_ai_client->chat()->create([
			'model' => 'gpt-4o',
			'messages' => [
			 
				[
					"role" => "user",
					"content" => "Please write ".$nr_titles." unique articles titles related to this topic: '".$title."' without number in title or quotation marks or hyphen."
				],
			],
			'temperature' => 1,
			'max_tokens' => 3000,
			'frequency_penalty' => 0,
			'presence_penalty' => 0,
		]);

		$out_text = $complete['choices'][0]['message']['content'];
		
		$new_titles = [];
		$lits_titles = explode( "\n", $out_text );
		foreach( $lits_titles as $s_title ) {
			if( substr_count( $s_title, '.' ) > 0 ){
				$tmp = explode('.', $s_title);
				$s_title = trim( $tmp[1] );
			}else{
				$tmp = explode(' ', $s_title);
				if( (int)$tmp[0] != 0 ){
					array_shift( $tmp );
					$s_title = implode(' ', $tmp);
				}
			}
					
			$new_titles[] = $s_title;
		}

		$out_text = implode("\n", $new_titles);

		$current_options = get_option('sacastitles_options');
		if( $out_text ){
			if( isset( $current_options['generated_title'] ) ){
				$tmp = array_merge( explode("\n", $current_options['generated_title'] ), explode("\n", $out_text ) );
				$out_text = implode("\n", $tmp);
			}
		}

		$out = [
			'title' => sanitize_text_field( $_POST['title'] ),
			'generated_title' => $out_text,
		];

		update_option('sacastitles_options', $out );
		echo wp_json_encode([ 'result' => 'success', 'titles' => $out_text ]);
		die();
	}
}

?>