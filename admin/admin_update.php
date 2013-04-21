<?php
session_start();
include('authenticate.php');
if (isset($_SERVER['PHP_AUTH_USER']) &&                                         
    $_SERVER['PHP_AUTH_USER'] == "$admin_user" &&                                     
    $_SERVER['PHP_AUTH_PW'] == "$admin_pass") {   
    include('header.php');
?>
<script>                                   
    jQuery(document).ready(function() {        
        jQuery("#database_form").validate({    
            onfocusout: false,  
            onkeyup: false,     
            onclick: false,     
            rules: {            
                database_file: {
                    required: true,    
                    accept: "mdb|accdb|MDB|ACCDB"
                }
            },
            messages: {                                                         
                database_file: 'Please enter a valid Access database file (mdb or accdb)',
            },                                                                  
            submitHandler: function(form) {                                     
                form.submit();                                                  
            },                                                                  
            errorElement: "li",                                                 
            errorLabelContainer: "#error_message",                              
            invalidHandler: function(form, validator) {                         
                var errors = validator.numberOfInvalids();                      
                var d = document.getElementById('body_div');                    
                d.style.height = "220px";
                if (errors) {                                                   
                    if (errors === 1) {                                         
                        d.style.height = "245px";                               
                    } else {                                                    
                        d.style.height = (245 + (errors * 15)) + "px";          
                    }                        
                }                            
            }                                
        });                                  
    });                                      
    </script>
<?php
    include("nav_bar.php");
?>
  <div id="body_div" class="body-container drop-shadow" style="height: 220px;">
    <div id="content">
      <div class="section-title">
        <h2>Admin</h2>
      </div>
      <hr class="h1" />
      <ul id="error_message"></ul>
      <table id="upload" col="1" style="border-spacing: 10px 5px;">
        <tr>
          <td>
            <big>
            Upload the current Access database file 
            to update the existing MySQL database on the server
            </big>
          </td>
        </tr>
        <tr>
          <td>
            <form enctype="multipart/form-data" id="database_form"
                  action="update_database.php" method="POST">
              <table id="form" col="2" style="border-spacing: 10px 5px;">
                <tr>
                  <td>
                      Choose the current Microsoft Access <br />
                      database file to upload: 
                  </td>
                  <td>
                    <input type="file" name="database_file" id="database_file" 
                           size="20" />
                  </td>
                  <td>
                    <input type="submit">Submit</input>
                  </td>
                </tr>
              </table>
            </form>
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
