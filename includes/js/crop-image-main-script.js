jQuery(document).ready(function($){
	
	$( document ).ajaxSend(function( event, request, settings ) {
		alert("her");
		var $image;
		
		var content	= '';
	
		var data  = settings.data;
		
		var check = data.search("action=query-attachments"); 
		
		if( check != -1 ){
			
			$(this).ajaxSuccess(function(){
				
				if( !$(document).find('.media-toolbar-primary #crop_image_btn').length ){
					
					$(document).find('.media-frame-toolbar .media-toolbar').append('<div class="media-toolbar-primary search-form"><button id="crop_image_btn" class="button media-button button-primary button-large" type="button">Crop</button></div>');
					
					$(document).find('#crop_image_btn').on('click', function(e){
						
						if( $(document).find('.attachments .attachment').hasClass('selected') ) {
							
							var image_url = $('.attachment-details label[data-setting="url"] input').val();
							
							if( image_url == undefined ){
								
								var thumbnail       = $('.attachment-info .thumbnail img').attr('src');
								var thumbnail_index = thumbnail.lastIndexOf("/") + 1;
								var thumbnail_file  = thumbnail.substr(thumbnail_index);
								
								var original_file   = $('.attachment-info .details .filename').html();
								
								image_url = thumbnail.replace(thumbnail_file, original_file);
							}
							
							$(document).find( "#crop_image_dialog" ).html('<div class="cropper"><img src="'+image_url+'" class="crop-dialog-image"></div>');
							
							// Get Image Data
							var image_title		     = $('.attachment-details label[data-setting="title"] input').val();
							var image_caption	     = $('.attachment-details label[data-setting="caption"] textarea').val();
							var image_alt            = $('.attachment-details label[data-setting="alt"] input').val();
							if( image_alt == '' ){
								image_alt = image_title;
							}
							
							var image_description    = $('.attachment-details label[data-setting="description"] textarea').val();
							var image_id		     =  $(document).find('.attachments .attachment.selected').attr('data-id');
							var image_align		     = 'align'+$('.attachment-display-settings .alignment').val();
							var image_link_to	     = $('.attachment-display-settings .link-to').val();
							var image_link_to_custom = $('.attachment-display-settings .link-to-custom').val();
							var image_size			 = 'size-'+$('.attachment-display-settings .size').val();

							$(document).find('.media-modal').parents('div').hide();
							
							$( "#crop_image_dialog" ).dialog({
								width:1200,
								height:600,
								resizable:false,
								buttons: [
									{
								      text: "Skip Cropping",
								      id: "skipCropping",
								      click: function() {
								      	
									      if( image_caption != '' )
									      {
									      	
									          if( image_link_to == 'none' ){
										      	content = '[caption id="attachment_'+image_id+'" align="'+image_align+'"]<img src="'+image_url+'" class="'+image_align+' '+image_size+' wp-image-'+image_id+'" alt="'+image_alt+'"> '+image_caption+'[/caption]';
										      }
										      else
										      {
										      	content = '[caption id="attachment_'+image_id+'" align="'+image_align+'"]<a href="'+image_link_to_custom+'"><img src="'+image_url+'" class="'+image_align+' '+image_size+' wp-image-'+image_id+'" alt="'+image_alt+'"></a> '+image_caption+'[/caption]';
										      }
									      }
									      else {
									      	
									          if( image_link_to == 'none' ){
										      	content = '<img src="'+image_url+'" class="'+image_align+' '+image_size+' wp-image-'+image_id+'" alt="'+image_alt+'">';
										      }
										      else
										      {
										      	content = '<a href="'+image_link_to_custom+'"><img src="'+image_url+'" class="'+image_align+' '+image_size+' wp-image-'+image_id+'" alt="'+image_alt+'"></a>';
										      }
									      }
								      	
										$('.wp-editor-area').val(content);
										$('body').removeClass('modal-open');
										$( this ).dialog( "close" );
										
										
										$('html,body').animate({
										scrollTop: $('#insert-media-button').offset().top-50},
										'slow');
								      }
								    },
								    {
								      text: "Crop Image",
								      id: "getCroppedCanvas",
								      click: function() {
								        $('#getCroppedCanvas').after('<img src="'+CropImage.processing+'/images/processing.gif" class="processing">');
								      }
								    }
								  ]
							});
							
							// Image Cropper
							$image = $(document).find('.cropper > img');
							$image.cropper({
								movable: false,
								zoomable: false,
								rotatable: false,
								scalable: false,
								autoCropArea:0.5
							});
						} else {
							alert('Please Select an Image.');
						}
					});
						
					// Crop
					$(document).on('click', '#getCroppedCanvas', function(){
						
						var data = $(this).data();
						
						var result = $image.cropper('getCroppedCanvas', data.option, data.secondOption);
						
						if (result) {
							
							var src   = $image.attr('src').split('/');
							var file  = src[src.length - 1];
							var image = result.toDataURL();
							
							var data = {
											action: 'crop_image_save_cropped_image',
											'image':image,
											'file':file
							};
							
							jQuery.post(CropImage.ajaxurl, data, function(response) {
								
								var data = JSON.parse(response);
								
								content = '<img src='+data.img+'>';
								
								$(document).find('.wp-editor-area').val(content);
								
								$('body').removeClass('modal-open');
								$(document).find('.processing').hide();
								$( "#crop_image_dialog" ).dialog('close');
								
								$('html,body').animate({
						        scrollTop: $('#insert-media-button').offset().top-50},
						        'slow');
							});
						}
					});
				}
			});
		}
	});
});