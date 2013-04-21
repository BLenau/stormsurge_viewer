<?php
    // xmlWriterXmlGeneration.php
    // 
    // A prototype PHP script to read data from a PSDS database and generate
    // an XML document with the results.  This version uses the XMLWriter
    // library to construct and print the XML content.
    // 
    // By Andy Dalton
    // May 26, 2011
    // 

    function printEmptyTag($tag) {
        $writer  = new XMLWriter();
        $writer->openURI("php://output");
        $writer->startDocument("1.0", "UTF-8");
        $writer->startElement($tag);
        $writer->endElement();
        $writer->flush();
    }

    //
    // We need to set the content type to xml so that the client knows how
    // to handle the file we're sending them.
    // 
    header("Content-Type: text/xml");
    
    // I read somewhere that this was essential -- I don't know why.
    @date_default_timezone_set("GMT"); 

    if (isset($_GET["table"])) {
        $table   = $_GET["table"];
        $dbName  = "stormsurge";
        $dbUser  = $dbName;
        $dbPass  = "VWhQbvF3ju94HGth";


        $dbConn  = mysql_connect("stormsurge.wcu.edu", $dbUser, $dbPass)
                       or die(  "Could not connect to MySQL database on "
                              . "localhost.");
        $db      = mysql_select_db($dbName, $dbConn);


        $wmQuery = "SELECT * FROM " . $table;
        $result  = mysql_query($wmQuery, $dbConn);
        if (!$result) {
            printEmptyTag("InvalidTableName");
            mysql_close($dbConn);
            exit;
        }


        $writer  = new XMLWriter();

        // Apparently this URI is some PHP magic for telling the XMLWriter to
        // send its output to stdout.
        $writer->openURI("php://output");
        $writer->startDocument("1.0", "UTF-8");

        $writer->setIndent(true);
        $writer->setIndentString("    ");

        //$writer->startElement("WaterMarks"); 
        $writer->startElement($table . "Table");

        while ($row = mysql_fetch_array($result)) {

            //$writer->startElement("WaterMark");
            $writer->startElement($table);
            $colQuery      = "SHOW COLUMNS FROM $table";
            $columnResults = mysql_query($colQuery, $dbConn)
                                 or die("Could not query DB for column names.");

            while ($nameRow = mysql_fetch_array($columnResults)) {
                if (       isset($_GET[$nameRow["Field"]])
                        || isset($_GET["ALL"])) {
                    $writer->writeElement($nameRow["Field"],
                                          trim($row[$nameRow["Field"]]));
                }
            }

            mysql_free_result($columnResults);
            $writer->endElement();

        }
        $writer->endElement();
        $writer->flush();

        // Clean up -- this would happen automatically
        mysql_free_result($result);
        mysql_close($dbConn);
    } else {
        printEmptyTag("NoTableRequested");
    }
?>
