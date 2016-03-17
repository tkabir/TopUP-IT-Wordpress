(function($) {
    // will appear when click on insert into post button

//    $(document).keypress(function(e) {
//        if (e.which === 13) {
//            gmap_insert_shortcode();
//        }
//    });
    $('.gmap_insert_into_editor ').on('click', gmap_insert_shortcode);

    function gmap_insert_shortcode() {

        $(".gmap_msg_error").html('');
        var show_map_heading, error_status = false;
        var map_title = $("#sc_map_title").val();
        if ($("#sc_show_map_heading").is(':checked') === true) {
            show_map_heading = 1;
        } else {
            show_map_heading = 0;
        }


        var map_latitude = $("#sc_map_latitude").val();
        var map_longitude = $("#sc_map_longitude").val();
        var map_zoom = $("#sc_map_zoom").val();
        var map_width = $("#sc_map_width").val();
        var map_height = $("#sc_map_height").val();
        var map_type = $("#sc_map_type").val();

        if (map_latitude === '') {
            $(".gmap_msg_error").append('<div class="error bellow-h2 notice notice-error is-dismissible"><p>Latitude is required.</p></div>');
            error_status = true;
        }
        if (map_longitude === '') {
            $(".gmap_msg_error").append('<div class="error bellow-h2 notice notice-error is-dismissible"><p>Longitude is required.</p></div>');
            error_status = true;
        }
        
        if(map_width===''){
            map_width='100%';
        }
        else{
            map_width+='%';
        }
        
        
        if(map_height===''){
            map_height='350px';
        }
        else{
            map_height+='px';
        }
        
        if (error_status === true) {
            return;
        }

        var shortcode =
                '[gmap title="' + map_title +
                '" show_heading="' + show_map_heading +
                '" latitude="' + map_latitude +
                '" longitude="' + map_longitude +
                '" zoom="' + map_zoom +
                '" width="' + map_width +
                '" height="' + map_height +
                '" map_type="' + map_type + '"]';



        if (!tinyMCE.activeEditor || tinyMCE.activeEditor.isHidden()) {
            $('textarea#content').val(shortcode);
        } else {
            tinyMCE.execCommand('mceInsertContent', false, shortcode);
        }
        //close the thickbox after adding shortcode to editor
        self.parent.tb_remove();
    }

})(jQuery);