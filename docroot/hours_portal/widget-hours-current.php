<?php 
include('config_other.inc.php');
$directory = dirname(dirname(__FILE__));
require_once('functions.php');

          

      $widget = displayLocationsStatusWidget($_GET['locationid']); 

      echo $widget;
 ?>

