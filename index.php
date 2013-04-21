<?php
    session_start();
    include_once("sql_functions.php");
    include("header.php");
?>

<link rel="stylesheet" href="scripts/css/index_style.css" type="text/css" 
      charset="utf-8" />

</script>

<script type="text/javascript" src="scripts/js/browser_detect.js"></script>

<?php
if (!isset($_SESSION['initial_load'])) {
?>
<script type="text/javascript">
<!--
    BrowserDetect.init();
    if (BrowserDetect.browser == "Explorer" && BrowserDetect.version < 9) {
        alert("You are not currently using the most recent version of Internet" +
              " Explorer. Please update to version 9 or higher.");
    }
    
    if (BrowserDetect.browser == "Firefox" && BrowserDetect.version < 4) {
        alert("You are not currently using the most recent version of Mozilla" +
              " Firefox. Please update to version 4 or higher.");
    }
    
    if (BrowserDetect.browser == "Chrome" && BrowserDetect.version < 9) {
        alert("You are not currently using the most recent version of Google" +
              " Chrome. Please update to version 9 or higher.");
    }
    
    if (!navigator.cookieEnabled) {
        alert("For the best experience at our site, we recommend you enable " +
              "cookies to preserve search data.");
    }
// -->
</script>
<?php
$_SESSION['initial_load'] = true;
}
?>

                                                                                
<?php
    /*
     * If the 'address_selected' flag isn't set, then default it to true
     */
    if (!isset($_SESSION['address_selected'])) {
        $_SESSION['address_selected'] = "true";
    }

    /*
     * If the address was submitted and the hname was not submitted, 
     * set the 'address_selected' flag to true.
     * If the address was not submitted and the hname was submitted,
     * set the 'address_selected' fllag to false.
     */
    if (isset($_POST['address']) && !isset($_POST['hname'])) {
        $_SESSION['address_selected'] = "true";
    } else if (isset($_POST['hname']) && !isset($_POST['address'])) {
        $_SESSION['address_selected'] = "";
    }

    /*
     * If the address wasn't submitted but the address was saved in the
     * session, then set the submitted address to the saved address.
     * If the address was submitted, then set the saved address to the 
     * submitted address.
     */
    if (!isset($_POST['address'])) {
        if ($_SESSION['address_selected'] == "true" && 
                isset($_SESSION['address']) && $_SESSION['address'] != "") {
            $_POST['address'] = $_SESSION['address'];
        } else {
            $_POST['address'] = "";
            $_SESSION['address'] = "";
        }
    } else {
        $_SESSION['address'] = $_POST['address'];
        $_SESSION['address_sellected'] = "true";
    }

    /*
     * If the hname wasn't submitted but the hname was saved in the
     * session, then set the submitted hname to the saved hname.
     * If the hname was submitted, then set the saved hname to the 
     * submitted hname.
     */
    if (!isset($_POST['hname'])) {
        if ($_SESSION['address_selected'] == "false" &&
                isset($_SESSION['hname']) && $_SESSION['hname'] != "") {
            $_POST['hname'] = $_SESSION['hname'];
        } else {
            $_POST['hname'] = "";
            $_SESSION['hname'] = "";
        }
    } else {
        $_SESSION['hname'] = $_POST['hname'];
        $_SESSION['address_sellected'] = "false";
    }

    /*
     * If the radius wasn't submitted but the radius was saved in the
     * session, then set the submitted radius to the saved radius.
     * If the radius was submitted, then set the saved radius to the 
     * submitted radius.
     */
    if (!isset($_POST['radius'])) {
        if (isset($_SESSION['radius']) && $_SESSION['radius'] != "") {
            $_POST['radius'] = $_SESSION['radius'];
        } else {
            $_POST['radius'] = "";
            $_SESSION['radius'] = "";
        }
    } else {
        $_SESSION['radius'] = $_POST['radius'];
    }
    
    include("checkbox_listeners.php");
    include("nav_bar.php");

    $num_hurricanes = 0;
?>

