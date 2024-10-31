<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('sacasElementsClassWizzard') ){
#[AllowDynamicProperties]	
	class sacasElementsClassWizzard{
		
		var  $type;
		var  $settings;
		var  $content;
	 
		function __construct( $type, $parameters, $value ){
	 
			$this->type = $type;
			$this->parameters = $parameters;

			// array empty patch
			$default_array = [
				'class' => '',
				'disabled' => '',
				'readonly' => '',
				'id' => '',
				'value' => '',
				'default' => '',
				'width' => '',
				'title' => '',
				'sub_title' => '',
				'sub_text' => '',
				'rows' => '',
				'name' => '',
				'href' => '',
				'style' => '',
				'upload_text' => '',
				'container_class' => '',
				'placeholder' => '',
			];	
			$this->parameters = array_merge( $default_array, $this->parameters );
			$this->value = $value;
			$this->generate_result_block();
 
		}
		function generate_result_block(){
			global $post;

			$label_filtering = array(
				'a' => array(
					'href' => array(),
					'title' => array()
				),
				'b' => array(),
			 
			);

			$out = '';
			switch( $this->type ){
				
		
		
				case "text":
						$out .= '
					<div class="'.sprintf(esc_attr__('%s','seonte-ai-content'), esc_attr($this->parameters['container_class'])).' '.( $this->parameters['width'] ? sprintf(esc_attr__('%s', 'seonte-ai-content'), esc_attr($this->parameters['width'])) : 'col-12' ).'">
						<div class="form-group">  
							<label class="control-label" for="'.sprintf(esc_attr__('%s', 'seonte-ai-content'), esc_attr($this->parameters['id'])).'">'.wp_kses( $this->parameters['title'], $label_filtering ).'</label>  
							
							  <input type="text" '.($this->parameters['readonly'] ? ' readonly ' : '' ).' '.($this->parameters['disabled'] ? ' disabled ' : '' ).' class="form-control '. sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['class'])).'"  name="'.sprintf(esc_attr__('%s', 'seonte-ai-content' ), esc_attr($this->parameters['name'])).'" id="'.sprintf(esc_attr__('%s', 'seonte-ai-content' ), esc_attr($this->parameters['id'])).'" placeholder="'.sprintf(esc_attr__('%s', 'seonte-ai-content'), esc_attr($this->parameters['placeholder'])).'" value="'.( $this->value && $this->value != '' ? sprintf(esc_html__('%s', 'seonte-ai-content' ), esc_html(stripslashes( $this->value ))) : $this->parameters['default'] ).'">  
							  <p class="help-block">'.sprintf(esc_html__('%s' , 'seonte-ai-content' ), esc_html($this->parameters['sub_text'])).'</p>  
							
						  </div> 
					</div>
						';
				break;
		
				case "button":
						$out .= '
					<div class="'.sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['container_class'])).' '.( $this->parameters['width'] ? sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['width'])) : 'col-12' ).'">
						<div class="form-group">  
							<!--<label class="control-label" for="">&nbsp;</label>-->
							
							  <a class="'.( $this->parameters['class'] ? sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['class'])) : 'btn btn-success' ).'" href="'.sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['href'])).'"   >'.sprintf(esc_html__( '%s', 'seonte-ai-content' ), esc_html($this->parameters['title'])).'</a>  
							  
							
						</div> 
					</div>
						';
				break;
				case "textarea":
			 
					$out .= '
					<div class="'.sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['container_class'])).' '.( $this->parameters['width'] ? sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['width'])) : 'col-12' ).'">
						<div class="form-group">  
							<label class="control-label" for="'.sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['id'])).'">'.$this->parameters['title'].'</label>  
						
							<textarea style="'.sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['style'])).'" class="form-control '.sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['class'])).'" name="'.sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['name'])).'" id="'.sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['id'])).'" rows="'.sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['rows'])).'">'.( $this->value && $this->value != '' ?  sprintf(esc_html__( '%s', 'seonte-ai-content' ), esc_html(stripslashes( $this->value ))) : $this->parameters['default'] ).'</textarea>  
							<p class="help-block">'.sprintf(esc_html__( '%s', 'seonte-ai-content' ), esc_html($this->parameters['sub_text'])).'</p> 
						
						</div> 
					</div>
						';
				break;
				
					
					case "save":
				 
					$out .= '
					<div class="'.sprintf(esc_attr__( '%s', 'seonte-ai-content' ), esc_attr($this->parameters['container_class'])).' '.( $this->parameters['width'] ? sprintf(esc_attr__( '%s','seonte-ai-content' ), esc_attr($this->parameters['width'])) : 'col-12' ).'">
						<div class="form-actions">  
							<button type="submit" class="btn btn-primary">'.sprintf(esc_html__( '%s', 'seonte-ai-content' ), esc_html($this->parameters['title'])).'</button>  
						</div> 
					</div>
					';	
					break;
					
			}
			$this->content = $out;
		 
		}
		public function  get_code(){
			return $this->content;
		}
	}
}
 
?>