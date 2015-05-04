<?php 
include('config_other.inc.php');
$directory = dirname(dirname(__FILE__));
require_once('functions.php');

          

      $widget = displayLocationsStatusWidget(); 


      echo "function getHoursLocationStatus(id){ \n";
      echo  "var location = " . trim($widget) . "\n";
      echo "return location[id];
      	}
      ";
      

 ?>