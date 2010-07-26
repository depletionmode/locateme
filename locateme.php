<?php
    /*
    Plugin Name: LocateMe
    Plugin URI: http://blog.2of1.org
    Author: David Kaplan
    Author URI: http://blog.2of1.org
    Version: 1.0
    */

    function locateme_install()
    {
        global $wpdb;
        $table = $wpdb->prefix."locateme_positions";
        $structure = "CREATE TABLE $table (
            id INT(9) NOT NULL AUTO_INCREMENT,
            lon FLOAT,
            lat FLOAT,
            accuracy INT(9),
            UNIQUE KEY id (id)
        );";
        $wpdb->query($structure);
    }
?>
