<script type="text/javascript">
   <!--
   /*
    * Singleton to hold the state of the selection options.
    */
    function MarkerSettings() {
        this.hurricaneToMarkersMap   = new Array();
        this.hurricanesSelected      = new Array();
        this.markerTypesSelected     = new Array();
        this.hurricaneToColorMap     = new Array();
        this.hurricaneToTrackLineMap = new Array();
        this.trackPointsHurricanes   = new Array();
        this.areAllChecked           = false;
        this.map                     = null;
    }

    MarkerSettings.instance = null;

    MarkerSettings.getInstance = function() {
        if (MarkerSettings.instance === null) {
            MarkerSettings.instance = new MarkerSettings();
        }

        return MarkerSettings.instance;
    }
    
    MarkerSettings.prototype.mapMarkerToHurricane = 
        function(hurricane, markerType, marker) {
            if (jQuery.inArray(hurricane, this.hurricaneToMarkersMap) < 0) {
                this.hurricaneToMarkersMap.push(hurricane);
                this.hurricaneToMarkersMap[hurricane] = new Array();
            }

            if (jQuery.inArray(markerType,
                          this.hurricaneToMarkersMap[hurricane]) < 0) {
                this.hurricaneToMarkersMap[hurricane].push(markerType);
                this.hurricaneToMarkersMap[hurricane][markerType] = new Array();
            }

            this.hurricaneToMarkersMap[hurricane][markerType].push(marker);
            this.hurricaneToMarkersMap[hurricane][markerType][marker] = marker;
        }

    MarkerSettings.prototype.getHurricaneToMarkersMap = function() {
        return this.hurricaneToMarkersMap;
    }

    MarkerSettings.prototype.addHurricaneSelection = function(hurricane) {
        this.hurricanesSelected.push(hurricane);
    }

    MarkerSettings.prototype.removeHurricaneSelection = function(hurricane) {
        this.hurricanesSelected = jQuery.grep(this.hurricanesSelected, 
            function(val) {
                return val !== hurricane;
            });
    }

    MarkerSettings.prototype.getHurricanesSelected = function() {
        return this.hurricanesSelected;
    }

    MarkerSettings.prototype.addMarkerTypeSelection = function(markerType) {
        this.markerTypesSelected.push(markerType);
    }

    MarkerSettings.prototype.removeMarkerTypeSelection = function(markerType) {
        this.markerTypesSelected = 
            jQuery.grep(this.markerTypesSelected, function(val) {
                return val !== markerType;
            });
    }
    
    MarkerSettings.prototype.getMarkerTypesSelected = function() {
        return this.markerTypesSelected;
    }

    MarkerSettings.prototype.addHurricaneToColorToMap = 
        function(hurricane, color) {
            this.hurricaneToColorMap.push(hurricane);
            this.hurricaneToColorMap[hurricane] = color;
    }

    MarkerSettings.prototype.addTrackPointsHurricane = function(hurricane) {
        this.trackPointsHurricanes.push(hurricane);
    }

    MarkerSettings.prototype.getTrackPointsHurricanes = function(hurricane) {
        return this.trackPointsHurricanes;
    }

    MarkerSettings.prototype.addHurricaneToTrackLineMap = 
        function(hurricane, trackLine) {
            if (jQuery.inArray(hurricane, this.hurricaneToTrackLineMap) < 0) {
                this.hurricaneToTrackLineMap.push(hurricane);
            }

            this.hurricaneToTrackLineMap[hurricane] = trackLine;
            //alert("trackline " + this.hurricaneToTrackLineMap[hurricane]);
        }
    
    MarkerSettings.prototype.getTrackLine = function(hurricane) {
        return this.hurricaneToTrackLineMap[hurricane];
    }
    
    MarkerSettings.prototype.getHurricaneToColorMap = function() {
        return this.hurricaneToColorMap;
    }

    MarkerSettings.prototype.setMap = function(map) {
        this.map = map;
    }

    MarkerSettings.prototype.getMap = function() {
        return this.map;
    }
    
    MarkerSettings.prototype.setAllChecked = function(allChecked) {
        this.areAllChecked = allChecked;
    }

    MarkerSettings.prototype.getAllChecked = function() {
        return this.areAllChecked;
    }
    // -->
</script>
