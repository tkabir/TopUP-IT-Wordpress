<?php

//this button will show a popup that contains inline content
add_action('media_buttons_context', 'add_gmap_custom_button');

//This will be shown in the inline modal
add_action('admin_footer', 'add_inline_popup_content');

//action to add a custom button to the content editor
function add_gmap_custom_button($context) {

    //path to my icon
    $img = plugins_url('../gmap_icon_18.png', __FILE__);

    //the id of the container I want to show in the popup
    $container_id = 'gmap_container';

    //our popup's title
    $title = 'Select your map properties to insert into post';

    //append the icon
    $context .= "<a class='button thickbox' title='{$title}'
    href='#TB_inline?width=700&height=450&inlineId={$container_id}'>
    " . '<span class="wp-media-buttons-icon" style="background: url(' . $img . '); background-repeat: no-repeat; background-position: left bottom;"></span>' . "Add Google Map</a>";


    return $context;
}

function add_inline_popup_content() {
    ?>
    <style>
        .gmap_properties tr{
            height: 50px;
        }
    </style>
    <div id="gmap_container" style="display:none;">
        <h2>Map Properties</h2>
        <span class="gmap_msg_error" style="width:80%;">

        </span>
        <table style="width: 100%;" class="gmap_properties">
            <tr>
                <th width='30%'>Map Title</th>
                <td>
                    <input id="sc_map_title" name="map_title" value="" type="text" class="regular-text"><br/>
                    <input type="checkbox" value="1" name="show_map_heading" id="sc_show_map_heading"> <label for="sc_show_map_heading">Show as map heading</label>
                </td>
            </tr>
            <tr>
                <th>Latitude</th>
                <td><input id="sc_map_latitude" name="map_latitude" value="" type="text" class="regular-text"></td>
            </tr>
            <tr>
                <th>Longitude</th>
                <td><input id="sc_map_longitude" name="map_longitude" value="" type="text" class="regular-text"></td>
            </tr>
            <tr>
                <th>Zoom</th>
                <td><input id="sc_map_zoom" name="map_zoom" value="5" type="text" class="regular-text"></td>
            </tr>
            <tr>
                <th>Width</th>
                <td><input id="sc_map_width" name="map_width" value="" type="text" class="regular-text"> %</td>
            </tr>
            <tr>
                <th>Height</th>
                <td><input id="sc_map_height" name="map_height" value="" type="text" class="regular-text"> px</td>
            </tr>
            <tr>
                <th>Map Type</th>
                <td>
                    <select id="sc_map_type" class="regular-text">
                        <option>ROADMAP</option>
                        <option>SATELLITE</option>
                        <option>HYBRID</option>
                        <option>TERRAIN</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button class="button gmap_insert_into_editor">Insert into post</button>
                </td>
            </tr>
        </table>
    </div>
    <?php

}

//javascript code needed to make shortcode appear in TinyMCE edtor
add_action('admin_enqueue_scripts', 'enqueue_gmap_editor_sc_js');

function enqueue_gmap_editor_sc_js() {
    global $pagenow;
    if ($pagenow == 'post.php' || $pagenow == 'post-new.php') {
        wp_enqueue_script('gmap_editor_shortcode', plugins_url('../assets/js/gmap_editor_shortcode.js', __FILE__), array('jquery'), '20151018', true);
    }
}
?>
