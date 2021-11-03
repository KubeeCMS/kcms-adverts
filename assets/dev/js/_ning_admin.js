/**
 *  GLOBAL FUNCTIONS
 */
var Adning_global = {
    // Adning_global.activate_tooltips();
	activate_tooltips: function(_obj){
		_obj.find(".ttip").tooltipster({
			theme: "tooltipster-light",
			functionInit: function(instance, helper){
				var $origin = jQuery(helper.origin),
					dataOptions = $origin.attr('data-tooltipster');
		
				if(dataOptions){
					dataOptions = JSON.parse(dataOptions);
		
					jQuery.each(dataOptions, function(name, option){
						instance.option(name, option);
					});
				}
			}
		});
	},



	/**
	 * CHECK IF EMAIL IS VALID
	 * Adning_global.is_valid_email();
	 */
	is_valid_email: function(email){
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);	
	},



	// Adning_global.adsense_tpl();
	adsense_tpl: function(options){
		var args = jQuery.extend({
			'pub_id': '',
			'slot_id': '',
			'type': 'normal',
			'layout_key': '',
			'layout': '',
			'width': 300,
			'height': 250,
			'google_src': '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'
		}, options );

		var code = '';

		switch ( args.type ) {
			case 'in-feed':
				code = '<script async src="'+args.google_src+'"></script>' +
						'<ins class="adsbygoogle" ' +
							 'style="display:block;" ' +
							 'data-ad-client="ca-' + args.pub_id + '" ' +
							 'data-ad-slot="' + args.slot_id + '" ' +
							 'data-ad-layout-key="' + args.layout_key + '" ';
				if ( args.layout !== '' ) {
					code += 'data-ad-layout="' + args.layout + '" ';
				}
				code += 'data-ad-format="fluid"></ins>' +
						'<script>' +
						'(adsbygoogle = window.adsbygoogle || []).push({});' +
						'</script>';
				break;
			case 'in-article':
				code = '<script async src="'+args.google_src+'"></script>' +
						'<ins class="adsbygoogle" ' +
							 'style="display:block;text-align:center;" ' +
							 'data-ad-client="ca-' + args.pub_id + '" ' +
							 'data-ad-slot="' + args.slot_id + '" ' +
							 'data-ad-layout="in-article" ' +
							 'data-ad-format="fluid"></ins>' +
						'<script>' +
						'(adsbygoogle = window.adsbygoogle || []).push({});' +
						'</script>';
				break;
			case 'matched-content':
				code = '<script async src="'+args.google_src+'"></script>' +
						'<ins class="adsbygoogle" ' +
							 'style="display:block;" ' +
							 'data-ad-client="ca-' + args.pub_id + '" ' +
							 'data-ad-slot="' + args.slot_id + '" ' +
							 'data-ad-format="autorelaxed"></ins>' +
						'<script>' +
						'(adsbygoogle = window.adsbygoogle || []).push({});' +
						'</script>';
				break;
			case 'link-responsive':
				code = '<script async src="'+args.google_src+'"></script>' +
						'<ins class="adsbygoogle" ' +
							 'style="display:block;" ' +
							 'data-ad-client="ca-' + args.pub_id + '" ' +
							 'data-ad-slot="' + args.slot_id + '" ' +
							 'data-ad-format="link"></ins>' +
						'<script>' +
						'(adsbygoogle = window.adsbygoogle || []).push({});' +
						'</script>';
				break;
			case 'link':
				code = '<script async src="'+args.google_src+'"></script>' +
						'<ins class="adsbygoogle" ' +
							 'style="display:block;width:' + args.width + 'px;height:' + args.height + 'px" ' +
							 'data-ad-client="ca-' + args.pub_id + '" ' +
							 'data-ad-slot="' + args.slot_id + '" ' +
							 'data-ad-format="link"></ins>' +
						'<script>' +
						'(adsbygoogle = window.adsbygoogle || []).push({});' +
						'</script>';
				break;
			case 'responsive':
				code = '<script async src="'+args.google_src+'"></script>' +
						'<ins class="adsbygoogle" ' +
							 'style="display:block;" ' +
							 'data-ad-client="ca-' + args.pub_id + '" ' +
							 'data-ad-slot="' + args.slot_id + '" ' +
							 'data-ad-format="auto"></ins>' +
						'<script>' +
						'(adsbygoogle = window.adsbygoogle || []).push({});' +
						'</script>';
				break;
			case 'normal':
				code = '<script async src="'+args.google_src+'"></script>' +
						'<ins class="adsbygoogle" ' +
							 'style="display:inline-block;width:' + args.width + 'px;height:' + args.height + 'px" ' +
							 'data-ad-client="ca-' + args.pub_id + '" ' +
							 'data-ad-slot="' + args.slot_id + '"></ins>' +
						'<script>' +
						'(adsbygoogle = window.adsbygoogle || []).push({});' +
						'</script>';
				break;
			default:
		}

		return code;
	},

 
	/**
	 * File upload content return based on file type
	 * Adning_global.fileContent(file);
	 * file = object( 'url': '', 'type':'video/mp4' )
	 */
	fileContent: function(file) {
		var url = file.url;
		var type = typeof file.type !== 'undefined' ? file.type : '';
		var extension = url.substr( (url.lastIndexOf('.') +1) );
		switch(extension) {
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
			case 'svg':
				//console.log('was jpg jpeg png gif svg'); 
				return '<div class="_ning_elmt"><img src="'+url+'" /></div>';
			break;                        
			case 'zip':
			case 'rar':
				//console.log('was zip rar');
			break;
			case 'pdf':
				//console.log('was pdf');
				return url;
			break;
			case 'mp4':
				//console.log('mp4');
				type = type !== '' ? type : 'video/'+extension;
				return '<video controls><source src="'+url+'" type="'+type+'">Your browser does not support HTML5 video.</video>';
			break;
			default:
				console.log('who knows which filetype this is...');
		}
	},


	load_code_editor: function(){

		jQuery('.code_editor').each(function(index, item){

			if( jQuery(item).parent().find('.CodeMirror').length || jQuery(item).parents('.closed').length )
				return;


			var height = typeof jQuery(item).data("height") !== 'undefined' ? jQuery(item).data("height") : '150px';
			
			var editor = _ning_codemirror.fromTextArea(jQuery(item)[0], {
				lineNumbers : true,
				mode: jQuery(item).data("lang"),
				theme: "github",
			});
			editor.setSize("100%",height);
	
			if( jQuery(item).attr('id') === 'banner_content' ){
				editor.on("change", function() {
					//console.log('Codemirror change');
					var render_preview = jQuery('#dont_render_preview_code').prop("checked");
					if( render_preview ){
						jQuery("._ning_cont").find('._ning_inner').html( editor.getValue() );
					}
				});
			}
	  	});

		/*var code_editors = document.querySelectorAll(".code_editor");
	
		for (var i = 0; i < code_editors.length; i++){
			console.log(this);
			
			var height = code_editors[i].getAttribute("data-height") !== null ? code_editors[i].getAttribute("data-height") : '150px';
			
			var editor = _ning_codemirror.fromTextArea(code_editors[i], {
				lineNumbers : true,
				mode: code_editors[i].getAttribute("data-lang"),
				theme: "github",
			});
			editor.setSize("100%",height);
	
			if( code_editors[i].getAttribute('id') === 'banner_content' ){
				editor.on("change", function() {
					//console.log('Codemirror change');
					var render_preview = $('#dont_render_preview_code').prop("checked");
					if( render_preview ){
						$("._ning_cont").find('._ning_inner').html( editor.getValue() );
					}
				});
			}
		}*/
	}
};