<script type="text/javascript">
<!--

   /*
    * Add the specified hurricane name to the Hurricane scrollbox if the 
    * scrollbox does not already contain it and if the specified latitude
    * and longitude lie within the search radius of the specified origin.
    *
    * Params:
    *   lat           - The latitude of the current watermark associated 
    *                   with the current hurricane.
    *   lng           - The longitude of the current watermark associated 
    *                   with the current hurricane.
    *   hName         - The name of the current hurricane.
    *   hurricaneText - The specification of the format for the html for 
    *                   the current hurricane.
    */
    function addHurricaneToScrollbox(lat, lng, hName, hurricaneText) {                       
        var wasAdded = false;

        if (document.getElementById(hName) == null) {                       
            var meterToMileScale = 1609;                                    
            var latlong = new google.maps.LatLng(lat, lng);                  
            var origin = latlng;
            <?php                                                                           
            if (isset($_POST['address']) && $_POST['address'] != "") {
            ?>                                                                              
                if ((google.maps.geometry.spherical.computeDistanceBetween(
                        latlong, origin) / meterToMileScale) <=
                        <?php echo $_POST['radius']; ?>) {
                    var list = document.getElementById('hurricanes');
                    var li   = document.createElement("li");
                    li.setAttribute('id', hName);
  
                    li.innerHTML = hurricaneText;
                    list.appendChild(li);
                    wasAdded = true;
                }
            <?php
            } else if (isset($_POST['hname']) && $_POST['hname'] != "") {
            ?>
                var list = document.getElementById('hurricanes');
                var li   = document.createElement("li");
                li.setAttribute('id', hName);
  
                li.innerHTML = hurricaneText;
                list.appendChild(li);
                wasAdded = true;
            <?php
            }
            ?>
        }
        
        return wasAdded;
    }                                                                       

    /*
     * Puts the information about the hurricane in an information window and
     * then displays the information window in the middle of the map.
     * Params:
     *   infoText - The text to put in the information window.
     */
    function openLfMark(lfMarker, infoText) {
        google.maps.event.clearListeners(lfMarker, 'click');
        lfMarker.setMap(myMap);
        google.maps.event.addListener(lfMarker, 'click', function() {
            myMap.setCenter(lfMarker.getPosition());
            infoWindow.setContent(infoText);
            infoWindow.setPosition(lfMarker.getPosition());
            infoWindow.open(myMap, lfMarker);
            lfMarker.setAnimation(google.maps.Animation.BOUNCE);
        });
        google.maps.event.trigger(lfMarker, 'click');

        google.maps.event.clearListeners(infoWindow, 'position_changed');
        google.maps.event.addListener(infoWindow, 'position_changed', function() {
            lfMarker.setAnimation(null);
            infoWindow.close();
            lfMarker.setMap(null);
        });
        
        return false;
    }

     // @See documentation on jQuery (document).ready
    jQuery(document).ready(
        function() {
            jQuery("#nav-home").attr("class", "active");
            
            <?php
            if (isset($_POST['address']) && $_POST['address'] != "") {
            ?>
                addressSelected();
            <?php
            } else if (isset($_POST['hname']) && $_POST['hname'] != "") {
            ?>
                hnameSelected();
            <?php
            } else {
            ?>
                document.getElementById("address-radio").checked=true;
            <?php
            }
            ?>
        }
    );

    /*
     * The function that will be called when the submit button is pressed.
     * It will geocode the address so that it doesn't need to be done after the
     * page has loaded.
     */
    function submitLatlng() {
        if (document.getElementById('address-radio').checked) {
            var address = document.getElementById('address').value;
            var geocoder = new google.maps.Geocoder();
            var regex = /[(]*[-]*\d+.\d+,[ ]*[-]*\d+.\d+[)]*/;

            if (address.match(regex)) {
                regex = /[() ]*/g;
                address = address.replace(regex, "");
                document.getElementById('latlng').value = address;
                
                return true;
            } else {
                geocoder.geocode({'address': address}, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        latlng = results[0].geometry.location;
                        regex = /[() ]*/g;
                        latlng = latlng.toString().replace(regex, "");
                        document.getElementById('latlng').value = latlng.toString();
                        submitSelectionForm();
                    } else {
                        alert("Please enter a valid address.");
                    }
                });
                return false;
            }
        } else {
            document.getElementById('latlng').value = "31.1497222,-81.4916667";
            return true;
        }
    }


   /*
    * Enables the address text field and radius text field. Disables the
    * hurricane name text field.
    */
    function addressSelected() {
        document.getElementById("address-radio").checked = true;
        document.getElementById("address").disabled = false;
        document.getElementById("radius").disabled = false;
        document.getElementById("hname").disabled = true;
        document.getElementById("hname").value = "";
    }

   /*
    * Enables the hurricane name field. Disables the address and radius text
    * fields.
    */
    function hnameSelected() {
        document.getElementById("hname-radio").checked = true;
        document.getElementById("hname").disabled = false;
        document.getElementById("address").disabled = true;
        document.getElementById("radius").disabled = true;
        document.getElementById("address").value = "";
    }

