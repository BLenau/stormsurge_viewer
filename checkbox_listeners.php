<!--
    checkbox_listeners.php

    Russell T. Gaskey
    Cristina Korb
    Brian M. Lenau
-->
<?php
    include("map_components.php");
?>

<script type="text/javascript">
    <!--
    var markerSettings = MarkerSettings.getInstance(); 

   /*
    *  Called when a hurricane checkbox is clicked and evaluates the specified 
    *  checkbox to determine if it is checked or unchecked and call approptiate
    *  functions based on evaluation.
    *
    *  Params:
    *    checkbox    - The checkbox of interest.
    *    form        - The html for in which the checkbox resides.
    */
    function hurricaneClicked(checkbox, form) {
        if (checkbox.checked === true) {
            hurricaneChecked(checkbox.id, form);
        } else {
            hurricaneUnchecked(checkbox.id, form);
        }
    }

   /*
    *  Called when the watermark checkbox is clicked and evaluates the specified
    *  checkbox to determine if it is checked or unchecked and call approptiate
    *  functions based on evaluation.
    *
    *  Params:
    *    checkbox    - The checkbox of interest.
    *    form        - The html for in which the checkbox resides.
    */
    function wmClicked(checkbox, form) {
        if (checkbox.checked === true) {
            wmChecked(form);
        } else {
            wmUnchecked(form);
        }
    }

   /*
    *  Called when the trackpoint checkbox is clicked and evaluates the 
    *  specified checkbox to determine if it is checked or unchecked and call 
    *  approptiate functions based on evaluation.
    *
    *  Params:
    *    checkbox    - The checkbox of interest.
    *    form        - The html for in which the checkbox resides.
    */
    function tpClicked(checkbox, form) {
        if (checkbox.checked === true) {
            tpChecked(form);
        } else {
            tpUnchecked(form);
        }
    }

    /*
     * This function is called when a specific hurricane checkbox is
     * checked and is responsible for determining if all checkboxes in the
     * scrollbox have been checked.  If so, the "All" checkbox is checked.
     * If not, it remains unchanged.
     * 
     * Parameters:
     *      hName - The name of the hurricane assosiated with this
     *              checkbox 
     *      form  - The form in which the scrollbox of checkboxes is  
     *              located. 
     */
    function hurricaneChecked(hName, form) {
        var map = markerSettings.getMap();

        form.All.checked = areAllChecked(form);
        
        markerSettings.addHurricaneSelection(hName);
        
        drawHurricaneSelectedMarkers(hName, map);
    }
    
    /*
     * This function is called when a specific hurricane checkbox is
     * unchecked and is responsible for unchecking the "All" checkbox.
     * 
     * Parameters:
     *      hName - The name of the hurricane assosiated with the current
     *              checkbox that has been selected.
     *      form  - The form in which the scrollbox of checkboxes is  
     *              located. 
     */
    function hurricaneUnchecked(hName, form) {
        form.All.checked = false;
        markerSettings.setAllChecked(false);

        markerSettings.removeHurricaneSelection(hName);

        drawHurricaneSelectedMarkers(hName, null);
    }

    /*
     * This function is called when the "All" checkbox is checked and is 
     * responsible for setting each checkbox's "checked" field to true 
     * (checking checkbox) for each checkbox in the scrollbox. 
     * 
     * Parameters:
     *      form - The form in which the scrollbox of checkboxes is

     * s.markerTypesSelected   = new Array();                               
             this.hurricaneToColorMap   = new Array();                               
                   located.
     */
    function checkAll(form) {
        var map = markerSettings.getMap();

        jQuery.each(form.elements, function() {
            if (this.type === 'checkbox' && this.id !== 'water-marks' &&
                this.id !== 'hurricane-tp' && this.id !== 'hurricane-lf') {
                
                this.checked = true;

                if (this.name !== 'All' && this.name !== '') {
                    markerSettings.addHurricaneSelection(this.name);

                    drawHurricaneSelectedMarkers(this.name, map);
                }
            }
        });
    }

    /*
     * This function is called when the "All" checkbox is unchecked and is
     * responsible for setting each checkbox's "checked" field to false 
     * (unchecking checkbox) for each checkbox in the scrollbox. 
     * 
     * Parameters:
     *      form - The form in which the scrollbox of checkboxes is
     *             located.
     */
    function uncheckAll(form) {
        jQuery.each(form.elements, function() {
            if (this.type === 'checkbox' &&
                this.id !== 'water-marks' &&
                this.id !== 'hurricane-tp' &&
                this.id !== 'hurricane-lf') {
                this.checked = false;

                if (this.name !== 'All' && this.name !== '') {
                    markerSettings.removeHurricaneSelection(this.name);

                    drawHurricaneSelectedMarkers(this.name, null);
                }
            }
        });

        markerSettings.setAllChecked(false);
    }

   /*
    *  Determines if all hurricanes in the hurricane scrollbox have been
    *  selected.  If so, the "All" checkbox will be checked.  If not, the 
    *  "All" checkbox will be unchecked.
    *
    *  Params:
    *      form    - The html form in which the checkboxes reside.
    */
    function areAllChecked(form) {
        var allChecked = true;
    
        if (markerSettings.getAllChecked() === false) { 
            jQuery.each(form.elements, function() {
                if (this.type === 'checkbox' &&
                    this.id !== 'water-marks' &&
                    this.id !== 'hurricane-tp' &&
                    this.id !== 'All' &&
                    this.id !== '') {
                    if (this.checked === false) {
                        allChecked = false;
                    }
                }
            });
        }

        markerSettings.setAllChecked(allChecked);

        return allChecked;
    }

   /*
    *  Draws all markers for all options selected on the specified map.  This
    *  includes all markers and tracklines that are selected for the specified
    *  hurricane.
    *
    *  Params:
    *      hName   - The hurricane to which all markers to be drawn are
    *                associated.
    *      map     - The map on which to draw all the markers and/or 
    *                tracklines
    */
    function drawHurricaneSelectedMarkers(hName, map) {
        var hurricaneToMarkersMap = markerSettings.getHurricaneToMarkersMap();
        var markerTypesSelected   = markerSettings.getMarkerTypesSelected();
        var hurricaneMarkTypes    = hurricaneToMarkersMap[hName];

        jQuery.each(markerTypesSelected, function() {
            var markerType = this;
            
            var markers = hurricaneMarkTypes[markerType];

            if (markers != undefined) {
                jQuery.each(markers, function() {
                    var marker = this;
                    if (marker != undefined) {
                        marker.setMap(map);
                    }
                });
            }
            
            if (markerType == 'trackpoints') {
                var trackLine = markerSettings.getTrackLine(hName);
                
                if (trackLine != undefined) {
                    trackLine.setMap(map);
                }
            }
        });
    }

    function wmChecked() {
        markerSettings.addMarkerTypeSelection('watermarks');

        var map = markerSettings.getMap();

        drawMarkTypeSelectedMarkers('watermarks', map);
    }

    function wmUnchecked() {
        markerSettings.removeMarkerTypeSelection('watermarks');

        drawMarkTypeSelectedMarkers('watermarks', null);
    }
  
    function tpChecked() {
        var map = markerSettings.getMap();

        markerSettings.addMarkerTypeSelection('trackpoints');

        drawMarkTypeSelectedMarkers('trackpoints', map);
    }

    function tpUnchecked() {
        markerSettings.removeMarkerTypeSelection('trackpoints');

        drawMarkTypeSelectedMarkers('trackpoints', null);
    }

   /*
    *  Draws all markers of the specified type for all hurricanes selected.
    *
    *  Params:
    *    markType  - The type of markers to be drawn.
    *    map       - The map on which to draw the markers.
    */
    function drawMarkTypeSelectedMarkers(markType, map) {
        var hurricaneToMarkersMap = markerSettings.getHurricaneToMarkersMap();
        var hurricanesSelected    = markerSettings.getHurricanesSelected();

        jQuery.each(hurricanesSelected, function() {
            var hurricane  = this;
            var markerTypes = hurricaneToMarkersMap[hurricane];

            if (markerTypes !== undefined) {
                var markers = markerTypes[markType];

                if (markers !== undefined) {
                    jQuery.each(markers, function() {
                        var marker = this;
                        
                        if (marker != undefined) {
                            marker.setMap(map);
                        }
                    });
                }
            }

            if (markType == 'trackpoints') {
                var trackLine = markerSettings.getTrackLine(hurricane);
                
                if (trackLine != undefined) {
                    trackLine.setMap(map);
                }
            }
        });
    }
    // -->
</script>
