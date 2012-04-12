<?php
/*
* Returns days of week occuring and NOT occuring during the date range specified by the incoming date range id
* For Holiday and Exception category hours, also rreturns dates in the date range
*/

session_start();
require_once(dirname(__FILE__).'/../../config');
require_once(LIB . '/db.inc.php');
include_once(LIB . '/utility.inc.php');

global $staffdb;

$alldays = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');

$hour_date_range_id = $_GET['daterangeid'];
if(!isset($_GET['categoryid']) || empty($_GET['categoryid'])) {
       $hour_category_id = 1;
} else {
       $hour_category_id = $_GET['categoryid'];
}
if(!isset($_GET['sortby']) || empty($_GET['sortby'])) {
       $sortby = "weekday";
} else {
       $sortby = $_GET['sortby'];
}

$sql = "SELECT `begin_date`, `end_date` FROM `hour_date_ranges` WHERE `id` = $hour_date_range_id";
$range = $staffdb->getArray($sql);
if($range) {
       $begin_str = $range[0]['begin_date'];
       $end_str = $range[0]['end_date'];
}
$begin = new DateTime($begin_str);
$end = new DateTime($end_str);
$daysin = array();
$datesin = array();
while($begin <= $end) {
       $daysin[] = date('l', $begin->format('U'));
       // for holiday (5) and exception (7) hours, include dates we well as day of week
       if($hour_category_id == 5 || $hour_category_id == 7) {
               $datesin[] = $begin->format('Y-m-d');
       }
       $begin->modify('+1 day');
}
$daysout = array();
$daysins = array();

if($sortby == "weekday") {
       //sorted by day of week
       foreach($alldays as $day) {
               if(!in_array($day,$daysin)) {
                       $daysout[] = $day;
               } else {
                       $daysins[] = $day;
               }
       }
       $daysin = $daysins;
}

$days_of_week=array('daysout'=>$daysout,'daysin'=>$daysin,'datesin'=>$datesin);
header('Content-type: text/javascript');
echo $_GET['callback'].'('.json_encode($days_of_week).');';

?>