// -->
  </script>

    <div class="body-container drop-shadow" style="height: 728px;">
      <div id="content">
        <div class="section-title">
          <div class="content-left">
            <img src="images/banner.png" style="position: relative; top: -10px;" /> 
          </div>
          <div class="content-index-right">
            <table id="logos-table" cols="3" align="center"
                   style="border-spacing: 0px 0px" cellspacing="10px">
              <tr>
                <td>
                  <a href="http://www.facebook.com/pages/Program-for-the-Study-of-Developed-Shorelines/178055929458"
                     target="new_"><div class="facebook-logo"></div></a>
                </td>
                <!--
                <td>
                  <a href="http://psds.wcu.edu"
                     target="_new"><div class="psds-logo"></div></a>
                </td>
                -->
                <td>
                  <a href="http://www.wcu.edu"
                     target="new"><div class="wcu-logo"></div></a>
                </td>
              </tr>
            </table>
          </div>
        </div> <!-- end section-title-->
        <div class="clear"><div>
          <hr class="section-divider"></hr>
        <!-- 
            The form that is used to submit the information to the results
            page to run the queries and generate the Google Map
        -->
        <div id="index_selection_canvas">
          <form action="." method="POST" id="selection_form" 
                name="selection_form">
            <table cols="5" style="margin-top: -20px; margin-left: -10px;">
              <tr>
                <td valign="top">
                  <table id="search-table" cols="2" 
                         style="border-spacing: 20px 0px">
                    <tr>
                      <td>
                        <label for="address-radio" >
                        <input type="radio" id="address-radio"
                               name="search" onClick="addressSelected();"/>
                          &nbsp; Address:</label>
                      </td>
                      <td>
                        <input type="text" id="address" name="address"   
                               size="25" 
                               <?php 
                                   if (isset($_SESSION['address'])) {
                                       echo 'value="' . $_SESSION['address'] . '"';
                                   } 
                               ?> />
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label for="hname-radio" >
                        <input type="radio" id="hname-radio" 
                               name="search" onClick="hnameSelected();" />
                          &nbsp; Hurricane Name:</label>
                      </td>
                      <td>
                        <input type="text" id="hname" name="hname" 
                               size="15" disabled=true
                               <?php
                               if (isset($_SESSION['hname'])) {
                                   echo 'value="' . $_SESSION['hname'] . '"';
                               }
                               ?> />
                      </td>
                    </tr>
                    <tr>
                      <td>
                        Search radius (miles):
                      </td>
                      <td>
                        <input type="text" id="radius" name="radius" size="3" 
                               <?php 
                                   if (isset($_SESSION['radius']) &&
                                             $_SESSION['radius'] != "") {
                                       echo 'value="' . $_SESSION['radius'] . '"'; 
                                   } else {
                                       echo 'value="25"';
                                   }
                               ?> />
                      </td>  <!-- end radius cell-->
                    </tr>
                    <tr>
                      <td>
                        <input type="submit" value="Submit" 
                               onClick="return submitLatlng();" />
                      </td>  <!-- end submit button cell-->
                    </tr>
                    <tr>
                      <td>
                        <div style="visibility: hidden">
                          <input type="text" id="latlng" name="latlng" size="5"
                                 <?php
                                 if (isset($_POST['latlng'])) {
                                     echo 'value="' . $_POST['latlng'] . '"';
                                 }
                                 ?> />
                        </div>
                      </td>
                    </tr>
                  </table>
                </td>
                <td valign="top">
                  <ul class="a" id="hurricanes">
                    <li class="a">
                      <table style="margin-bottom: -14px; margin-top: -2px;">
                        <tr>
                          <td>
                            <label id="all-label" class="a" for="All">
                              <div id="all" style="display:none">
                                <input type="checkbox" id="All" name="All"
                                 onCheck="checkAll(document.selection_form)"
                                 onUnCheck="uncheckAll(
                                     document.selection_form)"
                                 onClick="
                                   if (this.checked) {
                                       eval(this.getAttribute('onCheck'));
                                   } else if (!this.checked) {
                                       eval(this.getAttribute('onUnCheck'));
                                   };" /> All
                              </div>
                            </label>  <!-- end All label-->
                            <table>
                              <td>
                                <div id="none-found" style="display:none">
                                  No hurricanes found
                                </div>
                              </td>
                            </table>
                          </td>  <!-- end All cell-->
                        </tr>  <!-- end All row-->
                      </table>  <!-- end All table-->
                      <hr class="scrollbox-divider"></hr>
                      <table>
