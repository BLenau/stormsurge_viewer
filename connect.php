<!--
    Russell Gaskey
    Brian M. Lenau
    Cristina Korb

    Chris Blades
    Brad Proctor
-->
<?php
    // Databse username
    $dbuser="stormsurge";

    // Database password
    $dbpass="VWhQbvF3ju94HGth";

    // Database name
    $dbname="stormsurge";

    // Connect to the database
    $db=mysql_connect("stormsurge.wcu.edu", $dbuser, $dbpass);

    // Select to use the proper database
    mysql_select_db($dbname, $db);

    $admin_user = "blenau";
    $admin_pass = "my password!";
?>
