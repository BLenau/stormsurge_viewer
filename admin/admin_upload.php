<?php
session_start();

include('authenticate.php');
if (isset($_SERVER['PHP_AUTH_USER']) &&                                         
    $_SERVER['PHP_AUTH_USER'] == "$admin_user" &&                                     
    $_SERVER['PHP_AUTH_PW'] == "$admin_pass") {   
    include('header.php');
?>
    <script>
    var active_color   = '#000';
    var inactive_color = '#ccc';

    jQuery(document).ready(function() {
        jQuery("input.default-value").css("color", inactive_color);
        var default_values = new Array();
        jQuery("input.default-value").focus(function() {
            if (!default_values[this.id]) {
                default_values[this.id] = this.value;
            }
            if (this.value == default_values[this.id]) {
                this.value = '';
                this.style.color = active_color;
            }
            jQuery(this).blur(function() {
                if (this.value == '') {
                    this.style.color = inactive_color;
                    this.value = default_values[this.id];
                }
            });
        });

        jQuery("#image_form").validate({
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            rules: {
                image_file: {
                    required: true,
                    accept: "gif|jpe?g|png|bmp"
                },
                orig_name: {
                    required: true,
                },
                pdf_file: {
                    required: true,
                    accept: "pdf"
                },
                wmid: {
                    required: true,
                    min:      1
                }
            },
            messages: {
                image_file: 'Please enter a valid image file (jpg, png, or gif)',
                wmid: 'Please enter a number greater than 0',
                pdf_file: 'Please enter a valid PDF file',
                orig_name: 'Please enter a valid OrigName'
            },
            submitHandler: function(form) {
                var d = document.getElementById('body_div');
                d.style.height = "308px";
                form.submit();
            },
            errorElement: "li",
            errorLabelContainer: "#error_message",
            invalidHandler: function(form, validator) {
                var errors = validator.numberOfInvalids();
                var d = document.getElementById('body_div');
                if (errors) {
                    if (errors === 1) {
                        d.style.height = "350px";
                    } else {
                        d.style.height = (335 + (errors * 15)) + "px";
                    }
                }
            }
        });
    });
    function setTextField(text1, text2, idTextToFind, idTextToChange, idFileToFind,     
                          idFileToChange) {                                     
        var textField = document.getElementById('wmid_orig');                   
        var textBox   = document.getElementById('details');
        var inputText = document.getElementById(idTextToFind);                  
        var inputFile = document.getElementById(idFileToFind);                  
                                                     
        textField.value = text1;                      
        textBox.value   = text2;

        inputText.setAttribute('name', idTextToChange);
        inputText.setAttribute('id', idTextToChange);

        inputFile.setAttribute('name', idFileToChange);
        inputFile.setAttribute('id', idFileToChange);
    }

    </script>
<?php
    include("nav_bar.php");
?>
  <div id="body_div" class="body-container drop-shadow" style="height: 308px;">
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
              Upload a Watermark image or Watermark information file to be used on the server
            </big>
          </td>
        </tr>
        <tr>
          <td>
            <form enctype="multipart/form-data" id="image_form"
                  action="upload_file.php" method="POST">
              <table id="form" cols="2" style="border-spacing: 10px 5px;">
                <tr>
                  <td>
                    <label for="image">
                      <input type="radio" id="image" name="file_type"
                             value="image" checked
                             onClick="setTextField('WMID:', 'Choose a Watermark image to upload:', 
                                                   'orig_name', 'wmid', 'pdf_file', 'image_file');"
                             />Watermark Image
                    </label>
                    <label for="pdf">
                      <input type="radio" id="pdf" name="file_type"
                             value="pdf"
                             onClick="setTextField('OrigName:', 'Choose a Watermark information file to upload:', 
                                                    'wmid', 'orig_name', 'image_file', 'pdf_file');"
                             />Watermark Information (PDF)
                    </label>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="text" id="details" value="Choose a Watermark image to upload:"
                           size="42" class="textBox" readonly />
                  </td>
                  <td>
                    <input type="file" name="image_file" id="image_file" 
                           size="20" />
                  </td>
                </tr>
              </table>
              <table id="form" cols="5" style="border-spacing: 10px 5px;">
                <tr>
                  <td>
                    <input type="text" id="wmid_orig" value="WMID:"             
                           size="7" class="textBox" readonly />                
                    <input type="text" size="10" id="wmid" name="wmid" />
                  </td>
                  <td>
                    <input type="text" id="wmid_orig" value=""             
                           size="1" class="textBox" readonly />                
                  </td>
                  <td>
                    Description:
                  </td>
                  <td>
                    <input type="text" id="description" name="description"
                           size="60" class="default-value"
                           value="Enter a short description here." />
                  </td>
                </tr>
                  <td>
                    <input type="submit"></input>
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
