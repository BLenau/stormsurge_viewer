<?php
    include("address_init.php");
?>
    <script type="text/javascript" src="scripts/lightbox/js/prototype.js">
    </script>
    <script type="text/javascript" 
            src="scripts/lightbox/js/scriptaculous.js?load=effects,builder">
    </script>
    <script type="text/javascript" src="scripts/lightbox/js/lightbox.js">
    </script>
    <link type="text/css"  href="scripts/lightbox/css/lightbox.css" 
          rel="stylesheet" media="screen" />

<script type="text/javascript">
    <!--
    /* Search for MULTIPLE_WINDOWS to find all changes needed to display
       multiple information windows. */

    /** 
     * A constant for converting miles to meters (1 mile * 1609 = 1 meter)
     */
    var meterToMileScale = 1609;

    /**
     * The individual location marker that will be generated on the
     * Google Map.
     */
    var marker;

    /**
     * The Google Map that will be drawn on the page.
     */
    var myMap;

    /**
     * The geocoder that will be used for geographical functionality.
     * (Converting an address into a latln, determining distance between
     *  two latlngs, etc.)
     */
    var geocoder = new google.maps.Geocoder();

    /**
     * The actual latitude and longitude that will be used for generating
     * the markers on the Google Map.
     */
    var latlng;

    <?php
    if (isset($_POST['latlng'])) {
    ?>
    var temp       = '<?php echo $_POST['latlng']; ?>';
    <?php
    } else {
    ?>
    var temp       = '31.1497222,-81.4916667';
    <?php
    }
    ?>
    var tempArray  = temp.split(',');
    latlng         = new google.maps.LatLng(tempArray[0], tempArray[1]);

    /**
     * The array that will contain all of the markers that are generated
     * for the watermarks search.
     */
    var wmMarkers = [];

    /**
     * The array that will contain all of the markers that are generated
     * for the hurricane trackpoint search.
     */
    var tpMarkers = [];
   
    var iconColors = [
            'red',
            'green',
            'blue',
            'orange',
            'purple',
            'aqua',
            'yellow',
            'pink',
            'brown',
            'grey',
            'dark_aqua',
            'dark_purple',
            'white',
            'olive',
            'lime'
        ];
  
    var hurricaneToColorMap = new Array();

    var indexCount = 0;

    /* MULTIPLE_WINDOWS */
    /* Comment out the following declaration to allow for multiple info windows
       to be displayed at once. */
    var infoWindow = new google.maps.InfoWindow();

    /**
     * Creates the initial map as well as the basic components that go
     * with it (center point, the circle of the given radius, etc.)
     */
    function initialize() {
        markerSettings = MarkerSettings.getInstance();
        var geocoder   = new google.maps.Geocoder();
        var zoom       = 5;  // default 

        <?php
        if (isset($_POST['latlng'])) {
        ?>
        var temp       = '<?php echo $_POST['latlng']; ?>';
        <?php
        } else {
        ?>
        var temp       = '31.1497222,-81.4916667';
        <?php
        }
        ?>
        var tempArray  = temp.split(',');
        latlng         = new google.maps.LatLng(tempArray[0], tempArray[1]);

        <?php 
        if (isset($latlng)) {
        ?>
            var zoomCount = 0;
            var tempRadius = <?php echo $radius; ?>;

            if (tempRadius > 35) {
                while (tempRadius > 1) {
                    tempRadius = tempRadius / 2;
                    zoomCount++;
                }

                zoom = 14 - zoomCount;

                if (zoom < 0) {
                    zoom = 0;
                }
            } else if (tempRadius <= 0) {
                zoom = 5;
            } else {
                zoom = 8;
            }
<?php 
        }
?>

        /**
         * The set of options that the map will use when it is being
         * created.
         */
        var myOptions = {
            disableDoubleClickZoom: true,
            navigationControl: true,
            navigationControlOptions: {
                style: google.maps.NavigationControlStyle.ZOOM_PAN
            },
            zoom: zoom,
            mapTypeId: google.maps.MapTypeId.HYBRID,
            center: latlng
        };

        myMap = new google.maps.Map(document.getElementById("map_canvas"),
            myOptions);

        markerSettings.setMap(myMap);
        google.maps.event.addListener(myMap, 'click', function(event) {
            mapClicked(event);
        });
        <?php
        if (isset($latlng) && isset($_POST['address']) && $_POST['address'] != "") {
        ?>
        createCircle();
        <?php
        }
        ?>
        
<?php 
        // Determine which watermark and track points will need to be retrieved
        // by checking if the address or the hname was submitted.
        if (isset($_POST['address']) && $_POST['address'] != "") {
?>
            initWmMarkers("address");
            initTpMarkers("address");

            setHurricaneColorLegend();
<?php 
        } else if (isset($_POST['hname']) && $_POST['hname'] != "") {
?>
            initWmMarkers("hname");
            initTpMarkers("hname");

            setHurricaneColorLegend();
<?php 
        } 
?>
    }

    /*
     * When the map is clicked, geocode the location of the click, and then
     * prompt the user if they want to search that location.
     */
    function mapClicked(event) {
        var newAddress;
        var latLng = event.latLng;
        var addressFind = new google.maps.Geocoder();
        addressFind.geocode({'latLng': latLng}, 
                            function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1] && 
                   (results[1].formatted_address != "United States")) {
                    newAddress = results[1].formatted_address;
                    if (confirm(  "Would you like to search " + newAddress 
                                + " ?")) {
                        regex = /[() ]*/g;
                        latLng = latLng.toString().replace(regex, "");
                        document.getElementById("latlng").value = latLng;
                        document.getElementById("address").value = newAddress;
                        submitSelectionForm();
                    }
                } else {
                    if (confirm("Would you like to search this area?")) {
                        document.getElementById("address").value = latLng;
                        regex = /[() ]*/g;
                        latLng = latLng.toString().replace(regex, "");
                        document.getElementById("latlng").value = latLng;
                        submitSelectionForm();
                    }
                }
            } else {
                if (confirm("Would you like to search this area?")) {
                    document.getElementById("address").value = latLng;
                    regex = /[() ]*/g;
                    latLng = latLng.toString().replace(regex, "");
                    document.getElementById("latlng").value = latLng;
                    submitSelectionForm();
                }
            }
        });
    }

    /*
     * Sets the color of small box next to each hurricane in the checkbox to
     * the color of the map markers for that hurricane.
     */
    function setHurricaneColorLegend() {
        var markerSettings = MarkerSettings.getInstance();
        var hurricaneToColorMap = markerSettings.getHurricaneToColorMap();

        jQuery.each(hurricaneToColorMap, function() {
            if (document.getElementById(this) != undefined) {
                var boxId   = this + '-color-box';
                var bgColor = hurricaneToColorMap[this]
                var bgMappedColor = mapColor(bgColor);
                document.getElementById(boxId).style.backgroundColor=bgMappedColor;
                //document.getElementById(boxId).setAttribute(
                //    'background-color', bgMappedColor);
            }
        });
    }
    
   /*
    *  Determine which icon to use for the marker based upon the hName and
    *  markerType and return that icon.
    *
    *  Params:
    *    markerSettings  - The object in which all the marker settings are
    *                      kept.
    *    hName           - The name of the hurricane for which this marker
    *                      is to be associated.
    *    markerType      - The type of marker to use.
    *
    *  Return:
    *    The icon based on the hurricane and marker type.
    */
    function getIcon(markerSettings, hName, markerType) {
        if (markerType === 'watermark') {
            var hurricaneToColorMap = markerSettings.getHurricaneToColorMap();
     
            if (jQuery.inArray(hName, hurricaneToColorMap) < 0) {
                markerSettings.addHurricaneToColorToMap(
                    hName, iconColors[indexCount % iconColors.length]);

                indexCount++;
            }          

            icon = new google.maps.MarkerImage(
                'images/markers/' + hurricaneToColorMap[hName] + '.png',
                new google.maps.Size(15, 15), new google.maps.Point(0, 0), 
                new google.maps.Point(7, 10));
        } else {
            icon = new google.maps.MarkerImage(
                'images/markers/black_dot.png', new google.maps.Size(9, 9),
                new google.maps.Point(0, 0), new google.maps.Point(7, 10));
        }

        return icon;
    }

    /*
     * Creates the markers on the map that correspond with each watermark and
     * track point.
     *
     * Params:
     *    lat    - the latitude of the mark where it will be placed on the map
     *    lng    - the longitude of the mark where it will be placed on the map
     *    radius - the size of the search radius; used for a final check to
     *             determine if the watermark is within the radius
     *    hName  - the hurricane that the marker is associated with
     */
    function createMarker(lat, lng, radius, hName, 
                          origin, markerType, content) {
        var markerSettings = MarkerSettings.getInstance();
        var latlng         = new google.maps.LatLng(lat, lng);
        var distance       = (google.maps.geometry.spherical.
                              computeDistanceBetween(latlng, origin) /
                              meterToMileScale);

        if (distance < radius) {
            var map = markerSettings.getMap();
            var icon = getIcon(markerSettings, hName, markerType);
            var point = new google.maps.Point(0, 20);
 
            var marker = new google.maps.Marker({
                map: null,
                draggable: false,
                optimized: false,
                position: latlng,
                icon: icon
            });
            /* MULTIPLE_WINDOWS */
            /* Uncomment the below declaration to allow mutliple info windows
               to be displayed at once. */
            //var infoWindow = new google.maps.InfoWindow();

            var contentString = content;

            /* MULTIPLE_WINDOWS */
            /* Comment out the two event listeners below to allow multiple
               info windows to be displayed at once. */
            google.maps.event.addListener(marker, 'click', function() {
                infoWindow.setContent(contentString);
                infoWindow.setPosition(marker.getPosition());
                infoWindow.open(map, marker);
                marker.setAnimation(google.maps.Animation.BOUNCE);
            });

            google.maps.event.addListener(infoWindow, 'position_changed',
                                         function() {
                marker.setAnimation(null);
                infoWindow.close();
            });

            /* MULTIPLE_WINDOWS */
            /* Uncomment these two functions to allow multiple info windows to
               be displayed at once. */
            //addDisplayInfoEventListener(marker, latlng, infoWindow, map);
            //addStopBounceEventListener(marker, infoWindow);
            
            return marker;
        } else {
            return undefined;
        }
    }

    /*
     * Creates the landfall marker on the map
     *
     * Params:
     *    lat    - the latitude of the mark where it will be placed on the map
     *    lng    - the longitude of the mark where it will be placed on the map
     *    radius - the size of the search radius; used for a final check to
     *             determine if the watermark is within the radius
     *    hName  - the hurricane that the marker is associated with
     */
    function createLfMarker(lat, lng, radius, hName, 
                          origin, markerType, content) {
        var markerSettings = MarkerSettings.getInstance();
        var latlng         = new google.maps.LatLng(lat, lng);
        var distance       = (google.maps.geometry.spherical.
                              computeDistanceBetween(latlng, origin) /
                              meterToMileScale);

        if (distance < radius) {
            var map = markerSettings.getMap();
            var icon = getIcon(markerSettings, hName, markerType);
            var point = new google.maps.Point(0, 20);
 
            var marker = new google.maps.Marker({
                map: null,
                draggable: false,
                optimized: false,
                position: latlng,
                icon: icon
            });
            /* MULTIPLE_WINDOWS */
            /* Uncomment the below declaration to allow mutliple info windows
               to be displayed at once. */
            //var infoWindow = new google.maps.InfoWindow();

            var contentString = content;

            /* MULTIPLE_WINDOWS */
            /* Comment out the two event listeners below to allow multiple
               info windows to be displayed at once. */
            /*
            google.maps.event.addListener(marker, 'click', function() {
                markerSettings.getMap().setCenter(marker.getPosition());
                marker.setAnimation(google.maps.Animation.BOUNCE);
            });

            google.maps.event.addListener(infoWindow, 'position_changed',
                                         function() {
                marker.setAnimation(null);
            });
            */

            /* MULTIPLE_WINDOWS */
            /* Uncomment these two functions to allow multiple info windows to
               be displayed at once. */
            //addDisplayInfoEventListener(marker, latlng, infoWindow, map);
            //addStopBounceEventListener(marker, infoWindow);
            
            return marker;
        } else {
            return undefined;
        }
    }

    /*
     * Initializes the watermark markers for the current query. The function
     * handles when an address search is initiated or when an hname search is
     * requested.
     *
     * Params:
     *    search - the search type (address or hname)
     */
    function initWmMarkers(search) {
        <?php
        if (isset($_POST['latlng'])) {
            
        //if (isset($latlng)) {
            if ((isset($_POST['address']) && $_POST['address'] != "") ||
                    (isset($_POST['hname']) && $_POST['hname'] != "")) {
                $i = 0;
                if (mysql_num_rows($wmResult) > 0) {
        ?>    
                var origin;
                if (search == "address") { 
                    origin = new google.maps.LatLng(<?php echo $latlng; ?>);
                } else {
                    origin = new google.maps.LatLng(31.1487222, -81.4916667);
                }

                var markerSettings = MarkerSettings.getInstance();

                <?php
                    /* 
                     * Run all the queries and store the results for later use.
                     * This reduces runtime and processing time because the
                     * queries and data retrieval take the most time.  This
                     * approach reduces number of queries but increases the
                     * amount of loops needed to sort the results in a usable
                     * fashoin.
                     */
                    $meas_types = organize_rows("lkpWLMeasType", "WLMeasTypeID", 
                                               $db);
                    $vdatum    = organize_rows("lkpVDatum", "VDatumID", $db);
                    $mark_types = organize_rows("lkpMarkType", "MarkTypeID", $db);
                    $references = organize_rows("Source_References", "RefID", $db);

                    $photo_wmids   = get_wmphoto_ids($db);
                    $pdf_wmids     = get_wmphoto_ids_clause($db, "PDF");
                    $png_wmids     = get_wmphoto_ids_clause($db, "PNG");
                    $pdf_results   = organize_rows_clause("WaterMarkPhotos", "WMID", $db,
                                                   "PhotoType", "PDF");
                    $png_results   = organize_rows_clause("WaterMarkPhotos", "WMID", $db,
                                                   "PhotoType", "PNG");
                    $min_wmid      = $photo_wmids[0];
                    $max_wmid      = $photo_wmids[count($photo_wmids) - 1];
                    $png           = null;
                    $pdf           = null;
                    $image_found   = false;

                    /*
                     * The references are in the form 
                     * 'REFID-REFLINKNAME-#REFLINK#' so these have to be split
                     * up into usable forms.
                     */
                    foreach ($references as $source) {
                        $ref_link = $source['RefLink'];
                        if (!is_null($ref_link)) {
                            $ref_array = explode("#", $ref_link);
                            if (count($ref_array) >= 1 && 
                                                   count($ref_array[0]) >= 1) {
                                if (strlen($ref_array[0]) == 0) {
                                    $references[$source['RefID']]['RefLink'] =
                                        $ref_array[1];
                                } else {
                                    $references[$source['RefID']]['RefLink'] =
                                        'pdfs/' . $ref_array[0];
                                }
                            }
                        }
                    }
                
                    /*
                     * Determines if the WMID of the current watermark is within
                     * the range WMID's that have images associated with them.
                     * Then it determines if the WMID actually has imiges
                     * associated with it.
                     */
                    while ($row = mysql_fetch_array($wmResult, MYSQL_ASSOC)) {
                        $wmid = $row['WMID'];
                
                        if ($wmid >= $min_wmid && $wmid <= $max_wmid) {
                            if (in_array($wmid, $photo_wmids)) {
                                if (in_array($wmid, $png_wmids)) {
                                    $png = $png_results[$wmid];
                                    $image_found = true;
                                }
                                if (in_array($wmid, $pdf_wmids)) {
                                    $pdf = $pdf_results[$wmid];
                                    $image_found = true;
                                }
                            }
                        }
                
                        $point = $row['Latitude'] . ", " .  $row['Longitude'];
                
                        $start_div    = '';
                        $start_table  = '';
                        $left_column  = '';
                        $right_column = '';
                        $end_table    = '';
                        $end_div      = '';
                        
                        /*
                         * If any of the ID's that were queried were null, set
                         * them to be 1, which is defined as NULL in the tables.
                         */
                        if (is_null($row['MarkTypeID'])) {
                            $row['MarkTypeID'] = 1;
                        }
                        if (is_null($row['VDatumID'])) {
                            $row['VDatumID'] = 1;
                        }
                        if (is_null($row['RefID'])) {
                            $row['RefID'] = 1;
                        }

                
                        /*
                         * All of the logic used to build the information window
                         * for each watermark.
                         */
                        if (!$image_found) {
                            $start_div = '<div style="width: 200px; height: 110px;">';
                            $start_table = '<table cols="1" style="border-spacing: 10px 5px;"><tr>';
                        } else {
                            $image_found = false;
                            $start_div = '<div style="width: 300px;">';                      
                            $start_table = '<table cols="2" style="border-spacing: 10px 5px;"><tr>';
                            if ($png != null) {                           
                                $right_column = '<td><a href="' . $png['PLink'] . '"'
                                    . 'rel="lightbox' . $row['HName'] . ' title="' 
                                    . $png['Description'] . '">' 
                                    . '<img src="' . $png['PLink'] . '" alt="' 
                                    . $png['PName'] . '"' 
                                    . ' width="75" height="75" /></a>' 
                                    . '</td></tr>';

                                $png = null;
                            }
                            if ($pdf != null) {
                                $right_column .= '<td><a href="' . $pdf['PLink'] . '"' 
                                    . 'target="_new"><font size="2pt">' 
                                    . 'Click here</a> for more information ' 
                                    . '</font></td>';

                                $pdf = null;
                            }
                        }
                        if ($row['State'] == "LA") {
                            $county_word = "Parish";
                        } else {
                            $county_word = "County";
                        }
                        $left_column =  '<td><font size="2pt">' . $row['HName'] . ' (' 
                             . $row['HYear'] . ')'
                             . '<br />'
                             . '<hr style="width: 185px;" />'
                             . $row['MeasElev'] . ' ft. above ' 
                             . $vdatum[$row['VDatumID']]['VDatum']
                             . '<br />'
                             . $row['County'] . ' ' . $county_word
                             . '<br />'
                             . $meas_types[$row['WLMeasTypeID']]['WLMeasType'] . ': '
                             . $mark_types[$row['MarkTypeID']]['MarkType'];
                        $might_be_null = $references[$row['RefID']]['RefLink'];
                        if (!is_null($might_be_null)) {
                            if (!(substr($might_be_null,
                                  strlen($might_be_null) - 1) == '/')) {
                                $might_be_null .= '.pdf';
                            }
                            $left_column .= '<br />'
                                . 'Click <a href="' . $might_be_null
                                . '" target="new">here</a> for references';
                        }
                        $left_column .= '</font></td>';            
                        $end_table = '</tr></table>';
                        $end_div   = '</div>';
                
                        $content_string =   $start_div . $start_table . $left_column
                                          . $right_column . $end_table . $end_div;
                
                ?>
                    var lat = <?php echo $row['Latitude']; ?>;
                    var lng = <?php echo $row['Longitude']; ?>;
                    var hName = '<?php echo $row['HName']; ?>';
                    var contentString = '<?php echo $content_string; ?>';
                    var radius;

                    if (search == "address") {
                        radius = <?php echo $radius; ?>;
                    } else {
                        radius = 100000;
                        myMap.setZoom(5);
                    }

                    var marker = createMarker(lat, lng, radius, 
                                              '<?php echo $row['HName']; ?>',
                                              origin, 'watermark', 
                                              contentString);

                    if (marker !== undefined) {
                        markerSettings.mapMarkerToHurricane(
                            '<?php echo $row['HName']; ?>', 
                            'watermarks', marker);
                    }
                
                    var trackPointsList = 
                        markerSettings.getTrackPointsHurricanes();

                    if (jQuery.inArray(hName , trackPointsList) < 0) {
                        markerSettings.addTrackPointsHurricane(hName);
                    }
        <?php
                
                        $i++;
                    }
                }
            }
        }
        ?>
    }

    function initTpMarkers(search) {
        <?php
        if (isset($_POST['latlng'])) {
        //if (isset($latlng)) {
            if ((isset($_POST['address']) && $_POST['address'] != "") ||
                    (isset($_POST['hname']) && $_POST['hname'] != "")) {
                $i = 0;

                if (mysql_num_rows($tpResult) > 0) {
        ?>    
                var origin;
                if (search == "address") { 
                    origin = new google.maps.LatLng(<?php echo $latlng; ?>);
                } else {
                    origin = new google.maps.LatLng(31.1487222, -81.4916667);
                }

                var markerSettings  = MarkerSettings.getInstance();
                var trackLinePoints = new Array();

<?php
                while ($row = mysql_fetch_array($tpResult, MYSQL_ASSOC)) {
                    $point = $row['Latitude'] . ", " .  $row['Longitude'];
                    if (!is_null($row['Latitude']) && 
                            !is_null($row['Longitude'])) {
?>
                    var lat = <?php echo $row['Latitude']; ?>;
                    var lng = <?php echo $row['Longitude']; ?>;
                <?php
                $startDiv     = '';
                $table        = '';
                $tableContent = '';
                $endTable     = '';;
                $endDiv       = '';

                /* Build the information window HTML. */
                $startDiv = '<div style="width: 220px;">';
                $table = '<table cols="2" style="border-spacing: 10px 5px;"><tr><td>';
                $tableContent = '<font size="2pt">Hurricane Name:</font></td>' .
                                '<td><font size="2pt">' . $row['HName'] . '<font></td></tr>' .
                                '<tr><td><font size="2pt">Hurricane Year:</font></td>' .
                                '<td><font size="2pt">' . $row['HYear'] .
                                '</font></td></tr>' . 
                                '<tr><td><font size="2pt">Date_Time:</font></td>' .
                                '<td><font size="2pt">' . $row['Date_Time'] . '</font></td></tr>';
                $endTable = '</table>';
                $endDiv = '</div>';

                $content_string = $startDiv . $table . $tableContent . $endTable .
                                 $endDiv;
                ?>
                    var contentString = '<?php echo $content_string; ?>';
                
                    var marker = createMarker(lat, lng, 10000, 
                                              '<?php echo $row['HName']; ?>',
                                              origin, 'trackpoint',
                                              contentString);
                    if (marker !== undefined) {
                        markerSettings.mapMarkerToHurricane(
                            '<?php echo $row['HName']; ?>', 
                            'trackpoints', marker);
                        
                        if (jQuery.inArray('<?php echo $row['HName']; ?>', 
                                           trackLinePoints) < 0) {
                            trackLinePoints.push('<?php echo $row['HName'];?>');
                            trackLinePoints['<?php echo $row['HName'];?>'] =
                                new Array();
                        }

                        latlng = new google.maps.LatLng(lat, lng);

                        trackLinePoints['<?php echo $row['HName']; ?>'].push(
                            latlng);
                    }
                <?php

                    $i++;
                    }
                }
                ?>
                var hurricaneColorMap = markerSettings.getHurricaneToColorMap();
                var hurricaneToMarkersMap = 
                    markerSettings.getHurricaneToMarkersMap();

                jQuery.each(trackLinePoints, function() {
                    var hurricane = this;
                    var trackPointsList = 
                        markerSettings.getTrackPointsHurricanes();

                    jQuery.each(trackPointsList, function() {
                        if (this.localeCompare(hurricane) == 0) {
                            var path = trackLinePoints[hurricane];
                            var color = hurricaneColorMap[hurricane];
                            var hexColor = mapColor(color);
                            
                            var trackLine= new GPolylineWithArrows({
                                clickable: false,
                                draggable: false,
                                geodesic: true,
                                path: path,
                                strokeColor: hexColor,
                                strokeWeight: 3,
                                arrowStrokeColor: '#000000',
                                arrowStrokeOpacity: 1,
                                arrowStrokeWeight: 1,
                                fillColor: hexColor,
                                fillOpacity: 1,
                                arrowSize: 15,
                                middleArrow: true
                            });

                            markerSettings.addHurricaneToTrackLineMap(
                                hurricane, trackLine);
                        }

                    });
                });
        <?php
                }
            }
        }
        ?>
    }

    /*
    Polylines with arrows in Google Maps API v3
    by Pavel Zotov
    http://yab.hot-line.su/
    2011-02-19
    */
    GPolylineWithArrows = function( options ) {
        this.options = options;
        this.arrows = [];
    }

    GPolylineWithArrows.prototype = new google.maps.OverlayView();

    GPolylineWithArrows.prototype.onAdd = function() {
        this.polyline = new google.maps.Polyline({
            path: this.options.path,
            strokeColor: this.options.strokeColor,
            strokeWeight: this.options.strokeWeight,
            strokeOpacity: this.options.strokeOpacity
        });

        this.polyline.setMap(this.getMap());
    }

    GPolylineWithArrows.prototype.onRemove = function() {
        for(var i=this.arrows.length; i>0; i--){
            if (this.arrows[i - 1] != undefined) {
                this.arrows[i-1].setMap(null);

                delete this.arrows[i-1];

                this.arrows.length--;
            }
        }

        this.polyline.setMap(null);

        delete this.polyline;
    }

    GPolylineWithArrows.prototype.draw = function() {
        for( var i=this.arrows.length; i>0; i-- ){
            if (this.arrows[i - 1] != undefined) {
                this.arrows[i-1].setMap(null);

                delete this.arrows[i-1];

                this.arrows.length--;
            }
        }

        var prj = this.getProjection(), middle;

        for( var i=1; i<this.options.path.length; i++ ){
            var p1 = prj.fromLatLngToContainerPixel(this.options.path[i]); 
            var p0 = prj.fromLatLngToContainerPixel(this.options.path[i-1]);
            var vector = new google.maps.Point( p1.x - p0.x, p1.y - p0.y );
            var length = Math.sqrt(vector.x * vector.x + vector.y * vector.y);
            var normal = new google.maps.Point(vector.x / length, 
                                               vector.y / length);

            if (length > this.options.arrowSize && i % 3 == 0) {
                if (this.options.middleArrow) {
                    middle = new google.maps.Point((p1.x + p0.x) / 2, 
                                                   (p1.y + p0.y) / 2);
                } else {
                    middle = p1;
                }

                var offsetMiddle = new google.maps.Point(
                    normal.x * this.options.arrowSize, 
                    normal.y * this.options.arrowSize);

                var arrowPart1 = new google.maps.Point(-offsetMiddle.y * 0.4,
                                                       offsetMiddle.x * 0.4 );

                var arrowPart2 = new google.maps.Point(offsetMiddle.y * 0.4, 
                                                       -offsetMiddle.x * 0.4);

                var arrowPoint1 = new google.maps.Point(
                    middle.x - offsetMiddle.x + arrowPart1.x, 
                    middle.y - offsetMiddle.y + arrowPart1.y);

                var arrowPoint2 = new google.maps.Point(
                    middle.x - offsetMiddle.x + arrowPart2.x, 
                    middle.y - offsetMiddle.y + arrowPart2.y);

                this.arrows[i - 1] = new google.maps.Polygon({ 
                    map: this.getMap(),
                    path: [
                        prj.fromContainerPixelToLatLng(middle),
                        prj.fromContainerPixelToLatLng(arrowPoint1),
                        prj.fromContainerPixelToLatLng(arrowPoint2)],
                    fillColor: this.options.fillColor ? this.options.fillColor :
                               this.options.strokeColor,
                    fillOpacity: this.options.fillOpacity ? 
                                 this.options.fillOpacity : 
                                 this.options.strokeOpacity,
                    strokeColor: this.options.arrowStrokeColor ? 
                                 this.options.arrowStrokeColor : 
                                 this.options.strokeColor,
                    strokeOpacity: this.options.arrowStrokeOpacity ?
                                   this.options.arrowStrokeOpacity :
                                   this.options.strokeOpacity,
                    strokeWeight: this.options.arrowStrokeWeight ? 
                                  this.options.arrowStrokeWeight : 
                                  this.options.strokeWeight,
                    zIndex: 1
                });
            }
        }
    }

    function mapColor(color) {
        var mappedColor;

        switch (color) {
            case 'aqua':
                mappedColor = '#02f6fa'
                break;
            case 'blue':
                mappedColor = '#0044ff'
                break;
            case 'green':
                mappedColor = '#00ff15'
                break;
            case 'orange':
                mappedColor = '#ffab27'
                break;
            case 'pink':
                mappedColor = '#ff69fd'
                break;
            case 'purple':
                mappedColor = '#b300ff'
                break;
            case 'red':
                mappedColor = '#ff0000'
                break;
            case 'yellow':
                mappedColor = '#ddff00'
                break;
            case 'brown':
                mappedColor = '#4f2412'
                break;
            case 'dark_aqua':
                mappedColor = '#177f75'
                break;
            case 'dark_purple':
                mappedColor = '#6600cc'
                break;
            case 'grey':
                mappedColor = '#636563'
                break;
            case 'olive':
                mappedColor = '#566047'
                break;
            case 'white':
                mappedColor = '#ffffff'
                break;
            case 'lime':
                mappedColor = '#ccffcc'
                break;
        }
        
        return mappedColor;
    }
    
    /**
     * Creates the circle using the given radius on the Google Map.
     */
    function createCircle() {
        var radius           = <?php echo $radius; ?>;
        var markerSettings   = MarkerSettings.getInstance();
        var map              = markerSettings.getMap();
        var meterToMileScale = 1609;

        radius = radius * meterToMileScale;

        /*
         * The options that will be used to generate the circle.
         */
        var circleOptions = {
            center: latlng,
            fillColor: '#00ff33',
            fillOpacity: .1,
            strokeColor:'#00ff33',
            stokeOpacity: .3,
            map: map,
            radius: radius
        };

        var circle = new google.maps.Circle(circleOptions);

        google.maps.event.addListener(circle, 'click', function(event) {
            mapClicked(event);
        });

        circle.setMap(map);
    }

    /*
     * Adds the event listener to the marker so that when it is clicked it
     * will display the information window.
     *
     * @param mark   the mark to add the event listener to
     * @param latlng the latitude and longitude to place the information
     *               window on when it is clicked
     * @param win    the information window that is going to be displayed
     * @param map    the map where the marker and information window are
     */
    function addDisplayInfoEventListener(mark, latlng, win, map) {
        google.maps.event.addListener(mark, 'click', 
            /**
             * Sets the action that will be performed when the marker is
             * clicked.
             */
            function(){
                win.setPosition(latlng);
                win.setZIndex(1);
                win.open(map, mark);

                mark.setAnimation(google.maps.Animation.BOUNCE);
            }
        );
    }
    
    /*
     * Sets the action to stop the marker bouncing when its information
     * window has been closed.
     *
     * @param mark the marker that the information window is attached to
     * @param win  the information window that is displayed
     */
    function addStopBounceEventListener(mark, win) {
        google.maps.event.addListener(win, 'position_changed', 
            /*
             * Changes the animation to nothing (stops the bounce) when the
             * listener is activated.
             */
            function() {
                win.setZIndex(100);
                mark.setAnimation(null);
            }
        );
    }

    /*
     * Submits the form with the id of 'selection_form'.
     */
    function submitSelectionForm() {
        document.getElementById('selection_form').submit();
    }
    // -->
</script>
