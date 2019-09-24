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
    
    $pid = $module->getProjectId();
    $data = REDCap::getData($pid,'array',NULL,array($module->settings[$i]["x-coord"],$module->settings[$i]["y-coord"]));
    $x_coord = array();
    $y_coord = array();
    $records = array();
    foreach ($data as $rec => $ev_arr){
        foreach ($ev_arr as $ev => $val){
            array_push($x_coord,$val[$module->settings[$i]["x-coord"]]);
            array_push($y_coord,$val[$module->settings[$i]["y-coord"]]);
            array_push($records,$rec);
        }
    }

?>      
<script>
      var trace1 = {
        x: [
        <?php 
            for($j=0; $j<count($x_coord); $j++ ){
                if($j > 0){
                    echo ",";
                }
                echo $x_coord[$j];
            } 
        ?>
        ],
        y: [
        <?php 
            for($j=0; $j<count($y_coord); $j++ ){
                if($j > 0){
                    echo ",";
                }
                echo $y_coord[$j];
            } 
        ?>
        ], 
        mode: 'markers',
        type: 'scatter'
      };
      
      /*
      var trace2 = {
        x: [1.5, 2.5, 3.5, 4.5, 5.5],
        y: [4, 1, 7, 1, 4],
        mode: 'markers',
        type: 'scatter'
      };
      */

      var data = [ trace1 ];
      
      var layout = {
        xaxis: {
          title: <?php echo "'".$module->settings[$i]["x-title"]."'" ?>
        },
        yaxis: {
          title: <?php echo "'".$module->settings[$i]["y-title"]."'" ?>    
        },
        hovermode: 'closest'
      };
      
      Plotly.newPlot('myDiv'+<?php echo $i ?>, data, layout);
</script>
<?php
} //end for loop over all configured plots

// OPTIONAL: Display the project footer
require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';


