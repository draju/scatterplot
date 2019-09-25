<?php
// Call the REDCap Connect file in the main "redcap" directory
//require_once "../redcap_connect.php";

## Your custom PHP code goes here. You may use any constants/variables listed in redcap_info().

// OPTIONAL: Display the project header
require_once APP_PATH_DOCROOT . 'ProjectGeneral/header.php';

## Your HTML page content goes here
//once it works, move to class function
//$module->embedScatterplot();

/**
 * Helper function to generate link to data entry form for each point
 */
function getDataEntryLink($pid, $rec, $ev, $x_coord){

    global $redcap_version;
    //TODO: look up form name based on xcoord field name; for now just hard-code it
    $page = "measurements";
    //TODO: Add support for repeating instances later; for now just set intance=1
    $link = APP_PATH_WEBROOT_FULL."redcap_v{$redcap_version}"."/DataEntry/index.php?pid=$pid&id=$rec&page=$page&event_id=$ev&instance=1";
    return $link;
}

echo "<link rel='stylesheet' href='".$module->getUrl("css/scatterplot.css")."'>";
echo "<script src='https://cdn.plot.ly/plotly-latest.min.js'></script>";        
//echo "<PRE>".print_r($module->settings)."</PRE>";

//Loop through each configured scatter plot
for($i=0; $i < count($module->settings); $i++){
    echo "<h3 class='scatterplot-em'>Scatter Plot #".($i+1).": ".$module->settings[$i]["plot-title"]."</h3><div id='myDiv$i' class='scatterplot-em'></div>";    
    
    $pid = $module->getProjectId();
    $data = REDCap::getData($pid,'array',NULL,array($module->settings[$i]["x-coord"],$module->settings[$i]["y-coord"]));
    $x_coord = array();
    $y_coord = array();
    $links = array();
    $events = array();
    $records = array();
    foreach ($data as $rec => $ev_arr){
        foreach ($ev_arr as $ev => $val){
            array_push($x_coord,$val[$module->settings[$i]["x-coord"]]);
            array_push($y_coord,$val[$module->settings[$i]["y-coord"]]);
            array_push($records,$rec);
            array_push($events,$ev);
            array_push($links,getDataEntryLink($pid, $rec, $ev, $module->settings[$i]["x-coord"]));
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
        text: [
        <?php 
            for($j=0; $j<count($records); $j++ ){
                if($j > 0){
                    echo ",";
                }
                //Hide link in span tag so it doesn't cover the plot
                echo "\"<span style='display:none'>".$links[$j]."</span>\"";
      } ?>],            
        mode: 'markers',
        type: 'scatter'
      };
      
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

    var myPlot = document.getElementById('myDiv'+<?php echo $i; ?>);
    myPlot.on('plotly_click', function(data){
        var pts = '';
        var url = '';
        for(var i=0; i < data.points.length; i++){
            pts = 'x = '+data.points[i].x +'\ny = '+
                data.points[i].y + '\n\n';
            //Strip off the span tags that were inserted above
            url = jQuery(data.points[i].text).text();
        }
        //alert('Closest point clicked:\n\n'+pts);
        //location.href = txt;
        window.open(url);
    });

</script>
<?php
} //end for loop over all configured plots

// OPTIONAL: Display the project footer
require_once APP_PATH_DOCROOT . 'ProjectGeneral/footer.php';


