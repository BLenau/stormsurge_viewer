<?php
session_start();
include('authenticate.php');
if (isset($_SERVER['PHP_AUTH_USER']) &&                                         
    $_SERVER['PHP_AUTH_USER'] == "$admin_user" &&                                     
    $_SERVER['PHP_AUTH_PW'] == "$admin_pass") {   
    include('header.php');
    include('nav_bar.php');
?>
  <div class="body-container drop-shadow" style="height: 145px;">
    <div id="content">
      <div class="section-title">
        <h2>Admin</h2>
      </div>
      <hr class="h1" />
      <table id="page_info" style="border-spacing: 10px 5px;">
        <tr>
          <td>
    <?php
    if (isset($_FILES['database_file'])) {
        $filename    = basename($_FILES['database_file']['name']); 
        $extension   = substr($filename, -4);
        $filename    = "database_file" . $extension;
        $target_path = "../apache/database/" . $filename;
        $command     = "./../apache/database/mdb_script.sh";
        $remove      = "./../apache/database/remove_files.sh";
        $populate    = "mysql -u $dbuser -p$dbpass $dbname < "
                       . "../apache/database/populate_database.sql";
        $drop_extras = "mysql -u $dbuser -p$dbpass $dbname < "
                       . "../apache/database/drop_extras.sql";
        if (move_uploaded_file($_FILES['database_file']['tmp_name'], 
                               $target_path)) {
            exec($command);
            exec($populate);
            exec($drop_extras);
            //exec($remove);
            echo "The database has been updated.";
        } else {
            echo "An error occured while trying to update.";
        }
    } else {
    ?>
    <script type="text/javascript">
        <!--
        window.location = "admin_update.php";
        //-->
    </script>
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
?>
