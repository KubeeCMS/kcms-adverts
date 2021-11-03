'use strict';
;( function( $, window, document, undefined ){



	/**
	 * drag & drop FILE UPLOADER
	*/
	$.fn._ning_file_uploader = function(options) {
	  
	  	// Defaults
		var settings = $.extend({
			'text': {},
			'banner_id':0,
			'user_id': 0,
			'result_grid': null,
			'max_upload_size': 15,
			'allowed_file_types': ['jpg','png','gif','svg'],
			'upload': {
				'folder': 'ADN_Uploads/',
				'dir': '',
				'src' : ''
			},
			'modernizr': 0,
			'template': 1
		}, options );

		settings.text = $.extend({
            'logo': '<svg viewBox="0 0 640 512" style="font-size:48px;height:50px;"><path fill="currentColor" d="M272 64c60.28 0 111.899 37.044 133.36 89.604C419.97 137.862 440.829 128 464 128c44.183 0 80 35.817 80 80 0 18.55-6.331 35.612-16.927 49.181C572.931 264.413 608 304.109 608 352c0 53.019-42.981 96-96 96H144c-61.856 0-112-50.144-112-112 0-56.77 42.24-103.669 97.004-110.998A145.47 145.47 0 0 1 128 208c0-79.529 64.471-144 144-144m0-32c-94.444 0-171.749 74.49-175.83 168.157C39.171 220.236 0 274.272 0 336c0 79.583 64.404 144 144 144h368c70.74 0 128-57.249 128-128 0-46.976-25.815-90.781-68.262-113.208C574.558 228.898 576 218.571 576 208c0-61.898-50.092-112-112-112-16.734 0-32.898 3.631-47.981 10.785C384.386 61.786 331.688 32 272 32zm48 340V221.255l68.201 68.2c4.686 4.686 12.284 4.686 16.97 0l5.657-5.657c4.687-4.686 4.687-12.284 0-16.971l-98.343-98.343c-4.686-4.686-12.284-4.686-16.971 0l-98.343 98.343c-4.686 4.686-4.686 12.285 0 16.971l5.657 5.657c4.686 4.686 12.284 4.686 16.97 0l68.201-68.2V372c0 6.627 5.373 12 12 12h8c6.628 0 12.001-5.373 12.001-12z"></path></svg>',
			'upload': '<strong>Click here</strong><span class="box__dragndrop"> or drag file to upload</span>.',
			'upload_info': 'Max. size 15 MB. SVG, JPG, PNG, GIF only.',
			'upload_button': 'Upload'
		}, settings.text );

		// Dont run if using Modernizr
		if(!settings.modernizr){
			(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);
		}

		
		var isAdvancedUpload = function(){
			var div = document.createElement( 'div' );
			return ( ( 'draggable' in div ) || ( 'ondragstart' in div && 'ondrop' in div ) ) && 'FormData' in window && 'FileReader' in window;
		}();

		return this.each(function(i,el){

			var _obj = $(el),
				$form = _obj,
				html  = '';
			
			if( !_obj.hasClass('_isLoaded') ){

				_obj.addClass('_isLoaded');

				// let the server side know we are going to make an Ajax request
				html+= '<input type="hidden" name="ajax" value="1" />';

				if( settings.template === 1){
					html+= '<div class="box__input">';
						html+= settings.text.logo;
						html+= '<div>';
							html+= '<input type="file" name="files[]" id="file" class="box__file" data-multiple-caption="{count} files selected" multiple />';
							html+= '<label for="file">';
								html+= settings.text.upload;
								html+= '<div class="upload_info">'+settings.text.upload_info+'</div>';
							html+= '</label>';
							html+= '<button type="submit" class="box__button">'+settings.text.upload_button+'</button>';
						html+= '</div>';
					html+= '</div>';
				}else{
					html+= '<div class="box__input" style="width: 120px;height: 100px;">';
						html+= '<input type="file" name="files[]" id="file" class="box__file" data-multiple-caption="{count} files selected" multiple />';
						html+= '<div class="thumbnail" data-size="custom" style="display: inline-block; position: absolute; left: 26px; top: 15px;">';
							html+= '<span class="custom_size_btn">';
								html+= '<svg aria-hidden="true" data-fa-processed="" data-prefix="fal" data-icon="cloud-upload" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="svg-inline--fa fa-cloud-upload fa-w-20" style="height:20px;vertical-align:middle;"><path fill="currentColor" d="M272 64c60.28 0 111.899 37.044 133.36 89.604C419.97 137.862 440.829 128 464 128c44.183 0 80 35.817 80 80 0 18.55-6.331 35.612-16.927 49.181C572.931 264.413 608 304.109 608 352c0 53.019-42.981 96-96 96H144c-61.856 0-112-50.144-112-112 0-56.77 42.24-103.669 97.004-110.998A145.47 145.47 0 0 1 128 208c0-79.529 64.471-144 144-144m0-32c-94.444 0-171.749 74.49-175.83 168.157C39.171 220.236 0 274.272 0 336c0 79.583 64.404 144 144 144h368c70.74 0 128-57.249 128-128 0-46.976-25.815-90.781-68.262-113.208C574.558 228.898 576 218.571 576 208c0-61.898-50.092-112-112-112-16.734 0-32.898 3.631-47.981 10.785C384.386 61.786 331.688 32 272 32zm48 340V221.255l68.201 68.2c4.686 4.686 12.284 4.686 16.97 0l5.657-5.657c4.687-4.686 4.687-12.284 0-16.971l-98.343-98.343c-4.686-4.686-12.284-4.686-16.971 0l-98.343 98.343c-4.686 4.686-4.686 12.285 0 16.971l5.657 5.657c4.686 4.686 12.284 4.686 16.97 0l68.201-68.2V372c0 6.627 5.373 12 12 12h8c6.628 0 12.001-5.373 12.001-12z" class=""></path></svg>';
								
								html+= '<button type="submit" class="box__button">'+settings.text.upload_button+'</button>';
							html+= '</span>';
						html+= '</div>';
					html+= '</div>';
				}

				html+= '<div class="box__uploading">Uploading&hellip;</div>';
				html+= '<div class="box__success">Done! <a href="" class="box__restart" role="button">Upload more?</a></div>';
				html+= '<div class="box__error">Error! <span></span> <a href="" class="box__restart" role="button">Try again!</a></div>';

				$form.append( html );
			}

			var $input		 = $form.find( 'input[type="file"]' ),
				$label		 = $form.find( 'label' ),
				$errorMsg	 = $form.find( '.box__error span' ),
				$restart	 = $form.find( '.box__restart' ),
				droppedFiles = false,
				showFiles	 = function( files ){
					$label.text( files.length > 1 ? ($input.attr( 'data-multiple-caption' ) || '' ).replace( '{count}', files.length ) : files[0].name);
				};

			// automatically submit the form on file select
			$input.on( 'change', function( e ){
				showFiles( e.target.files );
				console.log('file select');
                droppedFiles = e.target.files;
				$form.trigger( 'submit' );
			});


			// drag&drop files if the feature is available
			if( isAdvancedUpload ){
				$form
				.addClass( 'has-advanced-upload' ) // letting the CSS part to know drag&drop is supported by the browser
				.on( 'drag dragstart dragend dragover dragenter dragleave drop', function( e ){
					// preventing the unwanted behaviours
					e.preventDefault();
					e.stopPropagation();
				})
				.on( 'dragover dragenter', function(){
					$form.addClass( 'is-dragover' );
				})
				.on( 'dragleave dragend drop', function(){
					$form.removeClass( 'is-dragover' );
				})
				.on( 'drop', function( e ){
					droppedFiles = e.originalEvent.dataTransfer.files; // the files that were dropped
					showFiles( droppedFiles );

					$form.trigger( 'submit' ); // automatically submit the form on file drop
				});
			}


			// if the form was submitted
			$form.on( 'submit', function( e ){
				// preventing the duplicate submissions if the current one is in progress
                if( $form.hasClass( 'is-uploading' ) ) return false;

				$form.addClass( 'is-uploading' ).removeClass( 'is-error' );
				
				if( isAdvancedUpload ){ // ajax file upload for modern browsers
					e.preventDefault();
					console.log(droppedFiles);
					// gathering the form data
					//var ajaxData = new FormData($form.get(0));
					var ajaxData = new FormData();
                    
					if( droppedFiles ){
						//ajaxData.append( $input.attr( 'name' ), $input[0].files[0] );
						$.each( droppedFiles, function( i, file ){
							//console.log(file);
							ajaxData.append( $input.attr( 'name' ), file );
						});
					}

					ajaxData.append('action', '_ning_upload_image');
					ajaxData.append('uid', settings.user_id);
					ajaxData.append('bid', settings.banner_id);
					ajaxData.append('max_upload_size', settings.max_upload_size);
					ajaxData.append('allowed_file_types', settings.allowed_file_types);
					ajaxData.append('upload', JSON.stringify(settings.upload));
					//console.log(settings.upload['folder']);
					// ajax request
					$.ajax({
						url: 			$form.attr( 'action' ),
						type:			$form.attr( 'method' ),
						data: 			ajaxData,
						dataType:		'json',
						cache:			false,
						contentType:	false,
						processData:	false,
						complete: function(){
							$form.removeClass( 'is-uploading' );
						},
						success: function( data ){
							console.log(data);
							$form.addClass( data.success == true ? 'is-success' : 'is-error' );
                            if( !data.success ) $errorMsg.text( data.error );
                            
							var uploaded_files = JSON.parse(data.files);
							var upl_fls = {};
							
							// If result grid is defined add images to it.
							if( settings.result_grid ){
								$.each( uploaded_files, function( uf, upl_fls ){
									console.log(upl_fls);
									settings.result_grid.prepend(upl_fls.grid_item);
								});

								// Setup result grid (this needs to be done after every upload)
								settings.result_grid._dn_upload_result_grid( settings );
							
								grid_func.create_images_grid( settings.result_grid );
								settings.result_grid.masonry('reloadItems');
								
								// Fire callback if provided
								if (typeof(settings.callback) == 'function') {
									settings.callback.call(_obj, upl_fls);
								}
									
							}else{
								// Fire callback if provided
								if (typeof(settings.callback) == 'function') {
									settings.callback.call(_obj, data);
								}
							}

						},
						error: function(data){
							console.log(data);
							alert( 'Error. Please, contact the webmaster!!!' );
						}
					});
				}
				else // fallback Ajax solution upload for older browsers
				{
					var iframeName	= 'uploadiframe' + new Date().getTime(),
						$iframe		= $( '<iframe name="' + iframeName + '" style="display: none;"></iframe>' );

					$( 'body' ).append( $iframe );
					$form.attr( 'target', iframeName );

					$iframe.one( 'load', function()
					{
						var data = $.parseJSON( $iframe.contents().find( 'body' ).text() );
						$form.removeClass( 'is-uploading' ).addClass( data.success == true ? 'is-success' : 'is-error' ).removeAttr( 'target' );
						if( !data.success ) $errorMsg.text( data.error );
						$iframe.remove();
					});
				}
			});

			// restart the form if has a state of error/success
			$restart.on( 'click', function( e ){
				e.preventDefault();
				$form.removeClass( 'is-error is-success' );
				$input.trigger( 'click' );
			});

			// Firefox focus bug fix for file input
			$input
			.on( 'focus', function(){ $input.addClass( 'has-focus' ); })
			.on( 'blur', function(){ $input.removeClass( 'has-focus' ); });

		});
	};



})( jQuery, window, document );