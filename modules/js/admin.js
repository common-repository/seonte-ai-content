jQuery(document).ready(function($){

	var time_interval = false;
	var processed_statuses;
	var is_first = true;

	$('body').on( 'submit', '#nacaswizzardmain', function( e ){
		e.preventDefault();
		if(is_first){
			is_first = false;
			processed_statuses = [0];
			$('button[type="submit"]').attr('disabled', true);
		}
		$('.current_status').html( 'Starting wizzard '+'<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>' );
		time_interval = setInterval(function(){
			process_badge_settings_call();
		}, 5000);
	})


	function process_badge_settings_call(){
		var data = {
			processed_statuses  : processed_statuses,
			formdata  : $( '#nacaswizzardmain' ).serialize(),
			security  : sacas_local_data.nonce,
			action : 'wizzard_processing'
		}
		jQuery.ajax({url: sacas_local_data.ajaxurl,
				type: 'POST',
				data: data,            
				beforeSend: function(msg){
						//jQuery('body').append('<div class="big_loader"></div>');
					},
					success: function(msg){
				 
						jQuery('.big_loader').replaceWith('');
						
						var obj = jQuery.parseJSON( msg );
						
						console.log( obj );
				 
						if( obj.result == 'success' ){
							//$('.preview_block').html('<img class="badge_preview" src="'+obj.url+'" />');
							$('.current_status').html( obj.status+' <div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>' );
							
							processed_statuses = obj.processed;
							if( obj.status == 'finished' ){
								$('button[type="submit"]').attr('disabled', false );
								clearInterval( time_interval );
								$('.current_status').html( 'Finished' );
								is_first = true;
							}
						}else{
						 
						}
						 
					} , 
					error:  function(msg) {
						console.log( msg );		
					}          
			});
	}

	$('body').on( 'submit', '#nacasmain', function( e ){
		
		// verify email

		
	 
	})
	$('body').on( 'click', '.generate_titles', function( e ){
		e.preventDefault();
		// verify email

		var data = {
			title  : $('input[name="title2use"]').val(),
			nr_titles: $('input[name="nr_titles"]').val(),
			security  : sacas_local_data.nonce,
			action : 'generate_titles'
		}
		jQuery.ajax({url: sacas_local_data.ajaxurl,
				type: 'POST',
				data: data,            
				beforeSend: function(msg){
						jQuery('body').append('<div class="big_loader"></div>');
					},
					success: function(msg){
			
						console.log( msg );
						
						jQuery('.big_loader').replaceWith('');
						
						var obj = jQuery.parseJSON( msg );
						
						console.log( obj );
						console.log( obj.success );
						if( obj.result == 'success' ){
							$('textarea[name="generated_title"]').val(obj.titles);
						} 
					} , 
					error:  function(msg) {
						console.log( msg );	
					}          
			});
	 
	});
});