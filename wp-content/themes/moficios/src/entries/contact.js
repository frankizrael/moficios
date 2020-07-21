import '../scss/contact.scss';
(function(jQuery) {
    var icono = url_principal+'/src/assets/images/icono-tinka.png';
    function new_map( $el ) {
        const $markers = $el.find('.marker');
        const args = {
            zoom		: 16,
            center		: new google.maps.LatLng(0, 0),
            mapTypeId	: google.maps.MapTypeId.ROADMAP
        };
        let map = new google.maps.Map( $el[0], args);
        map.markers = [];
        $markers.each(function(){
            add_marker( jQuery(this), map );
        });
        center_map( map );
        return map;
    }
    function add_marker( $marker, map ) {
        const latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
        let marker = new google.maps.Marker({
            position	: latlng,
            map			: map,
            icon: icono
        });
        map.markers.push( marker );
        if( $marker.html() )
        {
            let infowindow = new google.maps.InfoWindow({
                content		: $marker.html()
            });
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open( map, marker );
            });
        }
    }
    function center_map( map ) {
        let bounds = new google.maps.LatLngBounds();
        jQuery.each( map.markers, function( i, marker ){
            let latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
            bounds.extend( latlng );
        });
        if( map.markers.length == 1 )
        {
            map.setCenter( bounds.getCenter() );
            map.setZoom( 16 );
        }
        else
        {
            map.fitBounds( bounds );
        }
    }
    let map = null;
    jQuery(document).ready(function(){
        jQuery('.acf-map').each(function(){
            map = new_map( jQuery(this) );
        });
    });
})(jQuery);