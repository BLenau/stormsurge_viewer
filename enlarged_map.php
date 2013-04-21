<!--
    enlarged_map.php

    Russell Gaskey
    Cristina Korb
    Brian M. Lenau

    Chris Blades
    Brad Proctor
-->
<?php
    session_start();
    include_once("sql_functions.php");
    include("header.php");
?>
<link rel="stylesheet" href="scripts/css/enlarged_map_style.css"
      type="text/css" charset="utf-8" />
<script type="text/javascript" src="scripts/jquery/jquery-1.6.1.min.js">
</script>

<script type="text/javascript" src="scripts/js/browser_detect.js"></script>

<?php                                                                           
if (!isset($_SESSION['initial_load'])) {                                        
?>                                                                              
<script type="text/javascript">                                                 
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
</script>                                                                       
<?php                                                                           
$_SESSION['initial_load'] = true;                                               
}                                                                               
?>                                 

<script>                                                                       
</script>
                                                                                
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

<?php                                                                           
    if (isset($_POST['latlng']) || isset($_POST['hname'])) {                                              
?>                                                                              
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
     function addHurricaneToScrollbox(lat, lng, hName,                       
                                      hurricaneText) {                       
         var wasAdded = false;

         if (document.getElementById(hName) == null) {                       
             var meterToMileScale = 1609;                                    
                                                                             
             var origin = new google.maps.LatLng(<?php echo $lat; ?>,        
                                                 <?php echo $lng; ?>);       
             var latlng = new google.maps.LatLng(lat, lng);                  
<?php                                                                           
                if (isset($_POST['radius']) && $_POST['radius'] != "") {        
?>       
                    if ((google.maps.geometry.spherical.computeDistanceBetween(
                        latlng, origin) / meterToMileScale) <=
                        <?php echo $_POST['radius']; ?>) {
                        var list = document.getElementById('hurricanes');
                        var li   = document.createElement("li");
                        li.setAttribute('id', hName);

                        li.innerHTML = hurricaneText;
                        list.appendChild(li);
                        wasAdded = true;
                    }
<?php
                } else if (isset($_POST['hname'])) {
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
<?php
    }
?>

     // @See doumcnetation on jQuery (document).ready
    jQuery(document).ready(
        /*                                                                      
         * This function uses the CSS to set the tab of the current page        
         * (index.php here) to be opaque so that you can differentiate it from  
         * the other tabs. In addition, this function also initializes the      
         * default search radio buttons.                                        
         */
        function() {
            jQuery("#nav-home").attr("class", "active");

            if ("<?php echo $_POST['address']; ?>" != "") {
                addressSelected();                                              
            } else if ("<?php echo $_POST['hname']; ?>" != "") {                
                hnameSelected();                                                
            } else {
                document.getElementById("address-radio").checked=true;
            }
    });

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
        document.getElementById("address-radio").checked=true;                  
        document.getElementById("address").disabled=false;                      
        document.getElementById("radius").disabled=false;                       
        document.getElementById("hname").disabled=true;                         
        //document.getElementById("hname").value="";                            
    }                                                                           
       
   /*                                                                           
    * Enables the hurricane name field. Disables the address and radius text    
    * fields.                                                                   
    */                                                                         
    function hnameSelected() {
        document.getElementById("hname-radio").checked=true;                    
        document.getElementById("hname").disabled=false;                        
        document.getElementById("address").disabled=true;                       
        document.getElementById("radius").disabled=true;                        
        //document.getElementById("address").value="";                          
    }

  </script>

    <div class="body-container drop-shadow" style="height: 1050px;">
      <div id="content">
        <div class="section-title">
          <div class="content-left">
            <h2>Welcome to the Storm Surge Viewer</h2>
            <p>
              The Storm Surge Viewer allows you to locate past storm surge flood
              heights in your area with a simple search.
            </p>
          </div>
            <div class="content-right">
              <table id="logos-table" cols="3" align="center"
                     style="border-spacing: 0px 0px;" cellspacing="10px">
                <tr>
                  <td>                                                          
                    <a href="http://facebook.com/pages/Program-for-the-Study-of-Developed-Shorelines/178055929458"
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
        <div class="clear"></div>
        <hr class="section-divider"></hr>
          <table cols="2" style="border-spacing: 10px 5px;">
            <tr>
              <td valign="top">
                <h4>Options:</h4>
              </td>
              <td>
                <h4>
                  1) Click on the map to select a desired location
                  <br />
                  2) Manually enter an address or 
                  <br />
                  3) Enter a hurricane of interest
                </h4>
              </td>
            </tr>
          </table>
        <!-- 
            The form that is used to submit the information to the results
            page to run the queries and generate the Google Map
        -->
        <div id="index_selection_canvas" style="padding-top: 10px;">
          <form action="enlarged_map.php" method="POST" id="selection_form" 
                name="selection_form">
            <table id="search-table" cols="4" 
                   style="border-spacing: 20px 0px" cellspacing="10px">
              <tr>                                                              
                <td>                                                            
                  <label for="address-radio" >                                  
                  <input type="radio" id="address-radio"                        
                         name="search" onClick="addressSelected();"/>           
                    &nbsp; Address:                                             
                  </label>                                                      
                  <br />
                  <label for="hname-radio" >                                    
                  <input type="radio" id="hname-radio"                          
                         name="search" onClick="hnameSelected();" />            
                    &nbsp; Hurricane Name:                                      
                  </label>                                                      
                </td>                                                           
                <td>                                                            
                  <!--                                                          
                    The textbox for the "query" input, which is actually        
                    the address that was entered on the index page. It          
                    is populated with POST data that was submitted so           
                    that the user doesn't have to re-enter the same             
                    address.                                                    
                  -->                                                           
                  <input type="text" id="address" name="address"                
                         size="25"                                              
                         <?php                                                  
                             if (isset($_SESSION['address'])) {                 
                                 echo 'value="' . $_SESSION['address'] . '"';   
                             }                                                  
                         ?> />                                                  
                  <br />
                  <input type="text" id="hname" name="hname"                    
                         size="15" disabled=true                                
                         <?php                                                  
                         if (isset($_SESSION['hname'])) {                       
                             echo 'value="' . $_SESSION['hname'] . '"';         
                         }                                                      
                         ?> />                                                  
                </td>    <!-- end address cell-->                               
                <td>
                  Search radius (miles):                                      
                  <!--                                                          
                    The textbox for the "radius" input. If the user             
                    entered a radius, then the radius they entered is           
                    enter into the textbox. If no radius was entered,           
                    then the textbox is populated with 0.                       
                  -->                                                           
                  <input type="text" id="radius" name="radius" size="3"         
                         <?php                                                  
                             if (isset($_SESSION['radius']) &&
                                       $_SESSION['radius'] != "") {
                                 echo 'value="' . $_SESSION['radius'] . '"';    
                             } else {
                                 echo 'value="25"';
                             }
                         ?> />                                                  
                  <br />
                  <div style="visibility: hidden;">
                    <input type="text" id="latlng" name="latlng"
                    <?php 
                    if (isset($_POST['latlng'])) {
                        echo 'value="' . $_POST['latlng'] . '"';
                    }
                    ?> />
                  </div>
                </td>  <!-- end radius cell-->                                  
                <td>
                  <a href="images/how_to_use.png" style="z-index: 100;" 
                     rel="lightbox"><div class="how-to-use"></div></a>
                  <br />
                  <a href="images/definitions.jpg" 
                     rel="lightbox"><div class="definitions"></div></a>
                </td>
              </tr>  <!-- end search row-->                                     
              <tr>                                                              
                <td>                                                            
                </td>                                                           
                <td>                                                            
                </td>                                                           
                <td>
                </td>
              </tr>                                                             
              <tr>                                                              
                <td>                                                            
                  <!--                                                          
                    The button that is used to submit the form. When            
                    the submit button is clicked, the                           
                    submitSelectionForm() function is called.                   
                    (defined in results_scripts.php)                            
                  -->                                                           
                  <input type="submit" value="Submit"                           
                         onClick="return submitLatlng();" />                    
                </td>  <!-- end submit button cell-->                           
              </tr>                  
            </table>

            <div id="map-container">                                        
              <div id="map_canvas" class="enlarged-map-canvas"></div>
            </div>

            <center>
              <a href="index.php">Click here to reduce map</a>
            </center>

            <table id="search-table" cols="1" cellspacing=0px"
                   style="border-spacing: 20px 0px">
              <tr>
                <td>
                  <div class="section-title">
                    <h3>Choose Hurricane(s) and Options</h3>
                  </div>
                  <hr class="filter-divider"></hr>
                  <table id="selection-table" cols="3"
                         style="border-spacing: 10px 20px">
                    <tr>
                      <td> 
                        <div class=scrollbox-header>Hurricane</div>
                        <ul class="a" id="hurricanes">
                          <li class="a">  
                            <label id="all-label" class="a" for="All">
                              <table>
                                <tr>
                                  <td>
                                    <div id="all" style="display:none">
                                      <input type="checkbox" id="All" name="All"
                                             onCheck="checkAll(
                                               document.selection_form)"  
                                             onUnCheck="uncheckAll(
                                               document.selection_form)"
                                             onClick=" 
                                               if (this.checked) {
                                                   eval(this.getAttribute(
                                                            'onCheck'));    
                                               } else if (!this.checked) {
                                                   eval(this.getAttribute(
                                                            'onUnCheck'));  
                                               };" /> All
                                    </div>
                                    <table>
                                      <td>
                                        <div id="none-found" 
                                             style="display:none">      
                                          No hurricanes found
                                        </div>
                                      </td>
                                    </table>
                                  </td>  <!-- end All cell-->
                                </tr>  <!-- end All row-->
                              </table>  <!-- end All table-->
                            </label>  <!-- end All label-->
                            <hr class="scrollbox-divider"></hr>
                            <table>
