<div class="wrap">
    <h2> LocateMe Admin</h2>

    <?php
        $results = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."locateme_positions");
        foreach ($results as $result) {
            echo $result->lon." : ";
            echo $result->lat." : ";
            echo $result->accuracy." : ";
            echo $result->timestamp."";
            echo "<br/>";
        }
    ?>
</div>
