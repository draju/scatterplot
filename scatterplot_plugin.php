<?php
// Call the REDCap Connect file in the main "redcap" directory
//require_once "../redcap_connect.php";

## Your custom PHP code goes here. You may use any constants/variables listed in redcap_info().

// OPTIONAL: Display the project header
require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

## Your HTML page content goes here
//once it works, move to class function
//$module->embedScatterplot();

echo "<link rel='stylesheet' href='".$module->getUrl("css/scatterplot.css")."'>";
echo "<script src='https://cdn.plot.ly/plotly-latest.min.js'></script>";        

echo "<h1>Scatterplots</h1>
      <div id='myDiv' class='scatterplot-em'>
      </div>";

// OPTIONAL: Display the project footer
require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';


