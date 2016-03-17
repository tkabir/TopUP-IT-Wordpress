<?php

/**
 * Creating widget for Google Map
 */
class gmap_widget extends WP_Widget {

    public $base_id = 'gmap_widget'; //widget id
    public $widget_name = 'Google Map'; //widget name
    public $widget_options = array(
        'description' => 'Google Map' //widget description
    );

    public function __construct() {
        parent::__construct($this->base_id, $this->widget_name, $this->widget_options);
    }

    // Map display in front
    public function widget($args, $instance) {
        extract($args);
        extract($instance);

        $gmap_title = $title == '' ? get_option('gmap_title') : $title;
        $gmap_lat = $latitude == '' ? get_option('gmap_lat') : $latitude;
        $gmap_long = trim($longitude) == '' ? get_option('gmap_long') : $longitude;
        $gmap_width = $width == '' ? get_option('gmap_width') : $width;
        $gmap_height = $height == '' ? get_option('gmap_height') : $height;
        $gmap_zoom = $zoom == '' ? get_option('gmap_zoom') : $zoom;
        $gmap_type = $map_type;

        echo $before_widget
        . $before_title
        . $gmap_title;
        ?>
        <script type="text/javascript">
            google.maps.event.addDomListener(window, 'load', function() {
                new google.maps.Map(document.getElementById("gmp_<?php echo $this->id; ?>"), {
                    center: new google.maps.LatLng(<?php echo $gmap_lat; ?>,<?php echo $gmap_long; ?>),
                    zoom:<?php echo $gmap_zoom; ?>,
                    mapTypeId: google.maps.MapTypeId.<?php echo $gmap_type; ?>
                });
            });
        </script>

        <div id="gmp_<?php echo $this->id; ?>" style="width:<?php echo $gmap_width . '%'; ?>;height:<?php echo $gmap_height . 'px'; ?>;"></div>
        <?php
        echo $widget_body_text
        . $after_widget;
    }

    /**
     * Admin page options/settings
     * @param type $instance
     */
    public function form($instance) {
        ?>  
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" type="text" class="widefat">
        </p> 

        <p>
            <label for="<?php echo $this->get_field_id('latitude'); ?>">Latitude:</label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('latitude'); ?>" name="<?php echo $this->get_field_name('latitude'); ?>" value="<?php echo $instance['latitude']; ?>" type="text" class="widefat">
        </p>  

        <p>
            <label for="<?php echo $this->get_field_id('longitude'); ?>">Longitude:</label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('longitude'); ?>" name="<?php echo $this->get_field_name('longitude'); ?>" value="<?php echo $instance['longitude']; ?>" type="text" class="widefat">
        </p>        

        <p>
            <label for="<?php echo $this->get_field_id('width'); ?>">Width:</label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>" type="text" class="regular-text"> %
        </p>        

        <p>
            <label for="<?php echo $this->get_field_id('height'); ?>">Height:</label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $instance['height']; ?>" type="text" class="regular-text"> px
        </p>        

        <p>
            <label for="<?php echo $this->get_field_id('zoom'); ?>">Zoom:</label>
        </p>
        <p>
            <input id="<?php echo $this->get_field_id('zoom'); ?>" name="<?php echo $this->get_field_name('zoom'); ?>" value="<?php echo $instance['zoom']; ?>" type="text" class="widefat">
        </p>        

        <p>
            <label for="<?php echo $this->get_field_id('map_type'); ?>">Map Type:</label>
        </p>
        <p>
            <select  name="<?php echo $this->get_field_name('map_type'); ?>" id="<?php echo $this->get_field_id('map_type'); ?>" class="widefat">
                <option <?php selected($instance['map_type'], 'ROADMAP'); ?>>ROADMAP</option>
                <option <?php selected($instance['map_type'], 'SATELLITE'); ?>>SATELLITE</option>
                <option <?php selected($instance['map_type'], 'HYBRID'); ?>>HYBRID</option>
                <option <?php selected($instance['map_type'], 'TERRAIN'); ?>>TERRAIN</option>
            </select>
        </p>        
        <?php
    }

}

add_action('widgets_init', create_function('', 'return register_widget("gmap_widget");'));