<?php
if ((isset($_POST['address']) && $_POST['address'] != "") || 
        (isset($_POST['hname']) && $_POST['hname'] != "")) {
    
    if (isset($_POST['address']) && $_POST['address'] != "") {
        $sql_hname = "Select Distinct HName
                      From WaterMarks natural join Hurricane 
                      Where Latitude >= " . ($lat - $dLat) . " and
                            Latitude <= " . ($lat + $dLat) . " and
                            Longitude >= " . ($lng - $dLng) . " and
                            Longitude <= " . ($lng + $dLng) . " 
                      Order By HName;";
    } else {
        $sql_hname = "Select Distinct HName
                      From Hurricane natural join WaterMarks
                      Where HName Like '%" . $_POST['hname'] . "%'
                      Order By HName;";
    }

    $hNameResult = mysql_query($sql_hname, $db);

    $num_hurricanes = 0;

    if (($_SESSION['address_selected'] != "" && $_POST['address'] != "" &&
            isset($_POST['latlng'])) || 
            ($_SESSION['address_selected'] == "" && $_POST['hname'] != "")) {
        $hurricane_name_sql = "Select HID from Hurricane;";
        $hurricane_name_result = mysql_query($hurricane_name_sql, $db);
        $lf_sql = "Select HID, LFState, LFWind, LFPres, Cat_NOAA, Latitude, Longitude"
                . " FROM HurricaneLandfall;";
        $lf_result = mysql_query($lf_sql, $db);

        $lf_marks = array();
        $lf_info = array();
        while ($hurricane_name_row = mysql_fetch_assoc($hurricane_name_result)) {
            $lf_info[$hurricane_name_row['HID']] = null;
        }
        $i = 0;
        $lf_result = mysql_query($lf_sql, $db);
        while ($lf_row = mysql_fetch_assoc($lf_result)) {
            $lf_marks[$i] = $lf_row['HID'];
            if ($lf_info[$lf_row['HID']] == null) {
                $lf_info[$lf_row['HID']] = array();
                $lf_info[$lf_row['HID']][0] = 1;
                $lf_info[$lf_row['HID']][$lf_info[$lf_row['HID']][0]] = $lf_row;
                $lf_info[$lf_row['HID']][0]++;
            } else {
                $lf_info[$lf_row['HID']][$lf_info[$lf_row['HID']][0]] = $lf_row;
                $lf_info[$lf_row['HID']][0]++;
            }
            $i++;
        }

        $references = organize_rows("Source_References", "RefID", $db);
        /*
         * The references are in the form 
         * 'REFID-REFLINKNAME-#REFLINK#' so these have to be split
         * up into usable forms.
         */
        foreach ($references as $source) {
            $ref_link = $source['RefLink'];
            if (!is_null($ref_link)) {
                $ref_array = explode("#", $ref_link);
                if (count($ref_array) >= 1 && count($ref_array[0]) >= 1) {
                    if (strlen($ref_array[0]) == 0) {
                        $references[$source['RefID']]['RefLink'] = $ref_array[1];
                    } else {
                        $references[$source['RefID']]['RefLink'] = 'pdfs/' . $ref_array[0];
                    }
                }
            }
        }
        while ($row = mysql_fetch_array($hNameResult, MYSQL_ASSOC)) {
            $sql_latlng = "Select HID, HYear, Latitude, Longitude, RefID 
                           From WaterMarks natural join Hurricane 
                           Where Latitude >= " . ($lat - $dLat) . " and
                                 Latitude <= " . ($lat + $dLat) . " and
                                 Longitude >= " . ($lng - $dLng) . " and
                                 Longitude <= " . ($lng + $dLng) . " and
                                 HName = '" . $row['HName'] . "'
                           Order By HName;";
            $latlngResult = mysql_query($sql_latlng, $db);
           
            if (mysql_num_rows($latlngResult) > 0) {
                $new_row = mysql_fetch_assoc($latlngResult);
                $might_be_null = $references[$new_row['RefID']]['RefLink'];
                if (!is_null($might_be_null)) {
                    if (!(substr($might_be_null, strlen($might_be_null) - 1) == '/')) {
                        $might_be_null .= '.pdf';
                    }
                } else {
                    $might_be_null = "";
                }
            ?>
    <script type="text/javascript">
    <!--
        var hurricaneList = Array();
    // -->
    </script>
                <?php
                $latlng_row  = $new_row;
                $info_text     = "";
                $j = 0;
                $lf_found = false;
                while ($j < $i && !$lf_found) {
                    if ($lf_marks[$j] == $latlng_row['HID']) {
                        $lf_found = true;
                    }
                    $j++;
                }
                if (is_null($lf_info[$latlng_row['HID']][1]['Latitude'])) {
                    $lf_found = false;
                }

                if ($lf_found) {
                    $max_sql   = "Select MAX(MeasElev) as MaxMeasElev from WaterMarks "
                               . "where HID = " . $latlng_row['HID'] . ";";
                    $max_result = mysql_query($max_sql, $db);
                    $max_info_row = "";

                    if (mysql_num_rows($max_result) > 0) {
                        $max_row = mysql_fetch_assoc($max_result);
                        $max_info_row = "Max surge (ft):"
                                      . "</font></td><td><font size='2pt'>"
                                      . $max_row['MaxMeasElev']
                                      . "</font></td></tr><tr><td><font size='2pt'>";
                    }
                    $lf_window = "true";
                    $start_table   = "<div style='width: 285px; height: 165px;'>"
                                   . "<table cols='1'><tr><td><font size='2pt'>";
                    $hname_row     = $row['HName'] . " (" . $latlng_row['HYear']
                                   . ")<hr style='width: 240px;'>"
                                   . "</font></td></tr><tr><td><table cols='2'><tr><td><font size='2pt'>";
                    $lf_rows       = "";
                    $end_table     = "</table></td></tr></table>This is the landfall point.</div>";

                    $num_rows = $lf_info[$latlng_row['HID']][0];

                    $lf_cat_row    = "Landfall Category (Saffir-Simpson):"
                                   . "</font></td><td><font size='2pt'>"
                                   . $lf_info[$latlng_row['HID']][1]['Cat_NOAA']
                                   . "</font></td></tr><tr><td><font size='2pt'>";
                    $lf_wind_row   = "Landfall Windspeed (10-min, knots):"
                                   . "</font></td><td><font size='2pt'>"
                                   . $lf_info[$latlng_row['HID']][1]['LFWind']
                                   . "</font></td></tr><tr><td><font size='2pt'>";
                    $lf_pres_row   = "Landfall Pressure (mb):"
                                   . "</font></td><td><font size='2pt'>"
                                   . $lf_info[$latlng_row['HID']][1]['LFPres']
                                   . "</td></tr>";
                    $lf_state_row  = "Landfall State(s):</font></td><td><font size='2pt'>";
                    $k = 1;
                    while ($k < $num_rows) {
                        $lf_state_row = $lf_state_row
                                      . $lf_info[$latlng_row['HID']][$k]['LFState'];
                        if ($k != $num_rows - 1) {
                            $lf_state_row .= ", ";
                        }
                        $k++;
                    }
                    $lf_state_row = $lf_state_row . "</font></td></tr><tr><td><font size='2pt'>";
                    $lf_rows = $lf_state_row . $lf_cat_row . $lf_wind_row
                             . $lf_pres_row;
                    $info_text = $start_table . $hname_row . $max_info_row
                               . $lf_rows . $end_table;

                } else {
                    $lf_window = "false";
                }
                $h_name = str_replace(" ", "", $row['HName']);
            ?>
    <script type="text/javascript">
    <!--
        var hName = '<?php echo $h_name; ?>';
        if (jQuery.inArray(hName, hurricaneList) < 0) {
            var markerSettings = MarkerSettings.getInstance();
            var lat = <?php echo $latlng_row['Latitude']; ?>; 
            var lng = <?php echo $latlng_row['Longitude']; ?>; 
            var mightBeNull = "<?php echo $might_be_null; ?>";
            var startTable = "<table style=\"margin-top: -4px; "
                           + "margin-bottom: -4px\"><tr><td width=\"170px\">";
            var startLabel = "<label class=\"a\" for=\""
                           + "<?php echo $h_name; ?>\">";
            var input      = "<input type=\"checkbox\" id=\""
                           + "<?php echo $h_name; ?>\" "
                           + "name=\"<?php echo $h_name; ?>\" "
                           + "onClick=\"hurricaneClicked(this, "
                           + "document.selection_form);\"/>";
            var inputText  = "<?php echo $h_name; ?> "
                           + "(<?php echo $latlng_row['HYear']; ?>)";
            var endColumn1 = "</label></td>";
            var column2    = "<td><div class=\"color-box\" id=\""
                           + "<?php echo $h_name; ?>-color-box\"></div></td>";
            var endTable   = "</tr></table>";

            var infoText<?php echo $h_name ?> = "<?php echo $info_text; ?>";
            var column3    = "";
            if ("<?php echo $lf_window; ?>" == "true") {
                <?php
                if ($lf_found) {
                ?>
                var lfLat = <?php echo $lf_info[$latlng_row['HID']][1]['Latitude']; ?>;
                var lfLng = <?php echo $lf_info[$latlng_row['HID']][1]['Longitude']; ?>;
                var origin = new google.maps.LatLng(lfLat, lfLng);
                var radius = 100000;
                
                var lfPoint<?php echo $h_name; ?> = createLfMarker(lfLat, lfLng,
                                             radius, hName, origin,
                                             "watermark",
                                             infoText<?php echo $h_name; ?>);
                column3    = "<td width=\"60px\"><a href=\"#\""
                           + "onClick=\"return openLfMark(lfPoint<?php echo $h_name; ?>, infoText<?php echo $h_name; ?>)\">"
                           + "Info</a></td>";
                <?php
                }
                ?>
            } else {
                column3    = "<td width=\"70px\"></td>";
            }
            
            var hurricaneText = startTable + startLabel + input + inputText
                              + endColumn1 + column2 + column3 + endTable;
            <?php
            do { 
            ?>
            lat = <?php echo $new_row['Latitude']; ?>;
            lng = <?php echo $new_row['Longitude']; ?>;
            if (addHurricaneToScrollbox(lat, lng, 
                                        '_<?php echo str_replace(" ", "", $row['HName']); ?>',
                                        hurricaneText)) {
                hurricaneList.push(hName);
            }
            <?php
            } while ($new_row = mysql_fetch_assoc($latlngResult));
            ?>
        }
    // -->
    </script>
<?php
                
                $num_hurricanes = $num_hurricanes + 1;
            }
        }
    }
}
?>
                      </table>  <!-- end hurricanes table-->
                    </li>  <!-- end hurricanes li-->
                  </ul>  <!-- end hurricanes ul-->
                </td>
                <td valign="top">
                  <table>
                    <tr>
                      <td style="padding-top: 10px;">
                         <!-- 
                             The checkbox for the hurricane watermarks. It 
                             contains all the logic for when the checkbox is 
                             checked and unchecked.
                         -->
                        <div id="wm-text" class="disabled-marker-type">
                          <input type="checkbox" id="water-marks" 
                                 name="water-marks" class="enabled-marker-type"
                                 onCheck="wmChecked();"
                                 onUnCheck="wmUnchecked();"
                                 onClick="
                                   if (this.checked) {
                                       eval(this.getAttribute('onCheck'));
                                   } else if (!this.checked) {
                                       eval(this.getAttribute('onUnCheck'));
                                   };" DISABLED/>
                          <label id="wm-label" for="water-marks">
                            View Surge/High Water Marks
                          </label>
                        </div>
                      </td>  
                    </tr>
                    <tr>
                      <td>  
                        <!-- 
                            The checkbox for the hurricane track points. It 
                            contains all the logic for when the checkbox is 
                            checked and unchecked.
                        -->
                        <div id="tp-text" class="disabled-marker-type">
                          <input type="checkbox" id="hurricane-tp" 
                                 name="hurricane-tp"
                                 onCheck="tpChecked();"
                                 onUnCheck="tpUnchecked();"
                                 onClick="
                                   if (this.checked) {
                                       eval(this.getAttribute('onCheck'));
                                   } else if (!this.checked) {
                                       eval(this.getAttribute('onUnCheck'));
                                   };" DISABLED/>
                          <label id="tp-label" for="hurricane-tp">
                              View Hurricane Path
                          </label>
                        </div>
                      </td>  <!-- end trackpoints cell-->
                    </tr>  <!-- end trackpoints row-->
