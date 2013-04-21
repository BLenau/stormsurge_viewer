<?php
session_start();
include('authenticate.php');
include('../connect.php');
$no_data_error_message = "Please choose atleast one photo to remove";
if (isset($_SERVER['PHP_AUTH_USER']) &&                                         
    $_SERVER['PHP_AUTH_USER'] == "$admin_user" &&                                     
    $_SERVER['PHP_AUTH_PW'] == "$admin_pass") {   

    include('header.php');
?>
<script type="text/javascript">
    function movePage(filter) {
        var newLocation = "admin_remove.php?filter=" + filter + "&params=" +
                          document.getElementById('params').value;
        window.location = newLocation;
    }
</script>
<?php
    include('nav_bar.php');


    /**
     * The select statement that will populate the table with the currently
     * uploaded Watermark images
     */
    $sql    = "select * from WaterMarkPhotos Order By WMID Asc;";
    $result = mysql_query($sql, $db);

    /**
     * This function creates the "pagination" links for the different pages of
     * the images to remove.
     */
    function pagin($currpage, $items, $perpage, $params){ 
        $items++;
        $page = $currpage; 
        $pmax = ceil($items / $perpage); 
        if ($page < 1 || $page > $pmax) {
             $page = 1; 
        }

        $prev = $page - 1; 
        $next = $page + 1; 
        $table = "<table><tr>\n<td style=\"width:2.5em;text-align:right\">"; 
        if ($page != 1) {
            $table .= "<a href=\"?page=$prev&filter=$perpage&params=$params\">Prev</a>"; 
        } else {
            $table .= "&nbsp;"; 
        }
        
        $table .= "</td><td>"; 

        $beenDottedBefore = false;
        $beenDottedAfter  = false;
        for ($i = 1; $i <= $pmax; $i++){ 
            if ($i == $page) {
                $table .= "$i "; 
            } else if ((($i < $page + 3) && ($i > $page - 3)) || 
                       ($i == $pmax) || ($i == 1)) {
                $table .= "<a href=\"?page=$i&filter=$perpage&params=$params\">$i</a> ";
            } else if ($i <= $page - 3 && !$beenDottedBefore) {
                $table .= ".... ";
                $beenDottedBefore = true;
            } else if ($i >= $page + 3 && !$beenDottedAfter) {
                $table .= ".... ";
                $beenDottedAfter = true;
            }
        } 

        $table .= "</td><td style=\"width:2.5em;text-align:left\">"; 
        if ($page != $pmax) {
            $table .= "<a href=\"?page=$next&filter=$perpage&params=$params\">Next</a>"; 
        } else {
            $table .= "&nbsp;";
        }
    
        $table .= "</td>\n</tr></table>\n"; 
        return $table;
    }

    /**
     *
     */
    function get_table($per_page, $page, $params, $db) {
        $query = '';
        $start_row = $per_page * $page;
        if ($params == -1) {
            $query = "Select * from WaterMarkPhotos Order By WMID Asc Limit $per_page " .
                     "Offset $start_row;";
        } else {
            $query  = "Select * from WaterMarkPhotos where WMID in (
                           Select WMID from WaterMarks where 
                           OrigName like '%$params%') Order By WMID Asc
                           Limit $per_page Offset $start_row;";
        }
        return mysql_query($query, $db);
    }
