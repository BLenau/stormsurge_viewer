<!--
    address_init.php
-->

<?php
    include("connect.php");
    include("marker_settings.php");

    /*
     * These variables will hold the results of the queries on the database. 
     * They must be declared here as empty strings because they will be used 
     * later.
     */
    $wmResult = ""; // The results from the watermark query
    $tpResult = ""; // The results from tht hurricane track point query
    $lat      = "";
    $lng      = "";
    $dLat     = "";
    $dLng     = "";

    /*
     * If the radius that was entered is a number, then save it as a PHP
     * variable, otherwise, set the variable to 0.
     */
    if (isset($_POST['radius']) && is_numeric($_POST['radius'])) {
        $radius = $_POST['radius'];
    } else {
        $radius = 0;
    }
    
    /*
     * If the address has been selected and submitted, then use it to determine
     * the query.
     */
    if (isset($_POST['address']) && $_POST['address'] != "") {
        if (isset($_POST['latlng'])) {
            $latlng = $_POST['latlng'];
        } else {
            $_POST['latlng'] = "31.1497222,-81.4916667";
            $latlng = $_POST['latlng'];
        }

        /* Put the latlng into a usable form. */
        $tempLatLng = explode(",", $latlng);
        $lat        = $tempLatLng[0];
        $lng        = $tempLatLng[1];
        
        /*
         * dLat and dLng are the length of the radius of the circle in miles,
         * rather than degrees of longitude and latitude. However, the
         * calculations for the dLng are more complicated because longitude
         * is actually a function of latitude, and therefore changes as the
         * point of origin (the center of the circle) changes.
         */
        $dLat       = abs($radius / 69.1741);
        $dLng       = $dLat / abs(cos($lat * (pi() / 180)));

        /*
         * The select statement that will pull all of the watermarks within a
         * square of the latitude and longitude given, within the radius
         * 
         * (latitude of the address +- the entered radius in degrees latitude
         * (longitude of the address +- the entered readius in degrees
         *                              longitude
         * The resultant points are further refined to create a circular radius
         * when the markers are generated on the page.
         */

        $wmResult = get_points("WaterMarks", 
                               $lat, $lng, $dLat, $dLng, $db);
        $tpResult = get_points("HurricaneTrackPoints",
                               $lat, $lng, $dLat, $dLng, $db);
    } else if (isset($_POST['hname']) && $_POST['hname'] != "") {
        $wm_sql = "Select *
                   From WaterMarks natural join Hurricane
                   Where HName like '%" . $_POST['hname'] . "%';";
        $tp_sql = "Select *
                   From HurricaneTrackPoints natural join Hurricane
                   Where HName like '%" . $_POST['hname'] . "%'
                   Order By Date_Time;";
                                                                            
        $lat    = 31.1497222;
        $lng    = -81.4916667;
        $dLat   = 10000 / 69.1741;
        $dLng   = $dLat / abs(cos($lat * (pi() / 180)));
        $latlng = $lat . ", " . $lng;

        $wmResult = mysql_query($wm_sql, $db);
        $tpResult = mysql_query($tp_sql, $db);
    } else {
        $_POST['latlng'] = "31.1497222,-81.4916667";
    }
?>