<?php
if (isset($_POST['latlng']) || isset($_POST['hname'])) {
    $hurricaneList = array();                                                   

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
                          Where HName like '%" . $_POST['hname'] . "%'
                          Order By HName;";   
        }
                                                                                
    $hNameResult = mysql_query($sql_hname, $db);                                
    $num_hurricanes = 0;                                                        
                                                                                
    if (($_SESSION['address_selected'] != "" && $_POST['address'] != "" &&
        isset($_POST['latlng'])) ||       
        ($_SESSION['address_selected'] == "" && $_POST['hname'] != "")) { 
    while ($row = mysql_fetch_array($hNameResult, MYSQL_ASSOC)) {               
        $sql_latlng = "Select HName, HYear, Latitude, Longitude                 
                       From WaterMarks natural join Hurricane                   
                       Where Latitude >= " . ($lat - $dLat) . " and             
                             Latitude <= " . ($lat + $dLat) . " and             
                             Longitude >= " . ($lng - $dLng) . " and            
                             Longitude <= " . ($lng + $dLng) . " and            
                             HName = '" . $row['HName'] . "'                    
                       Order By HName;";                                        
        $latlngResult = mysql_query($sql_latlng, $db);                          
                                                                                
        if (mysql_num_rows($latlngResult) > 0) {                                
            while(($latlng_row = mysql_fetch_assoc($latlngResult)) &&           
                   !in_array($row['HName'], $hurricaneList)) {                  
?>                                                                              
    <script type="text/javascript">                                             
        var markerSettings = MarkerSettings.getInstance();                      
        var lat = <?php echo $latlng_row['Latitude']; ?>;                       
        var lng = <?php echo $latlng_row['Longitude']; ?>;                      
        var hurricaneText =                                                     
           "<label class=\"a\"" +                                               
                "for=\"<?php echo $row['HName']; ?>\">" +                       
           "<table>" +                                                          
           "<tr>" +                                                             
           "<td width=\"175px\">" +                                               
           "<input type=\"checkbox\"" +                                         
               "id=\"<?php echo $row['HName']; ?>\" " +                         
               "name=\"<?php echo $row['HName']; ?>\" " +                       
               "onClick=\"hurricaneClicked(this, document.selection_form);\"" + 
               "/><?php echo $row['HName'] . ' (' . $latlng_row['HYear'] . ')'; ?>" +
           "</td>" +                                                            
           "<td>" +                                                             
             "<div class=\"color-box\" id=\"<?php echo $row['HName']; ?>-color-box\">" +
             "</div>" +                                                         
           "</td>" +                                                            
           "</tr>" +                                                            
           "</table>" +                                                         
           "</label>";                                                          
             addHurricaneToScrollbox(lat, lng, '_<?php echo $row['HName']; ?>', 
                                     hurricaneText);                            
    </script>                           
<?php
            }
            
            $num_hurricanes = $num_hurricanes + 1;
        }
    }
    }
}
?>
                            </table>  <!-- end hurricanes table-->
                          </li>  <!-- end hurricanes li-->
                        </ul>  <!-- end hurricanes ul-->
                      </td>   <!-- end scrollbox cell in outer table-->
                      <td>
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
        jQuery('#water-marks').attr('disabled', false);
        jQuery('#hurricane-tp').attr('disabled', false);
        jQuery('#wm-text').attr('class', 'enabled-marker-type');
        jQuery('#tp-text').attr('class', 'enabled-marker-type');
        jQuery('#all-label').attr('class', 'a none-label-active');
        jQuery('#wm-label').attr('class', 'label-active');
        jQuery('#tp-label').attr('class', 'label-active');
        document.getElementById('all').style.display = 'inline';
</script>
<?php
    } else {
?>
<script type="text/javascript">
        document.getElementById('none-found').style.display = 'inline';
        jQuery('#all-label').attr('class', 'a none-label-inactive');
        jQuery('#wm-label').attr('class', 'label-inactive');
        jQuery('#tp-label').attr('class', 'label-inactive');
</script>
<?php
    } 
?>
                </table>  <!-- end selection table-->
              </tr>
            </table>
          </form>  
        </div>          
      </div>
    </div>
  </div>
</body>                                                                         
</html>
<script type="text/javascript">                                                 
    initialize();
</script>         