?>
  <div class="body-container drop-shadow">
    <div id="content">
      <div class="section-title">
        <h2>Admin</h2>
      </div>
      <hr class="h1" />
      <table id="upload" cols="2" style="border-spacing: 10px 5px;">
    <?php

    /**
     * If the no_data_error variable is set and is set to true:
     */
    if ((isset($_SESSION['no_data_error'])) && ($_SESSION['no_data_error'])) {
        ?>
        <tr>
          <td>
            <strong>
              <?php echo $no_data_error_message; ?>
            </strong>
          </td>
        </tr>
    <?php

    }
    ?>
        <tr>
          <td>
            <big>
            Remove Watermark images from the server
            </big>
            <br />
        <?php
        /**
         * If there is atleast one row of data from the select statement:
         *
         * That is, if the select statement actually had results:
         */
        if (mysql_num_rows($result) > 0) {
        ?>
            <form enctype="multipart/form-data"
                  action="remove_file.php" method="POST">
              <table id="form" cols="3" style="border-spacing: 10px 5px;">
                <tr>
                  <td>
            <?php
            $params  = (isset($_GET['params'])) ? $_GET['params'] : -1;
            $page    = (isset($_GET['page']) && 
                        ctype_digit($_GET['page'])) ? $_GET['page'] : 1; 
            $perpage = (isset($_GET['filter']) &&
                        ctype_digit($_GET['filter'])) ? $_GET['filter'] : 10;

            $orig_result  = get_table($perpage, 0, $params, $db);
            $table_result = get_table($perpage, $page - 1, $params, $db);
            $items        = ($params == -1) ? mysql_num_rows($result) : 
                                              mysql_num_rows($orig_result);
            
            echo pagin($page, $items, $perpage, $params);
            echo "<hr>";
            ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <table id="data" cols="4" style="border-spacing: 10px 5px;">
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
                        <td align="center">
                          Delete
                        </td>
                      </tr>
            <?php

            if ($table_result) {

                while ($row = mysql_fetch_assoc($table_result)) {
                    $plink = $row['PLink'];
                    
                    if (substr($plink, 0, 3) != "../") {
                        $plink = "../" . $plink;
                    }
            ?>
                      <tr>
                        <td align="center">
                          <?php echo $row['WMID']; ?>
                        </td>
                        <td align="center">
                          <?php echo $row['PName']; ?>
                        </td>
                        <td align="center">
                          <a href="<?php echo $plink; ?>" 
                             rel="lightbox"><img border="0" width="100"
                                  src="<?php echo $plink; ?>"
                                  alt="<?php echo $row['PName']; ?>"
                                  height="100" /></a>
                        </td>
                        <td align="center">
                          <input type="checkbox" name="<?php echo $row['PName']; ?>" 
                                 id="<?php echo $row['PName']; ?>" 
                                 value="<?php echo $row['WMID']; ?>" />
                        </td>
                      </tr>
            <?php
                }
            }
            ?>
                      <tr>
                        <td>
                          <input type="submit" name="submit" value="Submit" />
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
                <tr>
                  <td>
            <?php
            echo "<hr>\n"; 
            echo pagin($page, $items, $perpage, $params);
            ?>
                  </td>
                </tr>
              </table>
            </form>
          </td>
          <td valign="top">
            <input type="text" size="20" class="textBox" readonly />
          </td>
          <td valign="top">
            <select name="perpage" onChange="window.open(this.options[this.selectedIndex].value,
                                                         '_top');">
            <option value="admin_remove.php">Images per page</option>
            <option value="admin_remove.php?filter=10&params=<?php echo $params; ?>">10</option>
            <option value="admin_remove.php?filter=20&params=<?php echo $params; ?>">20</option>
            <option value="admin_remove.php?filter=50&params=<?php echo $params; ?>">50</option>
            </select>
            <br />
            <br />
            Enter OrigName or image name:<input type="text" id="params"
                                                name="params" size="8" />
            <input type="button" value="Search" 
                   onClick="movePage(<?php echo $perpage; ?>);" />
            <br />
            <br />
            <a href="admin_remove.php?filter=<?php echo $perpage; ?>">Clear search</a>
          </td>
        <?php
        /**
        * If the select statement on the WaterMarkPhotos table did not
        * return any results:
        *
        * That is, if there are currently not photos uploaded:
        */
        } else {
        ?>
            The database does not contain any Watermark photos at this time.
          </td>
        </tr>
        <tr>
          <td>
            <a href="admin_upload.php">Upload</a> one now
        <?php
        }
        ?>
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
</div>
</body>
</html>
<?php
    }
    /**
     * If this page loaded with an error message, ensure that subsequent
     * refreshes will not have the error message
     */
    $_SESSION['no_data_error'] = false;
?>
