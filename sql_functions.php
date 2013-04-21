<?php
   /*
    * This file contains the sqp functions that are used in querying the
    * database on the website.
    */

   /*
    * Formulate a MySQL query for hurricane information based upon the search 
    * parameters specified.
    * 
    * Params:
    *     table_name  - The table to be joined with Hurricane to query.
    *     lat         - The latitude of the center of the search area (origin).
    *     lng         - The longitude of the center of the search area (origin).
    *     dLat        - The difference in latitude from the origin used to 
    *                   define a search radius.
    *     dLng        - The difference in longitude from the origin used to 
    *                   define a search radius.
    *     db          - The database to be queried.
    */
    function get_points($table_name, $lat, $lng, $dLat, $dLng, $db) {           
        if ($table_name == "WaterMarks") {
            $sql = "Select * 
                    From " . $table_name . " natural join Hurricane
                    Where Latitude >= " . ($lat - $dLat) . " and
                          Latitude <= " . ($lat + $dLat) . " and
                          Longitude >= " . ($lng - $dLng) . " and
                          Longitude <= " . ($lng + $dLng) . ";";
        } else {
            $sql = "Select *
                    From " . $table_name . " natural join Hurricane
                    Where Exists(
                        Select HName                           
                        From WaterMarks natural join Hurricane
                        Where Latitude >= " . ($lat - $dLat) . " and
                              Latitude <= " . ($lat + $dLat) . " and
                              Longitude >= " . ($lng - $dLng) . " and
                              Longitude <= " . ($lng + $dLng) . "
                        Order By HName)
                    Order By Date_Time;";
        }
                                                                                
        return mysql_query($sql, $db);                                          
    }

    /*
     * Extracts and sorts the given table by the given id.
     * 
     * Params:
     *     table_name - the table that will be queried
     *     id         - the id that will be used to sort the results
     *     db         - the database that hold the table to be queried
     */
    function organize_rows_clause($table_name, $id, $db, $where, $determiner) {
        $query_rows = array();
        if (is_numeric($determiner)) {
            $sql          =   "Select * from " . $table_name . " where $where "
                            . "= $determiner;";
            $query_result = mysql_query($sql, $db);
        } else {
            $sql          =   "Select * from " . $table_name . " where $where "
                            . "= '$determiner';";
            $query_result = mysql_query($sql, $db);
        }

        while ($row = mysql_fetch_assoc($query_result)) {
            $query_rows[$row[$id]] = $row;
        }

        return $query_rows;
    }

    /*
     * Extracts and sorts the given table by the given id.
     * 
     * Params:
     *     table_name - the table that will be queried
     *     id         - the id that will be used to sort the results
     *     db         - the database that hold the table to be queried
     */
    function organize_rows($table_name, $id, $db) {
        $sql          = "Select * from " . $table_name . ";";
        $query_result = mysql_query($sql, $db);
        $query_rows   = array();

        while ($row = mysql_fetch_assoc($query_result)) {
            $query_rows[$row[$id]] = $row;
        }

        return $query_rows;
    }

    /*
     * Gets the WMIDs that have images associated with them. This will allow
     * the website to query the database for images one time, rather than once
     * for every watermark.
     *
     * Params:
     *     db - the database to query
     */
    function get_wmphoto_ids($db) {
        $sql          = "Select WMID from WaterMarkPhotos Order by WMID;";
        $query_result = mysql_query($sql, $db);
        $query_rows   = array();
        $i            = 0;
        while ($row = mysql_fetch_assoc($query_result)) {
            $query_rows[$i] = $row['WMID'];
            $i++;
        }

        return $query_rows;
    }

    /*
     * Gets the WMIDs that have images associated with them. This will allow
     * the website to query the database for images one time, rather than once
     * for every watermark.
     *
     * Params:
     *     db    - the database to query
     *     image - the image type to get from the database
     */
    function get_wmphoto_ids_clause($db, $image) {
        $sql          =   "Select WMID from WaterMarkPhotos where PhotoType = "
                        . "'$image' Order By WMID";
        $query_result = mysql_query($sql, $db);
        $query_rows   = array();
        $i            = 0;
        while ($row = mysql_fetch_assoc($query_result)) {
            $query_rows[$i] = $row['WMID'];
            $i++;
        }

        return $query_rows;
    }
?>
