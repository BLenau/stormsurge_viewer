  </head>
<body>
<div id="outer">
  <div id="logo"></div>
  <!-- The navigation bar that is used in all the pages of the site. -->
  <div id="nav">
    <ul>
      <a href="../index.php"><li id="nav-home"><b>home</b></li></a>
      <?php
          if (isset($_SESSION['address']) && $_SESSION['address'] !== "") {
      ?>
              <a href="../results.php"><li id="nav-results"><b>results</b></li></a>
      <?php
          }
      ?>
      <a href="http://www.wcu.edu/1037.asp" target="_new"><li id="nav-psds"><b>psds website</b></li></a>
      <a href="http://www.noaa.gov/" target="_new"><li id="nav-noaa"><b>noaa website</b></li></a>
      <a href="../about.php"><li id="nav-about"><b>about</b></li></a>
    </ul>
  </div>
