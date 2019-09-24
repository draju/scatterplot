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
//echo "<PRE>".print_r($module->settings)."</PRE>";
for($i=0; $i < count($module->settings); $i++){
    echo "<h3 class='scatterplot-em'>Scatter Plot #".($i+1).": ".$module->settings[$i]["plot-title"]."</h3><div id='myDiv$i' class='scatterplot-em'></div>";    
?>      
<script>
      var trace1 = {
        x: [1, 2, 3, 4, 5],
        y: [1, 6, 3, 6, 1],
        mode: 'markers',
        type: 'scatter',
        name: 'Team A',
        text: ['A-1', 'A-2', 'A-3', 'A-4', 'A-5'],
        marker: { size: 12 }
      };
      
      var trace2 = {
        x: [1.5, 2.5, 3.5, 4.5, 5.5],
        y: [4, 1, 7, 1, 4],
        mode: 'markers',
        type: 'scatter',
        name: 'Team B',
        text: ['B-a', 'B-b', 'B-c', 'B-d', 'B-e'],
        marker: { size: 12 }
      };
      
      var data = [ trace1, trace2 ];
      
      var layout = {
        xaxis: {
          title: <?php echo "'".$module->settings[$i]["x-title"]."'" ?>,  
          range: [ 0.75, 5.25 ]
        },
        yaxis: {
          title: <?php echo "'".$module->settings[$i]["y-title"]."'" ?>,    
          range: [0, 8]
        }
      };
      
      Plotly.newPlot('myDiv'+<?php echo $i ?>, data, layout);
</script>
<?php
} //end for loop over all configured plots

// OPTIONAL: Display the project footer
require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';


