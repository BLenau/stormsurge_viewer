<?php
session_start();
include('authenticate.php');
include('../connect.php');
if (isset($_SERVER['PHP_AUTH_USER']) &&                                         
    $_SERVER['PHP_AUTH_USER'] == "$admin_user" &&                                     
    $_SERVER['PHP_AUTH_PW'] == "$admin_pass") {  
    include('header.php');
    include('nav_bar.php');
?>
    <div class="body-container drop-shadow" style="height: 372px;">
    <div id="content">
      <div class="section-title">
        <h2>Admin</h2>
      </div>
      <hr class="h1" />
      <table id="end" col="1" style="boder-spacing: 10px 5px;">
    <?php

    $description;
    $file_name = '';
    $filepath;
    $lat;
    $long;
    $target_path = '';
    $target_path_pdf;
    $wmid;
    $file_type = '';

    /**
     * If the entered file is a pdf:
     */
    if (isset($_FILES['pdf_file'])) {
        $file_type = 'PDF';
        $file_name = $_FILES['pdf_file']['name'];
        $filepath = $_FILES['pdf_file']['tmp_name'];

        /**
         * We want to populate a bunch of variables that will be used
         * to insert the watermark photo information into the 
         * database.
         */
        $orig_name   = $_POST['orig_name'];
        $orig_name   = preg_replace("/[^A-Za-z0-9]/", "", $orig_name);
        $orig_name   = strtoupper($orig_name);
        $description = $_POST['description'];

        /**
         * These latitude and longitude variables contain invalid
         * latitude and longitude values. This will be used to check
         * and see that the WMID was an actual WMID in the database.
         */
        $lat  = -99999;
        $long = -99999;

        $sql        = "Select * from WaterMarks where OrigName is not null";
        $results    = mysql_query($sql, $db);
        $been_found = false;

        /**
         * While there are rows left in the table that resulted from
         * the earlier query:
         */
        while (($row = mysql_fetch_assoc($results)) &&
               !$been_found) {
            
            $row_orig_name = $row['OrigName'];
            if ($row_orig_name != null) {
                $row_orig_name = preg_replace("/[^A-Za-z0-9]/", "", 
                                 $row_orig_name);
                $row_orig_name = strtoupper($row_orig_name);
            }
                                
            /**
             * If the WMID of the current row equals the entered WMID:
             */
            if (strcmp($orig_name, $row_orig_name) == 0) {
                $lat        = $row['Latitude'];
                $long       = $row['Longitude'];
                $wmid       = $row['WMID'];
                $been_found = true;
            }
        }
        /**
         * If the latitude and longitude weren't changed, meaning that
         * a matching WMID in the database wasn't found:
         */
        if ($lat == -99999 || $long == -99999 || !$been_found) {
        ?>
<script type="text/javascript">
    var alertMessage = "The entered OrigName (" +
                       "<?php echo $orig_name; ?>) was not valid!";
    alert(alertMessage);
    window.location = "admin_upload.php";
</script>
        <?php

        /**
         * If the entered file is an image file:
         */
        }
    } else if (isset($_FILES['image_file'])) {
        $file_type = 'PNG';
        $file_name = $_FILES['image_file']['name'];
        $filepath = $_FILES['image_file']['tmp_name'];

        $wmid        = trim($_POST['wmid']);
        $description = $_POST['description'];
        $lat  = -99999;
        $long = -99999;

        $sql       = "Select * from WaterMarks where WMID = $wmid;";
        $results   = mysql_query($sql, $db);
        $been_found = false;

        while ($row = mysql_fetch_array($results, MYSQL_ASSOC) &&
               !$been_found) {
            
            /**
             * If the WMID of the current row equals the entered WMID:
             */
            if ($wmid == $row['WMID']) {
                $lat  = $row['Latitude'];
                $long = $row['Longitude'];
                $been_found = true;
            }
        }
        
        if (!$been_found || $lat == -99999 || $long == -99999) {

        ?>
<script type="text/javascript">
    var alertMessage = "The entered WMID (" +
                       "<?php echo $_POST['wmemid']; ?>) was not valid!";
    alert(alertMessage);
    window.location = "admin_upload.php";
</script>
    <?php
        }
    /**
     * If no file was submitted at all:
     */
    } else {
    ?>
<script type="text/javascript">

    window.location = "admin_upload.php";

</script>
    <?php
    }

    if ($file_name != '') {
        /**
         * This block of code formats the entered image file's name
         * to be an acceptable form for the server.  This means
         * that it cannot contain any characters that must be
         * escaped. The call to preg_replace formats the name of
         * the file.
         */
        $fullname   = basename($file_name);
        $fullname   = preg_replace("/[^A-Za-z0-9]/", "_", $fullname);
        $target_dir = '';

        /**
         * If the extension was found, meaning that the filename
         * had at least one non-alpha-numeric character in it:
         *
         * The only reason this is here is just as a precaution
         * against the user entering in file that is only text.
         * If that name did not contain any character that needed
         * to be replaced by an '_' by preg_replace(), then this
         * call will return false.
         */
        if ($extension = strrchr($fullname, '_')) {

            /**
             * The filename gets the fullname minus the extension,
             * so it gets everything from the begining of the
             * fullname until (and not including) the last '_'
             */
            $filename   = str_replace($extension, '', $fullname);

            /**
             * Change the '_' in the $extension into a '.' like

             * it should be.
             */
            $extension  = substr_replace($extension, '.', 0, 1);
            $fullname   = $filename . $extension;
        }

        /**
         * If the extension is .pdf we still have formatting to do:
         */
        if ($extension == ".pdf") {

            $target_dir = "../images/watermark_pdfs/";
            /**
             * The target_path is the pathname of the file.
             */
            $target_path = "../images/watermark_pdfs/" . $filename .
                               "_pdf.png";
            $target_path_temp = "../images/watermark_pdfs/" . $fullname;

        } else if ($extension == ".jpg" || $extension == ".jpeg" ||
                   $extension == ".gif" || $extension == ".png") {

            $target_dir = "../images/watermark_images/";
            $target_path = "../images/watermark_images/" . $fullname;
        } 
        if ($target_path != '') {

            /**
             * If the file doesn't already exist on the
             * server:
             */
            if (!file_exists($target_path)) {
                if ($extension == ".pdf") {
                    $target_path_png  = $target_path;
                    $target_path      = $target_path_temp;
                }

                /**
                 * If the file was moved successfully:
                 */
                if (move_uploaded_file($filepath, $target_path)) {
                    chmod($target_path, 0646);

                    if ($extension == ".pdf") {
                        exec("convert $target_path $target_path_png");
                        exec("rm $target_path");
                        $target_path = $target_path_png;

                        $compress = './' . $target_dir . 'crush.sh ' .
                                    $target_path;
                        exec($compress);
                    } else {
                        $convert  = './' . $target_dir . 'convert.sh ' . 
                                    $target_path;
                        exec($convert);
                    }
                    /**
                     * This insert statement is used to
                     * populate the WaterMarkPhotos table
                     * when the MySQL database is update.
                     */
                    $sql =   "Insert into WaterMarkPhotos "
                           . "(WMID, PName, PLink, Description, PhotoType)"
                           . "values ($wmid, '$filename', '$target_path', "
                           . "'$description', '$file_type');";
                    $success = mysql_query($sql, $db);

                    if ($success) {
                    ?>
        <tr>
          <td>
            Image upload successful
          </td>
        </tr>
                        <?php
                         /**
                          * When the database is updated, it doesn't
                          * initially contain any information about
                          * WaterMarkPhotos. To fix this, and cause
                          * the uploaded photos to persist when the
                          * database is updated, a file is created
                          * that will contain all of insert
                          * statements of all of the photos that
                          * have been update.
                          */
                        $file = "../scripts/database_scripts/insert_wmphotos.sql";
                        $fh = fopen($file, 'a');
                        fwrite($fh, $sql . "\n");
                        fclose($fh);
                        chmod("../scripts/database_scripts/insert_wmphotos.sql", 0755);
                        ?>
        <tr>
          <td>
            <a href="<?php echo $target_path; ?>" rel="lightbox"><img border="0" 
                    width="200" height="200" src="<?php echo $target_path; ?>" 
                    alt="Uploaded image" /></a>
          </td>
        </tr>
                    <?php
                    /**
                     * If the query to insert the image information
                     * into the database failed:
                     */
                    } else {
                    ?>
        <tr>
          <td>
            Image upload failed
          </td>
        </tr>
                <?php
                    }
                /**
                 * If the image was not successfully moved from
                 * it's temporary location to the images
                 * directory:
                 */
                } else {
                ?>
        <tr>
          <td>
            Failed to move image onto server
          </td>
        </tr>
            <?php
                }
                /**
                 * If the file already exists on the server:
                 */
            } else {
            ?>
<script type="text/javascript">
    var alertMessage = "The entered file already exsists on the server!";
    alert(alertMessage);
    window.location = "admin_upload.php";
</script>
        <?php
            }
            /**
             * If the image was not of an acceptable file type:
             */
        } else {
        ?>
<script type="text/javascript">
    var alertMessage = "The entered file was not a proper file type!";
    alert(alertMessage);
    window.location = "admin_upload.php";
</script>
    <?php
        }
    }
    ?>
        <tr>
          <td>
            <a href="admin_upload.php">Upload</a> another file
          </td>
        </tr>
        <tr>
          <td>
            <a href="index.php">Return</a> to the admin page
          </td>
        </tr>
      </table>
    </div>
  </div>
</body>
</html>
<?php
}
?>
