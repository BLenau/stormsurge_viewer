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
      <table id="page_table" col="1" style="border-spcing: 10px 5px;">
    <?php
    /**
     * If the user submitted the form on the admin_remove.php:
     *
     * This will return true unless the user navigates to this page by simply
     * typing in the URL
     */
    if (isset($_POST['submit'])) {
        $form_has_results = false;

        /**
         * For all the values in $_POST:
         */
        foreach($_POST as $form_result) {
            
            /**
             * The value for clicking the submit button is "Submit", so if the
             * user clicked submit, then that value will exist within the
             * $_POST array, so we need to check and see if other values exist
             * in teh array aside from this one
             */
            if ($form_result != "Submit") {
                $form_has_results = true;
            }
        }

        /**
         * If the form had results:
         *
         * That is, if the user ticked atleast one of the checkboxes on the
         * admin_remove.php page:
         */
        if ($form_has_results) {
    ?>
        <tr>
          <td class="warning">
            <big>
              Are you sure you want to delete the following photos?
            </big>
          </td>
        </tr>
        <tr>
          <td>
            <table id="deletion" col="3" style="border-spacing: 10px 5px;">
              <tr>
                <td align="center">
                  WMID                                                        
                </td>                                                         
                <td align="center">                                           
                  Image name                                                  
                </td>                                                         
                <td align="center">                                           
                  Image                                                       
                </td>                                                         
              </tr>
              <form enctype="multipart/form-data" action="remove_final.php"
                    method="POST">
            <?php
            
            /**
             * While there are rows left in the select statement:
             */
            while ($row = mysql_fetch_assoc($result)) {
                $plink = $row['PLink'];
                
                if (substr($plink, 0, 3) != "../") {
                    $plink = "../" . $plink;
                }
                $temp_array = explode('.', $row['PLink']);
                $extension = $temp_array[count($temp_array) - 1];
                $pname = $row['PName'];
                $fullname = $pname . '.' . $extension;

                /**
                 * If the photo name of the current row is set in the $_POST
                 * array:
                 *
                 * That is, if the Watermark photo associated with the current
                 * row had it's checkbox ticked on the original removal page:
                 */
                if (isset($_POST["$pname"])) {
                ?>
                <tr>
                  <td align="center">
                    <?php echo $row['WMID']; ?>
                  </td>
                  <td align="center">
                    <?php echo $fullname; ?>
                  </td>
                  <td align="center">
                    <img border="0" src="<?php echo $plink; ?>"
                         alt="<?php echo $fullname; ?>" width="100"
                         height="100" />
                    <input type="hidden" name="<?php echo $pname; ?>" 
                           id="<?php echo $pname; ?>" value="yes" />
                  </td>
                </tr>
            <?php
                
                /**
                 * If there was data, then we want to make sure that the error
                 * flag for having no data is set to false
                 */
                $_SESSION['no_data_error'] = false;
                }
            }
            ?>
                <tr>
                  <td>
                    <input type="submit" id="submit" name="submit" value="Yes" />
                  </td>
                  <td>
                    <a href="admin_remove.php"><input type="button" value="No" /></a>
                  </td>
                </tr>
              </form>
            </table>
          </td>
        </tr>
        <?php
        } else {
            $_SESSION['no_data_error'] = true;
        }
    }
    if (!isset($_POST['submit']) || $_SESSION['no_data_error']) {
    ?>
    <script type="text/javascript">
    <!--
        /**                                                                 
         * This Javascript code moves the user from the current page to the 
         * given page                                                       
         */
        window.location = "admin_remove.php";
    //-->
    </script>
    <?php
    }
    ?>
        <tr>
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
