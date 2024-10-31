<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('sacasSettingsClassWizzard') ){
#[AllowDynamicProperties]
class sacasSettingsClassWizzard{
	/* V1.0.2 */
	var $setttings_parameters;
	var $settings_prefix;
	var $message;
	
	function __construct( $prefix ){
		$this->setttings_prefix = $prefix;	
		
		if( isset($_POST[$this->setttings_prefix.'save_settings_field']) ){
			if(  wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST[$this->setttings_prefix.'save_settings_field'] ) ), $this->setttings_prefix.'save_settings_action') ){
				$options = array();
				$attrs_to_porcess = [
					'title2use',
					'generated_title',
					'nr_titles',
					'api_key',
					'nr_words_per_article'
				];
				foreach( $attrs_to_porcess as $s_key ){
					if( isset( $_POST[$s_key] ) ){
						$options[$s_key] = sanitize_text_field( wp_unslash ( $_POST[$s_key] ) ) ;
					}
				}
			 
				update_option( $this->setttings_prefix.'_options', $options );
			}
		}
	}
	
	function get_setting( $setting_name ){
		$inner_option = get_option( $this->setttings_prefix.'_options');
		return $inner_option[$setting_name];
	}
	
	function create_menu( $parameters ){
		$this->setttings_parameters = $parameters;		
		$this->message = '<div class="alert alert-success">'.$this->setttings_parameters['save_message'].'</div>';

		add_action('admin_menu', array( $this, 'add_menu_item') );
		
	}

	function add_menu_item(){

		$default_array = [
			'type' => '',
			'parent_slug' => '',
			'form_title' => '',
			'is_form' => '',
			'page_title' => '',
			'menu_title' => '',
			'capability' => '',
			'menu_slug' => '',
			'icon' => ''
		];	
		$this->setttings_parameters = array_merge( $default_array, $this->setttings_parameters );
 
		$block_type = $this->setttings_parameters['type'];
		$single_option = $this->setttings_parameters;
		
			if( $block_type == 'menu' ){
				add_menu_page(  			 
				$single_option['page_title'], 
				$single_option['menu_title'], 
				$single_option['capability'], 
				$this->setttings_prefix.$single_option['menu_slug'], 
				array( $this, 'show_settings' ),
				$single_option['icon']
				);
			}
			if( $block_type == 'submenu' ){
				add_submenu_page(  
				$single_option['parent_slug'],  
				$single_option['page_title'], 
				$single_option['menu_title'], 
				$single_option['capability'], 
				$this->setttings_prefix.$single_option['menu_slug'], 
				array( $this, 'show_settings' ) 
				);
			}
			if( $block_type == 'option' ){
	 
				add_options_page(  				  
				$single_option['page_title'], 
				$single_option['menu_title'], 
				$single_option['capability'], 
				$this->setttings_prefix.$single_option['menu_slug'], 
				array( $this, 'show_settings' ) 
				);
			}
	}
	
	function show_settings(){
		// hide output if its parent menu
		if( count( $this->setttings_parameters['parameters'] ) == 0 ){ return false; }
		
		?>
		<div class="wrap tw-bs4">
		
		
		
		<h2><?php echo sprintf(esc_html__( '%s', 'seonte-ai-content' ), esc_html($this->setttings_parameters['form_title'])); ?></h2>
		<hr/>
		<?php 
		if( isset($_POST[$this->setttings_prefix.'save_settings_field']) ){
			if(  wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST[$this->setttings_prefix.'save_settings_field'] ) ), $this->setttings_prefix.'save_settings_action') ){
				$label_filtering = array(
					'div' => array(
						'class' => array(),
						
					),				 
				);	
			echo wp_kses( $this->message, $label_filtering );
			}
		}
		?>
		
		<?php if( $this->setttings_parameters['is_form'] ): ?>
			<form class="form-horizontal" method="post" id="<?php echo sprintf(esc_attr__('%s', 'seonte-ai-content' ), esc_attr($this->setttings_prefix.$this->setttings_parameters['menu_slug'])); ?>" action="" enctype="multipart/form-data" >
		<?php endif; ?>

		<?php 
		wp_nonce_field( $this->setttings_prefix.'save_settings_action', $this->setttings_prefix.'save_settings_field'  );  
		$config = get_option( $this->setttings_prefix.'_options'); 
		 
		?>  
		<fieldset>

			<?php 
	 
				foreach( $this->setttings_parameters['parameters'] as $key=>$value ){	
					
					$interface_element_value =  '';
					if( isset($value['name']) ){
						if( isset( $config[$value['name']] ) ){
							$interface_element_value =  $config[$value['name']];
						}
					}
					
					
					$interface_element = new sacasElementsClassWizzard( $value['type'], $value, $interface_element_value );
					$arr = array( 
						'br' => array(), 
						'p' => array(), 
						'strong' => array(),
						'label' => array(),
						'div' => array(),
						'input' => array(),
						'select' => array(),
						'file' => array(),
					);
					echo  $interface_element->get_code() ;	 
				}
		 
			?>
		</fieldset>  
		
		<?php if( $this->setttings_parameters['is_form'] ): ?>
		</form>
		<?php endif; ?>

		</div>
		<?php
	}
}	
}	
 
	
	
