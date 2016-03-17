<?php
// Google Map shortcode
if (!function_exists('gmap_shortcode')) {

    function gmap_shortcode($atts, $content) {
        static $count;
        if (!$count) {
            $count = 0;
        }
        $count++;
        // default values for WP GMAP
        $atts = shortcode_atts(array(
            'title' => get_option('gmap_title','Map Heading'),
            'show_heading' => 0,
            'latitude' => get_option('gmap_lat','23.0214114'),
            'longitude' => get_option('gmap_long','90.0214114'),
            'width' => get_option('gmap_width','100').'%',
            'height' => get_option('gmap_height','300').'px',
            'zoom' => get_option('gmap_zoom','5'),
            'map_type' => get_option('gmap_type','ROADMAP')
                ), $atts);
//        echo get_option('gmap_title');
//        print_r($atts);die();
        ob_start();

        if (isset($atts['show_heading']) && $atts['show_heading'] == 1) {
            echo "<h1>" . $atts['title'] . "</h1>";
        }
        ?>
        <script type="text/javascript">
            google.maps.event.addDomListener(window, 'load', function() {
                new google.maps.Map(document.getElementById("gmp_<?php echo $count; ?>"), {
                    center: new google.maps.LatLng(<?php echo $atts['latitude']; ?>,<?php echo $atts['longitude']; ?>),
                    zoom:<?php echo $atts['zoom']; ?>,
                    mapTypeId: google.maps.MapTypeId.<?php echo $atts['map_type']; ?>
                });
            });
        </script>
        <div id="gmp_<?php echo $count; ?>" style="width:<?php echo $atts['width'] . ' !important;'; ?>;height:<?php echo $atts['height']; ?>  !important; "></div>
        <?php
        return ob_get_clean();
    }

}
//adding shortcode for Google Map
add_shortcode('gmap', 'gmap_shortcode');