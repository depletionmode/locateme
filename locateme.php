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
        /*
            $lon_f = (float)$lon;
            $lat_f = (float)$lat;
            $accuracy_i = (int)$accuracy'
        */

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

    function widget_locateme()
    {
    }

    function widget_locateme_init()
    {
        register_sidebar_widget(__('LocateMe'), 'widget_locateme');
    }

    add_action('plugins_loaded', 'widget_locateme_init');
?>
