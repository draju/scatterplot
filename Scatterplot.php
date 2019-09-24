<?php
/**
 * REDCap External Module: Scatterplot
 * 
 * External Module for creating scatterplots using plotly.js
 * 
 * @author Vishnu Raju, Albert Einstein College of Medicine
 * @author Alexandre Peshansky, Albert Einstein College of Medicine
 */
namespace Einstein\Scatterplot;

use ExternalModules\AbstractExternalModule;
use ExternalModules\ExternalModules;

use REDCap;

class Scatterplot extends \ExternalModules\AbstractExternalModule {

    //Project settings
    //TODO: change to private after moving code to class 
    public $settings;

    function __construct()
    {
        parent::__construct();
        if($this->getProjectId()){
          $this->settings = $this->framework->getSubSettings("scatterplot");
        }
    }
    
    /** 
    * Embed scatterplot in data entry form for now - later move it to a plugin link
    */
    public function embedScatterplot(){
    }
    
    function validateSettings($settings){

        //TODO: Check that each x and y coordinate are validated for number/integers
        //echo "<PRE>".print_r($settings)."</PRE>";
        $current_pid = $this->getProjectId();

        //Check that each x-coordinate is validated as an integer or number
        //TODO: Currently does not support custom validation types which could also be numeric 
        for ($i=0; $i < count($settings["x-coord"]); $i++) {
            $sql_validation_type = "SELECT COUNT(*) ".
                                    "FROM redcap_metadata ".
                                    "WHERE project_id=$current_pid ".
                                    "AND element_validation_type IN ('int','float') ".
                                    "AND field_name='".db_escape($settings["x-coord"][$i])."'";
            $count_valid_type = db_result(db_query($sql_validation_type),0);
            if(!$count_valid_type){
                $setting_num = $i + 1;
                return "Setting #$setting_num: The field name '".REDCap::escapeHtml($settings["x-coord"][$i])."' ".
                "must be validated as number or integer to create a scatter plot";
            }
        } 

        //Check that each y-coordinate is validated as an integer or number
        //TODO: Currently does not support custom validation types which could also be numeric 
        for ($i=0; $i < count($settings["y-coord"]); $i++) {
            $sql_validation_type = "SELECT COUNT(*) ".
                                    "FROM redcap_metadata ".
                                    "WHERE project_id=$current_pid ".
                                    "AND element_validation_type IN ('int','float') ".
                                    "AND field_name='".db_escape($settings["y-coord"][$i])."'";
            $count_valid_type = db_result(db_query($sql_validation_type),0);
            if(!$count_valid_type){
                $setting_num = $i + 1;
                return "Setting #$setting_num: The field name '".REDCap::escapeHtml($settings["y-coord"][$i])."' ".
                "must be validated as number or integer to create a scatter plot";
            }
        } 


        return parent::validateSettings($settings);        
    }

    /** 
    * Returns index of setting if found.
    * Note that a value of zero is a valid index.
    */
    private function searchSettings($value,$key){
      foreach ($this->settings as $k => $val) {
        if ($val[$key] == $value) {
            return $k;
        }
      }
      return null;
    }

}