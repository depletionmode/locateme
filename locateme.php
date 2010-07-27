<?php
    /*
    Plugin Name: LocateMe
    Plugin URI: http://blog.2of1.org
    Author: David Kaplan
    Author URI: http://blog.2of1.org
    Version: 1.0
    */

    function locateme_activate()
    {
        global $wpdb;
        $table = $wpdb->prefix."locateme_positions";
        $structure = "CREATE TABLE $table (
            id INT(9) NOT NULL AUTO_INCREMENT,
            lon FLOAT,
            lat FLOAT,
            accuracy INT(9),
            timestamp TIMESTAMP,
            UNIQUE KEY id (id)
        );";
        $wpdb->query($structure);
    }

    add_action('activate_locateme/locateme.php', 'locateme_activate');

    function locateme_deactivate()
    {
        global $wpdb;
        $table = $wpdb->prefix."locateme_positions";
        $wpdb->query("DROP TABLE $table");
    }

    add_action('deactivate_locateme/locateme.php', 'locateme_deactivate');

    function log_location()
    {
        if (!empty($_GET['lon']) && !empty($_GET['lat']) && !empty($_GET['accuracy'])) {
            $lon = $_GET['lon'];
            $lat = $_GET['lat'];
            $accuracy = $_GET['accuracy'];

            global $wpdb;
            $table = $wpdb->prefix."locateme_positions";
            $insert = "INSERT INTO $table(lon, lat, accuracy, timestamp) 
                VALUES ($lon, $lat, $accuracy, now());";
            $wpdb->query($insert);
        }
    }

    add_action('parse_request', 'log_location');

    function locateme_menu()
    {
        global $wpdb;
        include 'locateme-admin.php';
    }

    function locateme_admin_actions()
    {
        add_options_page("LocateMe", "LocateMe", 1, "Locateme-Admin", "locateme_menu");
    }

    add_action('admin_menu', 'locateme_admin_actions');

    function widget_locateme($args)
    {
	extract($args);
        echo $args['before_widget'];
        echo $args['before_title'].'My Location'.$args['after_title'];
        ?>
        <div id="map_canvas" style="width:100%; height:100px;"></div>

        <?php 
        echo 'as of ';
        echo $args['after_widget'];
    }

    function widget_locateme_init()
    {
        register_sidebar_widget(__('LocateMe'), 'widget_locateme');
    }

    add_action('plugins_loaded', 'widget_locateme_init');

    function load_into_head()
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."locateme_positions where id = (select max(id) from ".$wpdb->prefix."locateme_positions)");
        foreach ($results as $result) {
            $lng = $result->lon;
            $lat = $result->lat;
        }
        ?>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript">
        var lat = <?php echo $lat; ?>;
        var lng = <?php echo $lng; ?>;
        function map_init() {
            var title = null;

            var latlng = new google.maps.LatLng(lat,lng);
            var options = {
                disableDefaultUI: true,
                zoom: 5,
                draggable: false,
                disableDoubleClickZoom: true,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.HYBRID
            }
            var map = new google.maps.Map(document.getElementById("map_canvas"), options);
            var marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: title
            });
        }
        </script>
         <?php 
    }

    add_action(is_admin() ? 'admin_head' : 'wp_head', 'load_into_head');

    function filter_body($text)
    {
        return $text;
    }

    add_filter('body_onload', 'filter_body');
?>
