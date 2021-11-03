/**
 * Responsive Banners
 * $(".example").ningResponsive({width:null});
 *
*/
;(function($, win) {
  $.fn.ningResponsive = function(options) {
	  
	  // Defaults
	  var settings = $.extend({
		  width: null,
		  height:null
	  }, options );
	  
     return this.each(function(i,el){
		 
		// Fire when banner enters viewport
		$(el).ningInViewport(function(px){ if(px) $(this).addClass("_ning_visible"); }, {padding:50});
		/*var animation = $(el).data('animation');
		if( typeof animation !== 'undefined'){
			$(el).ningInViewport(function(px){ if(px) $(this).addClass("_ning_visible").addClass(animation+" animated"); }, {padding:50});
		}else{
			$(el).ningInViewport(function(px){ if(px) $(this).addClass("_ning_visible"); }, {padding:50});
		}*/
		
		if($(el).hasClass('responsive')){
			
			var _ning_size = $(el).data('size').split('x'),
				maxWidth = settings.width ? settings.width : _ning_size[0],
				maxHeight = settings.height ? settings.height : _ning_size[1];
			//console.log(maxWidth);
			if(maxWidth == 'full'){
				$(el).css({'max-width': '100%', 'width':'100%'}); // , 'max-height': maxHeight+'px'
				$(el).find('._ning_inner').css({'max-width': '100%', 'width':'100%', 'height': maxHeight+'px'});
			}else{
				$(el).css({'max-width': maxWidth+'px', 'width':'100%'}); // , 'max-height': maxHeight+'px'
				// Set hight
				_ning_adjust_height();
				 
				// On Resize
				$(window).on('resize', _ning_adjust_height);
			}
			$(el).attr('data-size',maxWidth+'x'+maxHeight);
		}
		
		
		
		function _ning_adjust_height(){
			var width = $(el)[0].getBoundingClientRect().width,
				ratio = Number(width) / Number(maxWidth);
				newHeight = maxHeight * ratio;
			
			$(el).css({'height':newHeight+'px'});
			
			// Transform/resize banner content	
			if($(el).hasClass('scale')){
				var prop = get_proportion($(el));
				$(el).find('._ning_inner').css({'transform': 'translate(0, 0) scale('+prop.proportion+')', 'transform-origin':'0px 0px 0px'});
			}
			
			
			/*if($(el).hasClass('scale')){
				$(el).children().each(function() {
					// Make sure child has to be resized
					if( !$(this).hasClass('noresize')){
						var prop = get_proportion($(this));
						$(this).css({'transform': 'translate(0, 0) scale('+prop.proportion+')', 'transform-origin':'0px 0px 0px'});
					}
				});
			}*/
		}
		
		
		
		/**
		 * GET PROPORTION
		*/
		function get_proportion(itm){
			
			var width = $(el).attr('data-size').split('x')[0];
			var height = $(el).attr('data-size').split('x')[1];
			
			var proportion_width = $(el).outerWidth() / width;
			var proportion_height = $(el).outerHeight() / height
			
			//console.log(proportion_width +' '+proportion_height);
			var prop = Math.min(proportion_width, proportion_height);
			
			// Dont allow item to be bigger then original width.
			prop = prop > 1 ? 1 : prop;
			prop = prop > 0 ? prop : 1;
			
			return {
				proportion: prop
			};
		}
		
		/**
		 * CHECK IF VALUE IS NUMERIC
		*/
		/*function isNumber(n) {
		  	return !isNaN(parseFloat(n)) && isFinite(n);
		}*/
		function size_value(n, show_unit) {
			var num;
			var hasPx = n.indexOf('px') >= 0;
			var hasPct = n.indexOf('%') >= 0;
			
			console.log(show_unit);
			if(	hasPx && typeof show_unit !== "undefined" ){ 
				num = n.split('px');
				console.log(num[0]);
				return num[0];	
			}else{
				if( typeof show_unit != 'undefined' && !hasPct){
					return n+'px';
				}else{
					return n;
				}
			}
		}
		
	   
     });
  };
}(jQuery, window));











// Return the visible amount of px
// of any element currently in viewport.
// https://stackoverflow.com/a/27462500/3481803
// http://jsfiddle.net/RokoCB/tw6g2oeu/7/
/**
 * $(".example").ningInViewport(function(px){ if(px) $(this).addClass("class"); }, {padding:0});
 *
*/
;(function($, win) {
  $.fn.ningInViewport = function(cb, options) {
	  
	  // Defaults
	  var settings = $.extend({
		 padding: 0 // Add delay
	  }, options );
	  
     return this.each(function(i,el){
		
       function visPx(){
         var H = $(this).height(),
             r = el.getBoundingClientRect(), t=r.top+settings.padding, b=r.bottom+settings.padding;
         return cb.call(el, Math.max(0, t>0? H-t : (b<H?b:H)));  
	   } 
	   visPx();
       $(win).on("resize scroll", visPx);
     });
  };
}(jQuery, window));