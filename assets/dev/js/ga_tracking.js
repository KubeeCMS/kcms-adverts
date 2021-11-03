;(function($){
    "use strict";
    
    var host = 'https://www.google-analytics.com',
	    batch_path = '/batch',
        collect_path = '/collect';
        
    var gaTracker = {

        setup: function( name, ga_ID ){
            var args = {
                'name': name,
                'cid': false,
                'gaID': ga_ID,
                'analyticsObj': null
            };

           
            // Check if analytics.js has already been loaded and the GoogleAnalyticsObject has been created
            args.analyticsObj = ( typeof( GoogleAnalyticsObject ) == 'string' && typeof( window[GoogleAnalyticsObject] ) == 'function' ) ? window[GoogleAnalyticsObject] : false;

            if( args.analyticsObj === false ){
                console.log('No GoogleAnalyticsObject found.');
                // analytics.js has not yet been loaded. Inlude it now.
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','https://www.google-analytics.com/analytics.js','_ning_ga');

                _ning_ga( 'create', args.gaID, 'auto', args.name );
                /*if( gatracking_anonym ) {
                    _ning_ga( 'set', 'anonymizeIp', true );
                }*/
                _ning_ga(function(){
                    var tracker = _ning_ga.getByName( args.name );
                    args = gaTracker.loadGA( tracker, args );
                });

                args.analyticsObj = _ning_ga;
            }else{
                console.log('GoogleAnalyticsObject found!');
                // The GoogleAnalyticsObject has already been created, so we use that one to avoid any conflicts.
                if ( GoogleAnalyticsObject !== '_ning_ga' ){
                    console.log( "Adning GA Analytics - using variable called '" + GoogleAnalyticsObject + "'" );
                }
                
                window[GoogleAnalyticsObject]( 'create', args.gaID, 'auto', args.name );
                
                /*if( gatracking_anonym ) {
                    window[GoogleAnalyticsObject]( 'set', 'anonymizeIp', true );
                }*/
                
                window[GoogleAnalyticsObject](function(){
                    var tracker = window[GoogleAnalyticsObject].getByName( args.name );
                    args = gaTracker.loadGA( tracker, args );
                });
            }

            return args;
        },

        loadGA: function( tracker, args ){
            console.log('LOAD GA');
            //console.log( tracker );
            args.cid = tracker.get('clientId');
            //console.log(args.cid);

            return gaTracker.track_impressions(args);
        },


        track_impressions: function(args){
            if( !loaded_ang.length ){
            //if( !Object.keys(loaded_ang).length ){
                console.log( ' Adning GA Analytics - loaded_ang is empty.' );
				return args;
            }
            if( ! args.cid ){
				console.log( ' Adning GA Analytics - no client ID provided.' );
				return args;
			}

            var track_banner_data = gaTracker.ga_track_data({'ea': '[banner] Impressions'}, args);
            var track_adzone_data = gaTracker.ga_track_data({'ea': '[adzone] Impressions'}, args);
            
            var gaload = '';
            
            // Banners
            for( var b in loaded_ang ){
                if( $.isPlainObject(loaded_ang[b]) ){
                    $.each( loaded_ang[b], function( key, val ) {
                        var ad = {
                            el: '[' + key + '] '+ val.name,
                        };
                        var ad_param = $.extend( {}, track_banner_data, ad );
                        gaload += $.param( ad_param ) + "\n";
                    });
                }
            }

            // Adzones
            if( Object.keys(loaded_angzones).length ){
                $.each( loaded_angzones, function( key, val ){
                    //console.log('[adzone] '+key+' '+val);
                    var ad = {
                        el: '[' + key + '] '+ val,
                    };
                    var ad_param = $.extend( {}, track_adzone_data, ad );
                    gaload += $.param( ad_param ) + "\n";
                });
            }

            
			//console.log(gaload);
			if ( gaload.length ) {
				$.post(
					host + batch_path,
					gaload
				);
            }
            
            return args;
        },

        track_clicks: function(args, ids){
            console.log('Track Click');
            console.log(args);
            var gaload = '';
            var ga_path = collect_path;
            var track_banner_data = gaTracker.ga_track_data({'ea': '[banner] Clicks', 'el': '[' + ids.bid + '] ' + loaded_ang[ids.lid][ids.bid].name}, args);
            gaload = $.param( track_banner_data )+"\n";

            if( ids.aid !== 0){
                ga_path = batch_path;
                var track_adzone_data = gaTracker.ga_track_data({'ea': '[adzone] Clicks', 'el': '[' + ids.aid + '] ' + loaded_angzones[ids.aid]}, args);
                gaload += $.param( track_adzone_data );
            }
            
            if ( gaload.length ) {
                //console.log(loaded_ang[ids.lid][ids.bid].name);
                console.log(gaload);
				$.post(
					host + ga_path,
					gaload
				);
            }
        },


        ga_track_data: function(ga, args){
            var track_data = {
				v: 1,
				tid: args.gaID,
				cid: args.cid,
				t: 'event',
				ni: 1,
				ec: 'Adning Advertising',
				ea: ga.ea,
				dl: document.location.origin + document.location.pathname,
				dp: document.location.pathname,
            };

            if(typeof ga.el !== 'undefined'){
                track_data.el = ga.el;
            }
            
            return track_data;
        }
    }

    $(function(){
        if( typeof ang_tracker !== 'undefined'){
            if( typeof loaded_ang !== 'undefined'){
                    
                var tracker = new gaTracker.setup( 'ang_tracker', ang_tracker );
                ( function( _tracker ){
                    //console.log(_tracker);
                    $('body').find('.strack_bnr').on('click', function(e){
                        var bid = $(this).data('bid'),
                            aid = $(this).data('aid'),
                            lid = $(this).data('lid');
                        
                        gaTracker.track_clicks( _tracker, {'bid': bid, 'aid':aid, 'lid':lid} );
                    });
                    
                })( tracker );
                    
            }
        }
    });

})(jQuery);