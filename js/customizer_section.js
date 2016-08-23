/**
 * 
 */


(function ( api ) {
    api.section( 'DeliberaLoginForm', function( section ) {
        section.expanded.bind( function( isExpanded ) {
            var url;
            if ( isExpanded ) {
                url = api.settings.url.home;
                api.previewer.previewUrl.set( url+'?showDeliberaLoginForm=1' );
            }
        } );
    } );
} ( wp.customize ) );