<?php
   /*
    * If there is hurricane data to be displayed make the selection options 
    * active, else disable the selection options.
    */
    if ($num_hurricanes > 0) {
?>
<script type="text/javascript">
    <!--
        jQuery('#water-marks').attr('disabled', false);
        jQuery('#hurricane-tp').attr('disabled', false);
        jQuery('#wm-text').attr('class', 'enabled-marker-type');
        jQuery('#tp-text').attr('class', 'enabled-marker-type');
        jQuery('#all-label').attr('class', 'a none-label-active');
        jQuery('#wm-label').attr('class', 'label-active');
        jQuery('#tp-label').attr('class', 'label-active');
        document.getElementById('all').style.display = 'inline';
    // -->
</script>
<?php
    } else {
?>
<script type="text/javascript">
    <!--
        document.getElementById('none-found').style.display = 'inline';
        jQuery('#all-label').attr('class', 'a none-label-inactive');
        jQuery('#wm-label').attr('class', 'label-inactive');
        jQuery('#tp-label').attr('class', 'label-inactive');
    // -->
</script>
<?php
    } 
?>
                    <tr>
                      <!--
                      <td style="position: relative; top: 62px; right: -10px;">
                      -->
                      <td>
                        <a href="images/how_to_use.png"
                           rel="lightbox"><div class="how-to-use"></div></a>                      
                        <a href="images/definitions.jpg"                                          
                           rel="lightbox"><div class="definitions"></div></a>
                      </td>
                    </tr>
                  </table>  <!-- end selection table-->
                </td>
              </tr>
            </table>
          </form>  
          <div id="new-map-container">
            <table id="map-table" cols="1" width="660px"
                   style="border-spacing: 10px 15px;"> 
              <tr>
                <td>
                  <div id="map_canvas"></div>
                </td>
              </tr>
            </table>
          </div>          
        </div>          
      </div>
    </div>
  </div>
</body>
</html>
<script type="text/javascript">
    <!--
    initialize();
    // -->
</script>
