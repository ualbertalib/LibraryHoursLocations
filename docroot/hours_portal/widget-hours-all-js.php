<?php 

include('config_other.inc.php');

$directory = dirname(dirname(__FILE__));
require_once('functions.php');
header('content-type:application/x-javascript');
?>

var hourswidget = "";
hourswidget += "<div class=\"span5 left-side\">";
          
      <?php 
      $widget = displayLocationsStatusHomepage(); 
 ?>

       hourswidget+=<?php echo json_encode($widget); ?>;
      
hourswidget += "</div>";

document.write(hourswidget);