module.exports = {
	Adning_global: Adning_global
};



(function($, win) {
	
	$(document).ready(function(){

		Adning_global.activate_tooltips($('.adning_dashboard'));

		// POSITIONING
		$('#ADNI_align').on('change', function(){
			var pos = $(this).val();
			$('.banner_holder').find('._ning_outer').removeClass('_align_left _align_right _align_center');
			$('.banner_holder').find('._ning_outer').addClass('_align_'+pos);

			if( $('.banner_holder').find('._ning_grid').length ){
				if( pos === 'left'){
					$('.banner_holder').find('.mjs_row').removeClass('justify-content-center justify-content-end');
					$('.banner_holder').find('.mjs_row').addClass('justify-content-start');
				}
				if( pos === 'center'){
					$('.banner_holder').find('.mjs_row').removeClass('justify-content-start justify-content-end');
					$('.banner_holder').find('.mjs_row').addClass('justify-content-center');
				}
				if( pos === 'right'){
					$('.banner_holder').find('.mjs_row').removeClass('justify-content-center justify-content-start');
					$('.banner_holder').find('.mjs_row').addClass('justify-content-end');
				}
			}
		});

		// BORDER/LABEL OPTIONS
		$('#ADNI_label').on('change', function(){
			var label = $(this).val();
			$("._ning_outer").removeClass('has_label');
			$('.banner_holder').find('._ning_label').html('');
			console.log( label );
			if( label !== ''){
				$("._ning_outer").addClass('has_label');
				$('.banner_holder').find('._ning_label').html(label);
			}
		});
		$('#ADNI_label_pos').on('change', function(){
			var pos = $(this).val();
			$('.banner_holder').find('._ning_label').removeClass('_left _right _center');
			$('.banner_holder').find('._ning_label').addClass('_'+pos);
		});
		$('#ADNI_has_border').on('change', function(){
			$("._ning_outer").toggleClass('has_border');
		});

		// POSITIONING OPTIONS
		if( $('.spot_box.selected').data('custom') === 1){
			$('.custom_placement_settings_cont').show();
			$('.custom_placement_settings_cont').find('.option_'+$('.spot_box.selected').data('pos')).show();
		}
		

		// Reset stats
		$('#reset_stats').on('click', function(){
			if (window.confirm($(this).data('msg'))){
				var url = $(this).data('href');
				window.location.replace(url);
			}
		});
		

		// Remove sell order
		$('._ning_remove_sell_order').on('click', function(){
			if (window.confirm($(this).data('msg'))){
				var url = $(this).data('href');
				window.location.replace(url);
			}
		});

		
		$('.spot_box').on('click', function(){
			var pos = $(this).data('pos'),
				has_custom = $(this).data('custom');

			$('.spot_box').removeClass('selected');
			if( pos !== ''){
				$(this).addClass('selected');
			}

			if( has_custom ){
				$('.custom_placement_settings_cont').show();
				$('.custom_placement_settings_cont').find('.custom_box').hide();
				$('.custom_placement_settings_cont').find('.option_'+pos).show();
			}else{
				$('.custom_placement_settings_cont').hide();
				$('.custom_placement_settings_cont').find('.custom_box').hide();
			}
			console.log(pos);
			$('.adning_auto_position').val(pos);
		});


		$('.pop_box').on('click', function(){
			var pos = $(this).data('pos'),
				has_custom = $(this).data('custom');

			$('.pop_box').removeClass('selected');
			if( pos !== ''){
				$(this).addClass('selected');
			}

			/*if( has_custom ){
				$('.custom_placement_settings_cont').show();
				$('.custom_placement_settings_cont').find('.custom_box').hide();
				$('.custom_placement_settings_cont').find('.option_'+pos).show();
			}else{
				$('.custom_placement_settings_cont').hide();
				$('.custom_placement_settings_cont').find('.custom_box').hide();
			}*/
			console.log(pos);
			$('.popup_display_type').val(pos);
		});


		/*
		* Media Popup - works for admins only
		*/
		$('.upload_image_button').on('click', function()
		{
			var media_uploader = null;
			
			media_uploader = wp.media({
				frame:    "post", 
				state:    "insert", 
				multiple: false
			});
		
			media_uploader.on("insert", function(){
				var json = media_uploader.state().get("selection").first().toJSON();
				//console.log(json);
				/*var image_url = json.url;
				var image_caption = json.caption;
				var image_title = json.title;*/
				var content = Adning_global.fileContent({'url':json.url,'type':json.mime});
				$('#banner_content').val(content).trigger("change");
				
				// $('#banner_content').val('<div class="_ning_elmt"><img src="'+json.url+'" /></div>').trigger("change");
			});
		
			media_uploader.open();
		});



		$('.ttip').tooltipster({
			theme: 'tooltipster-light',
			multiple:true,
			maxWidth: 200,
			speed:50,
			delay:0,
			contentAsHTML: true,
			interactive: true
		});	
		

		

		var config = {
			'.chosen-select'           : {},
			'.chosen-select-deselect'  : { allow_single_deselect: true },
			'.chosen-select-no-single' : { disable_search_threshold: 10 },
			'.chosen-select-no-results': { no_results_text: 'Oops, nothing found!' },
			'.chosen-select-rtl'       : { rtl: true }
			//'.chosen-select-width'     : { width: '100%' }
		}
		for (var selector in config) {
			$(selector).chosen(config[selector]).chosenSortable();
		}

		$('.chosen-search-input').autocomplete({
			source: function( request, response ) {
				
				var input_obj = this.element;
				input_obj.css({'width': '100%'});
				var select_obj = input_obj.closest('.ning_chosen_select').find('.chosen-select');
				var type = '';
				var key = '';

				// Start search when string contains at least 3 characters
				if( request.term.length > 2 ){
					console.log('search: '+request.term);
					
					if( select_obj.hasClass('ning_chosen_posttype_select') ){ 
						type = select_obj.data('type');
						key = 'post_type';

					}else if( select_obj.hasClass('ning_chosen_taxonomy_select') ){
						type = select_obj.data('type');
						key = 'taxonomy';

					}else if( select_obj.hasClass('ning_chosen_author_select') ){
						type = select_obj.data('type');
						key = 'author';
					}
					
					if( type !== ''){
						$.ajax({
							type: "POST",
							url: _adn_.ajaxurl,
							dataType: "json",
							data: "action=display_filter_load_posts&key="+key+"&search="+request.term+"&type="+type
						}).done(function( obj ) {
							
							if( !$.isEmptyObject(obj) )
							{
								var inpt = input_obj.val();
								
								$.map( obj, function( item ) {
									if( !select_obj.find('.opt_'+item.id).length ){
										select_obj.append('<option class="opt_'+item.id+'" value="'+item.id+'">' + item.name + ' - (#'+item.id+')</option>');
										select_obj.trigger("chosen:updated");
										input_obj.val(inpt);
									}
								});
							}
						});
					}
					// end posttype search
				}
			}
		});



		// EXPORT OPTIONS iframe - embedcode
		$('.export_switcher').on('change', function(){
			console.log($(this).prop("checked"));
			console.log($(this).attr('id'));
			console.log($(this).data('opos'));
			var opos = $(this).prop("checked") ? false : true;


			$('#'+$(this).data('opos')).prop( "checked", opos );
			$('.export_switch_box').toggleClass('visible');
		});
		// Select all text
		$(".export_embed_code").on("focus keyup", function(e){
			var keycode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
			if(keycode === 9 || !keycode){
				var $this = $(this);
				$this.select();
	
				// Chrome Fix
				$this.on("mouseup", function() {
					// Unbindeamos el mouseup
					$this.off("mouseup");
					return false;
				});
			}
		});



		$('.tog').on('click', function(){
			$(this).closest('.option_box').toggleClass('closed').find('.settings_box_content').toggleClass('hidden');
			window.Adning_global.load_code_editor();
		});
		$('.togg').on('click', function(){
			$(this).closest('.adn_settings_cont').toggleClass('closed').find('.set_box_content').toggleClass('hidden');
			window.Adning_global.load_code_editor();
		});



		$('#parallax_activate_btn').on('change', function(){
			$('.parallax_settings_container').toggleClass('hidden');
		});
		$('.ParallaxUploader')._ning_file_upload({
			'banner_id': $(this).data('id'),
			'max_upload_size': 1000,
			'wpnonce': $(this).data('wpnonce'),
			'upload': {
				'folder': 'items/'+$(this).data('id')+'/',
				'dir': _adn_.upload.dir,
				'src': _adn_.upload.src
			},
			'allowed_file_types': ['jpg','png','gif','svg','mp4'],
			'callback': function(obj){
				var src = '';

				var uploaded_file = JSON.parse(obj.files);
				src = uploaded_file[0].src;

				$(this).closest('.parallax_settings').find('.parallax_bg_src').val(src);
				console.log(src);
			}
		});
		/*$('.ParallaxUploader')._ning_file_uploader({
			'banner_id': $(this).data('id'),
			'max_upload_size': 1000,
			'upload': {
				'folder': 'banners/'+$(this).data('id')+'/',
				'dir': _adn_.upload.dir,
				'src': _adn_.upload.src
			},
			'allowed_file_types': ['jpg','png','gif','svg','mp4'],
			'text': {
				'logo': '<svg viewBox=\"0 0 640 512\" style=\"width:30px;\"><path fill=\"currentColor\" d=\"M272 64c60.28 0 111.899 37.044 133.36 89.604C419.97 137.862 440.829 128 464 128c44.183 0 80 35.817 80 80 0 18.55-6.331 35.612-16.927 49.181C572.931 264.413 608 304.109 608 352c0 53.019-42.981 96-96 96H144c-61.856 0-112-50.144-112-112 0-56.77 42.24-103.669 97.004-110.998A145.47 145.47 0 0 1 128 208c0-79.529 64.471-144 144-144m0-32c-94.444 0-171.749 74.49-175.83 168.157C39.171 220.236 0 274.272 0 336c0 79.583 64.404 144 144 144h368c70.74 0 128-57.249 128-128 0-46.976-25.815-90.781-68.262-113.208C574.558 228.898 576 218.571 576 208c0-61.898-50.092-112-112-112-16.734 0-32.898 3.631-47.981 10.785C384.386 61.786 331.688 32 272 32zm48 340V221.255l68.201 68.2c4.686 4.686 12.284 4.686 16.97 0l5.657-5.657c4.687-4.686 4.687-12.284 0-16.971l-98.343-98.343c-4.686-4.686-12.284-4.686-16.971 0l-98.343 98.343c-4.686 4.686-4.686 12.285 0 16.971l5.657 5.657c4.686 4.686 12.284 4.686 16.97 0l68.201-68.2V372c0 6.627 5.373 12 12 12h8c6.628 0 12.001-5.373 12.001-12z\"></path></svg>',
				'upload': '<strong>Click here</strong><span class=\"box__dragndrop\"> or drag file to upload</span>.',
				'upload_info': 'Max. size 1000 MB. Allowed files: <strong><em>JPG, PNG, GIF, SVG, MP4</em></strong>',
			},
			'callback': function(obj){
				var src = '';

				var uploaded_file = JSON.parse(obj.files);
				src = uploaded_file[0].src;

				$(this).closest('.parallax_settings').find('.parallax_bg_src').val(src);
				console.log(src);
			}
		});*/
	
	});





	$.fn._ning_file_upload = function(options) {

		var settings = $.extend({
			'text': {},
			'banner_id':0,
			'user_id': 0,
			'result_grid': null,
			'max_upload_size': 1000,
			'wpnonce' : null,
			'allowed_file_types': ['jpg','png','gif','svg'],
			'upload': {
				'folder': 'ADN_Uploads/',
				'dir': '',
				'src' : ''
			},
			'modernizr': 0,
			'template': 1
		}, options );

		return this.each(function(i,el){

			var _obj = $(el),
				$form = _obj,
				html  = '',
				afs = '';
			
			// Create accepted files string
			$.each( settings.allowed_file_types, function( key, value ) {
				var cma = !key ? '' : ',';
				afs+= cma+'.'+value;
			});

			var afstring = afs.replace(/[.]/g, ' ');
			console.log(afstring);
			_obj.find('.allowed_extentions').html(afstring);
			_obj.find('.max_filesize').html(settings.max_upload_size);
			

			// https://www.dropzonejs.com/
			//Dropzone.autoDiscover = false;
			$form.dropzone({ 
				url: "/", 
				//acceptedFiles: "image/*,.mp4",
				acceptedFiles: afs,
				complete: function(file) {
					_obj.addClass('dropzone');
					$form.find('.dz-message').hide();

					var ajaxData = new FormData();
					ajaxData.append( 'files[]', file );
					ajaxData.append('action', '_ning_upload_image');
					ajaxData.append('uid', settings.user_id);
					ajaxData.append('bid', settings.banner_id);
					ajaxData.append('max_upload_size', settings.max_upload_size);
					ajaxData.append('allowed_file_types', settings.allowed_file_types);
					ajaxData.append('wpnonce', settings.wpnonce);
					ajaxData.append('upload', JSON.stringify(settings.upload));

					console.log(file);
					$.ajax({
						url: 			_adn_.ajaxurl,
						type:			'POST',
						data: 			ajaxData,
						dataType:		'json',
						cache:			false,
						contentType:	false,
						processData:	false,
						complete: function(){
							//$form.removeClass( 'is-uploading' );
							$form.find('.dz-preview').addClass('dz-complete');
						},
						success: function( data ){
							console.log(data);
							// Fire callback if provided
							if (typeof(settings.callback) == 'function') {
								settings.callback.call(_obj, data);

								setTimeout(function(){
									$form.find('.dz-message').show();
									$form.find('.dz-preview').remove();
								}, 1000);
							}
						}
					});
					
				}
			});
			// end dropzonejs

		});
	};
	

}(jQuery, window));