<?php
include('../connect.php');

/**
 * If the user has not yet submitted a username and password, then prompt
 * the user for them.
 */
if (!isset($_SERVER['PHP_AUTH_USER']) || 
    $_SERVER['PHP_AUTH_USER'] != "$admin_user" || 
    $_SERVER['PHP_AUTH_PW'] != "$admin_pass") {
    /**
     * These headers are used for HTTP authentication. They prompt the user
     * for a username and password as soon as the request for the page is
     * met, and the username and password are stored in 
     * $_SERVER['PHP_AUTH_USER'] and $_SERVER['PHP_AUTH_PW'] respectively. 
     */
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    // What comes after the headers is what the page will generate if the user
    // clicks the "Cancel" button, rather than submitting the authentication
    // request form
    include('header.php');
    include('nav_bar.php');
?>
  <div class="body-container drop-shadow" style="height: 115px;">
    <div id="content">
      <div class="section-title">
        <h2>Admin</h2>
      </div>
      <div id="title-divider"></div>
      <div class="clear"></div>
      <p>
        <big><strong>This is a restricted page. Please enter the appropriate
        credentials to access it.</strong></big>
      </p>
    </div>
  </div>
</body>
</html>
<?php
    }
?>
