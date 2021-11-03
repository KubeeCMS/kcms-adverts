/**
 * 
 *
*/
;(function($, win) {
    $.fn.bgTakeover = function(options) {
        
        // Defaults
        var settings = $.extend({
            bg_image: '',
            bg_color: '',
            content_bg_color: '',
            bg_pos: 'fixed', // fixed | absolute
            nofollow: 0,
            click_url: '', // string | object
            container: '.container',
            top_skin: '', // 100px - height of the top skin bar
        }, options );
        
       return this.each(function(i,el){
        
            var _obj = $(el),
                h = '',
                bg_img_h = '',
                bg_img_w = '';
            
            _obj.css({'background': settings.bg_color});

            h+= '<div id="skin_container" class="skin_container">';
                h+= '<div class="skin_inner">';
                    h+= settings.bg_image !== '' ? '<div class="skin_bg" style="background:url('+settings.bg_image+') no-repeat center top;background-size:cover;"></div>' : ''; // no-repeat fixed center
                    h+= '<div class="skin_bg_top"></div>';
                    h+= '<div class="skin_bg_left"></div>';
                    h+= '<div class="skin_bg_right"></div>';
                h+= '</div>';
            h+= '</div>';

            _obj.prepend(h);
            _obj.find('.skin_container').css({position: settings.bg_pos});
            $(settings.container).css({position:'relative'});
            if( settings.content_bg_color !== ''){
                _obj.find('.skin_container').closest(':has('+settings.container+')').find(settings.container).css({background:settings.content_bg_color});
                //$(settings.container).css({background:settings.content_bg_color});
            }
            
            if( jQuery.isPlainObject(settings.click_url) ){
                var click_url = $.extend({
                    top: '',
                    left: '',
                    right: ''
                }, settings.click_url );
            }else{
                var click_url = {
                    top: settings.click_url,
                    left: settings.click_url,
                    right: settings.click_url
                };
            }

            if( settings.nofollow ){
                _obj.find('.skin_bg_top').attr('rel','nofollow');
                _obj.find('.skin_bg_left').attr('rel','nofollow');
                _obj.find('.skin_bg_right').attr('rel','nofollow');
            }
            
            if( click_url.top === ''){
                _obj.find('.skin_bg_top').css({cursor:'default'});
            }
            if( click_url.left === ''){
                _obj.find('.skin_bg_left').css({cursor:'default'});
            }
            if( click_url.right === ''){
                _obj.find('.skin_bg_right').css({cursor:'default'});
            }
            _obj.find('.skin_bg_top').on('click', function(){ click_url.top !== '' ? window.open(click_url.top) : ''; });
            _obj.find('.skin_bg_left').on('click', function(){ click_url.left !== '' ? window.open(click_url.left) : ''; });
            _obj.find('.skin_bg_right').on('click', function(){ click_url.right !== '' ? window.open(click_url.right) : ''; });
            
            function resize_bg() {
                console.log('Resize Background');
                var mainbodyWidth = $(settings.container).outerWidth(); // the width of the main body container
                
                var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                
                if( settings.top_skin !== ''){  
                    if (_obj.css('overflow') !== 'hidden'){
                        _obj.css({'overflow': 'auto'});
                    }     
                    $(settings.container).css({'margin-top': settings.top_skin});         
                    $('.skin_bg_top').css({width:mainbodyWidth,height: settings.top_skin});
                }

                if( settings.bg_pos === 'absolute'){
                    var outerHeight = _obj.is( "body" ) ? $(document).outerHeight() : _obj.outerHeight();
                    console.log(_obj.height());
                    console.log(outerHeight);

                    if( settings.bg_image !== ''){
                        // Get image size
                        var img = new Image();
                        img.onload = function(){
                            bg_img_h = this.height;
                            bg_img_w = this.width;

                            // Make sure .skin_container is not higher then the actual image.
                            if( outerHeight < bg_img_h){
                                _obj.find('.skin_bg').css({height:outerHeight});
                            }else{
                                _obj.find('.skin_bg').css({height:bg_img_h});
                            }
                            _obj.find('.skin_container').css({height:outerHeight});
                        }
                        img.src = settings.bg_image;
                    }else{
                        _obj.find('.skin_container').css({height:outerHeight});
                    }
                }

                _obj.find('.skin_bg_left').css({width:(width-mainbodyWidth)/2+"px"});
                _obj.find('.skin_bg_right').css({width:(width-mainbodyWidth)/2+"px"});
            }
            
            $(document).ready(function(){
                resize_bg();
            });
            window.onresize = resize_bg;
        });
    };
}(jQuery, window));