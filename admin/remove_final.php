<?php
session_start();
include('authenticate.php');
include('../connect.php');
if (isset($_SERVER['PHP_AUTH_USER']) &&                                         
    $_SERVER['PHP_AUTH_USER'] == "$admin_user" &&                                     
    $_SERVER['PHP_AUTH_PW'] == "$admin_pass") {   
    include('header.php');
    include('nav_bar.php');

    $sql    = "select * from WaterMarkPhotos Order By WMID Asc;";
    $result = mysql_query($sql, $db);
?>
  <div class="body-container drop-shadow"> 
    <div id="content">
      <div class="section-title">
        <h2>Admin</h2>
      </div>
      <hr class="h1" />
      <table id="page_table" col="1" style="border-spacing: 10px 5px;">
        <tr>
          <td>
    <?php

    /**
     * A flag that will be used later on to determine if the delete statements
     * were successful or not
     */
    $success  = false;

    /**
     * The new file that will contain all of the insert statements for the
     * WaterMarkPhotos table minus the insert statements that were associated
     * with the photos that were just removed
     */
    $new_file = "../scripts/database_scripts/insert_wmphotos_temp.sql";

    /**
     * The old file that contained all of the insert statements for the
     * WaterMarkPhotos table
     */
    $old_file = "../scripts/database_scripts/insert_wmphotos.sql";

    /**
     * The link to the photo that is currently being deleted
     */
    $plink    = "";

    /**
     * If the user clicked the submit button to get to this page:
     */
    if (isset($_POST['submit'])) {
        $success = true;

        /**
         * While there are still rows left in the results of the select
         * statement:
         */
        while ($success && $row = mysql_fetch_assoc($result)) {
            $pname = $row['PName'];
            $plink = $row['PLink'];
            
            /**                                                             
             * If the photo name of the current row is set in the $_POST    
             * array:                                                       
             *                                                              
             * That is, if the Watermark photo associated with the current  
             * row had it's checkbox ticked on the original removal page:   
             */ 
            if (isset($_POST["$pname"])) {

                /**
                 * The sql delete statement for removing the photo from the
                 * database.
                 */
                $sql = "Delete from WaterMarkPhotos where WMID = " .
                       $row['WMID'] . " and PName = '$pname';";
                $success = mysql_query($sql, $db);

                /**
                 * If the delete statement ran successfully:
                 */
                if ($success) {
                    /**
                     * This block of variables is used to form the original
                     * insert statement that was used to add the image to the
                     * database originally.
                     */
                    $wmid        = $row['WMID'];
                    $filename    = $row['PName'];
                    $target_path = $row['PLink'];
                    $description = $row['Description'];
                    $lat         = $row['Latitude'];
                    $long        = $row['Longitude'];

                    /**
                     * This statement must be exactly equal to the original
                     * insert statement because it will be used to remove the
                     * original insert statement from the file that contains
                     * all of the Watermark photo insert statements.
                     */
                    $sql2 = "Insert into WaterMarkPhotos " .         
                            "(WMID, PName, PLink, Description, " .   
                            "Latitude, Longitude) values " .         
                            "($wmid, '$filename', '$target_path', " .
                            "'$description', $lat, $long);";

                    /**
                     * Remove the actual image file from teh server
                     */
                    exec("rm $plink");

                    $fh1 = fopen($new_file, 'w');
                    $fh2 = fopen($old_file, 'r');

                    /**
                     * While there are still lines to be read from the original
                     * insert_wmphotos file:
                     */
                    while ($line = fgets($fh2)) {

                        /**
                         * Trim the whitespace from the line and the SQl
                         * statement (just to be sure)
                         */
                        $line = trim($line);
                        $sql2 = trim($sql2);

                        /**
                         * If the line wasn't equal to the insert statement,
                         * and the line wasn't simply a blank line:
                         */
                        if ($line != $sql2 && $line != "\n") {
                            
                            /**
                             * Write the line to the new insert file because
                             * it isn't associated with the deleted image
                             */
                            fwrite($fh1, $line . "\n");
                        }
                    }
                    fclose($fh1);
                    fclose($fh2);

                    /**
                     * Remove the old insert file, since it is no longer valid,
                     * and then rename (using the mv command) the new file so
                     * that it has the same name as the old file
                     */
                    exec("rm $old_file");
                    exec("mv $new_file $old_file");

                    chmod($old_file, 0755);
                }
            }
        }
    /**
     * If the user came to this page by simply typing in the URL:
     */
    } else {
    ?>
    <script type="text/javascript">
    <!--
        /**
         * Redirect them back to the original removal page
         */
        window.location = "admin_remove.php";
    //-->
    </script>
    <?php
    }
    /**
     * If, at some point during the deletion, there was an error when deleting
     * the row from the database:
     */
    if (!$success) {
    ?>
            An error occured while trying to remove <?php echo $plink; ?>
    <?php
    } else {
    ?>
            The image(s) were removed successfully
    <?php
    }
    ?>
          </td>
        </tr>
        <tr>
          <td>
            <a href="admin_remove.php">Delete</a> more photos
          </td>
        </tr>
          <td>
            <a href="index.php">Return</a> to the admin page
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>
</body>
</html>
<?php
}
?>