add_Action('init',  function (){

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
	
	if ( defined('DOING_AJAX') && DOING_AJAX ) {
		return;
	}

 
	$config_big = 
 
		array(
			'type' => 'menu',
			'form_title' => __('Nalery AI Content and SEO', 'seonte-ai-content'),
			'is_form' => true,
			'page_title' => __('Nalery AI Content and SEO', 'seonte-ai-content'),
			'save_message' => __('Settings Saved', 'seonte-ai-content'),
			'menu_title' => __('Nalery AI Content and SEO', 'seonte-ai-content'),
			'capability' => 'edit_published_posts',
			'menu_slug' => 'main',
			'parameters' => array(		 
				array(
					'type' => 'text',
					'title' => __('Title, that will be used to generate title','seonte-ai-content'),
					'name' => 'title2use',
					'sub_text' => __('', 'seonte-ai-content'),
					'style' => ' ',
					'default' => get_bloginfo('name'),
					'id' => '',
					'class' => ''
				),
				array(
					'type' => 'textarea',
					'title' => __('List of generated titles','seonte-ai-content'),
					'name' => 'generated_title',
					'sub_text' => __('', 'seonte-ai-content'),
					'style' => ' height:300px;',
			 
					'id' => '',
					'class' => ''
				),
				array(
					'type' => 'text',
					'title' => __('Number of titles to generate for one click','seonte-ai-content'),
					'name' => 'nr_titles',
					'sub_text' => __('', 'seonte-ai-content'),
					'style' => ' ',
					'default' => '25',
					'id' => '',
					'class' => ''
				),
				array(
					'type' => 'button',
					'class' => 'generate_titles btn btn-success',
					'title' => __('Generate Titles', 'seonte-ai-content'),
				), 
				array(
					'type' => 'save',
					'title' => __('Save Changes', 'seonte-ai-content'),
				)
			)
		); 
	global $sacas_settings;

	$sacas_settings = new sacasSettingsClassWizzard( 'nacastitles' ); 
	$sacas_settings->create_menu(  $config_big   );

	$config_big = 
 
		array(
			'type' => 'submenu',
			'parent_slug' => 'nacastitlesmain',
			'form_title' => __('Settings', 'seonte-ai-content'),
			'is_form' => true,
			'page_title' => __('Settings', 'seonte-ai-content'),
			'save_message' => __('Settings Saved', 'seonte-ai-content'),
			'menu_title' => __('Settings', 'seonte-ai-content'),
			'capability' => 'edit_published_posts',
			'menu_slug' => 'main_settings',
			'parameters' => array(		 
				array(
					'type' => 'text',
					'title' => __('<b>Chat GPT API Key</b>, <a href="https://platform.openai.com/api-keys" target="_blank">click here to create it if you do not have one</a>','seonte-ai-content'),
					'name' => 'api_key',
					'sub_text' => __('', 'seonte-ai-content'),
					'style' => ' ',
					'id' => '',
					'class' => ''
				),
				array(
					'type' => 'text',
					'title' => __('<b>Number of words per article</b> (can be between 500 and 2000)','seonte-ai-content'),
					'name' => 'nr_words_per_article',
					'sub_text' => __('', 'seonte-ai-content'),
					'style' => ' ',
					'id' => '',
					'class' => ''
				),
				array(
					'type' => 'save',
					'title' => __('Save', 'seonte-ai-content'),
				), 
			)
		)
	 ; 
	global $sacas_settings;

	$sacas_settings = new sacasSettingsClassWizzard('seonte-ai-content'); 
	$sacas_settings->create_menu($config_big);
	
} );

?>