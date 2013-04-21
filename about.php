<?php
    session_start();

    include("header.php");
?>
    <link rel="stylesheet" href="scripts/css/about_style.css" 
          type="text/css"  charset="utf-8" />
<?php
    include("nav_bar.php");
?>

  <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("#nav-about").attr("class", "active");
        });
  </script>

  <div class="body-container drop-shadow">
    <div id="content">
      <div class="section-title">
        <div class="content-left">
          <img src="images/banner.png" style="position: relative; top: -10px;" /> 
        </div>
        <div class="content-right" style="position: relative; right: 5px;">
          <table id="logos-table" cols="3" align="center"
                 style="border-spacing: 0px 0px;" cellspacing="10px">
            <tr>  
              <td>                                                          
                <a href="http://facebook.com/pages/Program-for-the-Study-of-Developed-Shorelines/178055929458"
                   target="new_"><div class="facebook-logo"></div></a>
              </td>
              <!--
              <td>
                <a href="http://psds.wcu.edu" 
                   target="_new"><div class="psds-logo"></div></a>
              </td>
              -->
              <td> 
                <a href="http://www.wcu.edu"
                   target="new"><div class="wcu-logo"></div></a>

              </td>
            </tr>  
          </table> 
        </div>
      </div>
      <div class="clear"></div>
      <hr class="section-divider"></hr>
      <div class="para">
        <p>
          Storm Surge Viewer was created at <a href="http://www.wcu.edu/"
                                               target="new">
          Western Carolina University</a>
          (WCU) as part of 
          the <a href="http://psds.wcu.edu/"
                 target="_new">Program for the Study of Developed Shorelines</a>
          (PSDS) Storm Surge Database project. This web page 
          utilizes the storm surge database to locate, describe and map 
          storm surge and high water mark data. Storm track data was 
          obtained from NOAA NCDC’s IBTrACS (International Best Track 
          Archive for Climate Stewardship) database. 
        </p>
        
        <p>
            This national storm surge database itself was formed using 
            relational tools and geographic information systems (GIS) and 
            will eventually provide one central location for coastal 
            scientists and engineers to access storm surge and high water 
            mark data. This large database is being built in Microsoft 
            Access and ArcGIS at WCU, but will ultimately be maintained 
            and archived at NOAA’s National Climatic Data Center (NCDC). 
            Storm surge data are being obtained from multiple sources 
            including federal agencies, state agencies, academic studies, 
            and the private sector.
        </p>            
      </div>
      <div class="dev-separator"></div>
      <div class="content-left-narrow">
        <a href="http://psds.wcu.edu"
           target="_new"><div id="psds-logo" class="logo"></div></a>
      </div>
      <div class="content-right-wide">
        <p>
          The <a href="http://psds.wcu.edu"
                 target="_new">Program for the Study of Developed Shorelines</a>
          (PSDS) is a 
          research and policy outreach center serving the global coastal 
          community. The primary mission of PSDS is to conduct scientific 
          research into coastal processes and to translate that science 
          into management and policy recommendations through a variety 
          of professional and public outreach mechanisms. The Program 
          specializes in evaluating the design and implementation of 
          coastal engineering projects.
        </p>
      </div>
      <div class="dev-separator"></div>
      <div class="developer">
        <div id="rob-dev-pic" class="dev-pic"></div>
        <p>
          Dr. Robert Young -Director of the Program for the Study of 
          Developed Shorelines, a joint Duke University/Western 
          Carolina University venture. He is also a Professor of 
          Coastal Geology at Western Carolina University and a 
          licensed professional geologist in three states (FL, NC, SC).
        </p>
      </div>
      <div class="dev-separator"></div>
      <div class="developer">
        <div id="katie-dev-pic" class="dev-pic"></div>
        <p>
          Katie McDowell Peek -Coastal Research Scientist at the 
          Program for the Study of Developed Shorelines who is 
          currently developing and maintaining the storm surge 
          database at Western Carolina University.
        </p>
      </div>
      <div id="selection-divider"></div>
    </div>
  </div>

  <div class="body-container drop-shadow" 
       style="border-radius: 10px 10px 10px 10px;">
    <div id="content">
      <div class="section-title">
         <h2>About the Storm Surge Viewer Developers</h2>
      </div>
      <hr class="section-divider"></hr>
        <div class="dev-separator"></div>
        <div class="developer">
            <div id="chris-dev-pic" class="dev-pic"></div>
            <p>
                Chris Blades - Recent Computer Science graduate of Western
                Carolina University and one of the original database and 
                web developers for the Storm Surge Database project.
           </p>
       </div>
        <div class="dev-separator"></div>
        <div class="developer">
           <div id="russell-dev-pic" class="dev-pic"></div>
           <p>
              Russell Gaskey - Senior Computer Science major at Western
              Carolina University, one of the original database and web 
              developers for the Storm Surge Database project, and
              current member of the database and web development team for
              the Storm Surge Database project.
           </p>
        </div>
        <div class="dev-separator"></div>
        <div class="developer">
           <div id="cristina-dev-pic" class="dev-pic"></div>
           <p>
              Cristina Korb - Senior Mathematics major and Computer Science
              minor at Western Carolina University and current member of 
              the database and web development team for the Storm Surge 
              Database project.
           </p>
        </div>
        <div class="clear"></div>
        <div class="developer">
           <div id="brian-dev-pic" class="dev-pic"></div>
           <p>
              Brian Lenau - Senior Computer Science major at Western
              Carolina University and current member of the database and 
              web development team for the Storm Surge Database project.
           </p>
        </div>
        <div class="dev-separator"></div>
        <div class="developer">
            <div id="brad-dev-pic" class="dev-pic"></div>
            <p>
                Brad Proctor - Senior Computer Science major and one of 
                the original database and web developers for the Storm 
                Surge Database project.
           </p>
       </div>
        <div class="dev-separator"></div>
        <div class="developer">
            <div id="holliday-dev-pic" class="dev-pic"></div>
            <p>
                Dr. Mark Holliday - Computer science professor at 
                Western Carolina University and supervisor of 
                database and web development for the Storm Surge Project.
           </p>
       </div>
       <div id="selection-divider"></div>
     </div>
  </div>

  <div style="height: 135px;">
    <div class="body-container drop-shadow" 
    style="height: 125px; border-radius: 10px 10px 10px 10px;">
      <div id="content">
        <div class="section-title">
          <h2>Acknowledgments</h2>

        </div>
        <hr class="section-divider"></hr>
        <div class="dev-separator"></div>
          <p>
            We gratefully acknowledge the financial support of the National
            Oceanic and Atmospheric Administration (NOAA) for this project.
          </p>
      </div>
    </div>
  </div>
  </body>
</html>
