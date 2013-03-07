<?php
/*
* Return days of week NOT occuring during the date range specified by the incoming date range id
*/

session_start();
require_once(dirname(__FILE__).'/../../config');
require_once(LIB . '/db.inc.php');
include_once(LIB . '/utility.inc.php');

global $staffdb;

$alldays = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');

$hour_date_range_id = $_GET['daterangeid'];
/*
if(!isset($_GET['categoryid']) || empty($_GET['categoryid'])) {
       $hour_category_id = 1;
} else {
       $hour_category_id = $_GET['categoryid'];
}
*/
if(!isset($_GET['sortby']) || empty($_GET['sortby'])) {
       $sortby = "weekday";
} else {
       $sortby = $_GET['sortby'];
}

$sql = "SELECT `begin_date`,`end_date`,`hour_category_id`, `hour_categories`.`category` 
		FROM `hour_date_ranges` 
		JOIN `hour_categories` ON (`hour_categories`.`id` = `hour_date_ranges`.`hour_category_id`)
		WHERE `hour_date_ranges`.`id` = $hour_date_range_id";
$range = $staffdb->getArray($sql);
if($range) {
       $begin_str = $range[0]['begin_date'];
       $end_str = $range[0]['end_date'];
}
$category_id = $range[0]['hour_category_id'];
/*
if($range[0]['category'] == 'Summer Alternate') { 
	$category = 'summer-alternate'; 
} else {
*/
$category = $range[0]['category'];
$begin = new DateTime($begin_str);
$end = new DateTime($end_str);
$daysin = array();
$datesin = array();
while($begin <= $end) {
       $daysin[] = date('l', $begin->format('U'));
       if($category_id == 5 || $category_id == 7) {
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

$days_of_week=array('daysout'=>$daysout,'daysin'=>$daysin,'datesin'=>$datesin,'categoryid'=>$category_id,'category'=>$category);
header('Content-type: text/javascript');
echo $_GET['callback'].'('.json_encode($days_of_week).');';

?>
