<?php
/* 
 * Write hours data to xml file for use in digital signage, etc. 
 
 	Usage:
 
	 All hours for all locations and all date ranges:
	- no parameters
	
	All hours and all date ranges for one location:
	- 'location' parameter required
	- 'location' parameter valid values are: 'asian', 'ikblc', 'koerner', 'law', 'woodward'
	- example query string: ?location=asian
	
	Current status for one location:
	- 'location' parameter required
	- 'timeframe' parameter required with value of 'current'
	- if 'location' is 'asian', will include open time for next day
	- example query string: ?location=asian&timeframe=current
	
	Today's hours for one location:
	- 'location' parameter required
	- 'timeframe' parameter required with value of 'today'
	- 'type' parameter is optional; if set to 'reference' will get reference hours instead of library (the default)
	- example query string: ?location=asian&timeframe=today&type=reference
	
	This week's hours for one location:
	- 'location' parameter required
	- 'timeframe' parameter required with value of 'week'
	- 'type' parameter is optional; if set to 'reference' will get reference hours instead of library (the default)
	- will return collapsed hours for the week by line, with separate days and hours for each line
	- example query string: ?location=law&timeframe=week
	
	Holiday hours for one location:
	- 'location' parameter required
	- 'holiday' parameter required with the value being the name of the holiday (i.e. Easter, BC Day, etc.)
	- the value of the 'holiday' parameter will be used to search date range descriptions and will retrieve collapsed hours for all date ranges that match by line, with separate days and hours for each line
	- there is a check to make sure the date ranges returned are in the "Holiday" category
	- example query string: ?location=law&holiday=christmas  
 
 */

// pull in dbConnect file
$directory = dirname((dirname(__FILE__)));
require_once($directory.'/dbConnect.php');
require_once($directory.'/functions.php');

// create DOM document and add XML root to it
$doc = new DOMDocument('1.0');
// we want a nice output
$doc->formatOutput = true;

$root = $doc->createElement('all_hours');
$root = $doc->appendChild($root);

// get URL parameters and set other parameters
$conditions = '';
$location_login = rv('location'); 
$timeframe = rv('timeframe');
$holiday = rv('holiday');
$type_name = rv('type',"Library"); 
$type_id = (strtolower($type_name) == "reference"?3:2);
	
$validLocations = array('asian', 'ikblc', 'koerner', 'law', 'woodward');
if(!empty($location_login) && (array_search($location_login, $validLocations) === false)) {
	$error = $doc->createElement('error');
	$error = $root->appendChild($error);
	$etext = $doc->createTextNode("Invalid location parameter passed.  Must be one of: 'asian', 'ikblc', 'koerner', 'law', 'woodward'.");
	$etext = $error->appendChild($etext);
	header('Content-Type: text/xml');
	$xml = $doc->saveXML() . "\n";
	echo $xml;
	exit();
} else {
	// used with code below timeframe specific code
	if(!empty($location_login)) {
		$location_id = getID($location_login);	
		$conditions .= " AND `HourGrouping`.`hour_location_id` = $location_id ";
	}
}

$validTimeframes = array('current', 'today', 'week');
if(!empty($timeframe) && (array_search($timeframe, $validTimeframes) === false)) {
	$error = $doc->createElement('error');
	$error = $root->appendChild($error);
	$etext = $doc->createTextNode("Invalid timeframe parameter passed.  Must be one of: 'current', 'today', 'week'.");
	$etext = $error->appendChild($etext);
	header('Content-Type: text/xml');
	$xml = $doc->saveXML() . "\n";
	echo $xml;
	exit();
} 
// get and write data for specific messages depending on timeframe sent

// data for current status message
if($timeframe == 'current' && !empty($location_login) && !empty($location_id)) {
	$ymd = date("Y-m-d");
	$time = date("H:i");	
	$location_name = getName($location_login);	
	$location_status = displayCurrentStatus($ymd, $time, $location_id);
			
	$location = $doc->createElement('location');
	$location = $root->appendChild($location);
	
	$name = $doc->createElement('name');
	$name = $location->appendChild($name);
	
	$ntext = $doc->createTextNode($location_name);
	$ntext = $name->appendChild($ntext);
	
	$status = $doc->createElement('status');
	$status = $location->appendChild($status);
	
	$stext = $doc->createTextNode($location_status);
	$stext = $status->appendChild($stext);
	
	// add next day open time for Asian since building may be open when library is closed
	if($location_login == 'asian') {
		// set date for next day to get next open time
			$nextymd = date('Y-m-d', ((strtotime($ymd) + 43200)));
			$hours_by_date = getHoursByDate($nextymd, $location_id, $type_id);
			$next_open_time = displayTime($hours_by_date[0]['open_time']);  
			
			$nextopen = $doc->createElement('next_open');
		$nextopen = $location->appendChild($nextopen);
		
		$notext = $doc->createTextNode($next_open_time);
		$notext = $nextopen->appendChild($notext);
	}
	
	header('Content-Type: text/xml');
	$xml = $doc->saveXML() . "\n";
	echo $xml;
	exit();
}

// data for todays hours for a locations
if($timeframe == 'today' && !empty($location_login) && !empty($location_id)) {
	$ymd = date("Y-m-d");
	$time = date("H:i");	
	$hours_by_date = getHoursByDate($ymd, $location_id, $type_id);
	$location_name = getName($location_login);	
	$location_status = displayCurrentStatus($ymd, $time, $location_id);
	
	$location = $doc->createElement('location');
	$location = $root->appendChild($location);
	
	$name = $doc->createElement('name');
	$name = $location->appendChild($name);
	
	$ntext = $doc->createTextNode($location_name);
	$ntext = $name->appendChild($ntext);
	
	$date = $doc->createElement('date');
	$date = $location->appendChild($date);
	
	$dtext = $doc->createTextNode(date("M j"));
	$dtext = $date->appendChild($dtext);
	
	$otime = $doc->createElement('open_time');
	$otime = $location->appendChild($otime);
	
	$ottext = $doc->createTextNode(displayTime($hours_by_date[0]['open_time']));
	$ottext = $otime->appendChild($ottext);
	
	$ctime = $doc->createElement('close_time');
	$ctime = $location->appendChild($ctime);
	
	$cttext = $doc->createTextNode(displayTime($hours_by_date[0]['close_time']));
	$cttext = $ctime->appendChild($cttext);
	
	$closed = $doc->createElement('is_closed');
	$closed = $location->appendChild($closed);
	
	$clsdtext = $doc->createTextNode($hours_by_date[0]['is_closed']?"Closed":"Open");
	$clsdtext = $closed->appendChild($clsdtext);
			
	header('Content-Type: text/xml');
	$xml = $doc->saveXML() . "\n";
	echo $xml;
	exit();
}

if($timeframe == 'week' && !empty($location_login)) {
	$location_name = getName($location_login);
	$location = $doc->createElement('location');
	$location = $root->appendChild($location);
	
	$name = $doc->createElement('name');
	$name = $location->appendChild($name);
	
	$ntext = $doc->createTextNode($location_name);
	$ntext = $name->appendChild($ntext);
	
	/*** Code copied from various parts of widget-hours-js.php ***/
	// set current year, week, day, date, time
	$currentyear = date('Y');
	$currentweek = date('W');
	$currentdate = date('Y-m-d');
	$currentday = date('l', strtotime($currentdate));
	$currenttime = date('H:i:s');
	
	// for testing different dates, times
	//$currentyear = '2012';
	//$currentweek = '15';
	//$currentdate = '2011-12-10';
	//$currentday = date('l', strtotime($currentdate));
	//$currenttime = '01:00:00';		
	
	// find first monday of the year
	$firstmon = strtotime("mon jan {$currentyear}");
	
	// weeks offset is always one less than current week
	$weeksoffset = $currentweek - 1;
	
	// calculate this week's monday
	$thismon = date( 'Y-m-d', strtotime("+{$weeksoffset} week " . date('Y-m-d', $firstmon)) );
	
	// put the week's dates in an array
    $ymd = array($thismon, date('Y-m-d', (strtotime($thismon) + (1*86400))), date('Y-m-d', (strtotime($thismon) + (2*86400))), date('Y-m-d', (strtotime($thismon) + (3*86400))), date('Y-m-d', (strtotime($thismon) + (4*86400))), date('Y-m-d', (strtotime($thismon) + (5*86400))), date('Y-m-d', (strtotime($thismon) + (6*86400))) );	
    $weeklyhours = array();	  
    // pull in each days hours and add it to a weekly array  
    for ($i = 0; $i < 7; $i++) {	    
    	// grab hours based on date and location (library hours)
   		// returns: OPEN_TIME, CLOSE_TIME, IS_CLOSED, TYPE, CATEGORY
    	$dailyhours = getHoursByDate($ymd[$i], $location_id, $type_id);
    	array_push($weeklyhours, $dailyhours);
  	}//closes for
  	
	  // set up match as false outside the loop
  $match = false;

  // display week's hours, collapsing where appropriate
  for ($i = 0; $i < 7; $i++) {	    
    // variables to compare the next values
    $next_open = isset($weeklyhours[$i+1][0]['open_time']) ? $weeklyhours[$i+1][0]['open_time'] : '0';
    $next_close = isset($weeklyhours[$i+1][0]['close_time']) ? $weeklyhours[$i+1][0]['close_time'] : '0';
    $next_closed = isset($weeklyhours[$i+1][0]['is_closed']) ? $weeklyhours[$i+1][0]['is_closed'] : '0';
    
    // if the next set of hours is the same, set the range start date, change match to true, break the loop
    if ($weeklyhours[$i][0]['open_time'] == $next_open && $weeklyhours[$i][0]['close_time'] == $next_close && $weeklyhours[$i][0]['is_closed'] == $next_closed && $match == false) {	    
      $start_range = date('D', strtotime($ymd[$i]));
      $match = true;
      continue;	    
    // if the next set of hours is the same AGAIN, just skip to the next one
    } else if ($weeklyhours[$i][0]['open_time'] == $next_open && $weeklyhours[$i][0]['close_time'] == $next_close && $weeklyhours[$i][0]['is_closed'] == $next_closed && $match == true) {	    
      continue;	    
    // otherwise, add the hours to the line 
    } else {	
      // when a range has been set, display it
      if ($match == true) {
		$daystext = $start_range.'-'.date('D', strtotime($ymd[$i]));
      } else {
		$daystext = date('l', strtotime($ymd[$i]));
      }//closes if-else
      // now display the hours
	  if ($weeklyhours[$i][0]['is_closed'] == 1) {
		$hourstext = 'Closed';
	  } else if ($weeklyhours[$i][0]['is_closed'] == 0 && $weeklyhours[$i][0]['open_time'] == $weeklyhours[$i][0]['close_time']) {
		$hourstext = 'Open 24 Hours';
      } else {
		$hourstext = displayTime($weeklyhours[$i][0]['open_time']).'-'.displayTime($weeklyhours[$i][0]['close_time']);
	  }//closes if-elseif-else
    }//closes if-elseif-else
    
    // write line to XML file
    $line = $doc->createElement('line');
	$line = $location->appendChild($line);
	
	$days = $doc->createElement('days');
	$days = $line->appendChild($days);
	
	$dtext = $doc->createTextNode($daystext);
	$dtext = $days->appendChild($dtext);
	
	$hours = $doc->createElement('hours');
	$hours = $line->appendChild($hours);
	
	$htext = $doc->createTextNode($hourstext);
	$htext = $hours->appendChild($htext);
	   
	//reset match to false to start the loop over
	$match = false;
	    
	}//closes for
	
	header('Content-Type: text/xml');
	$xml = $doc->saveXML() . "\n";
	echo $xml;
	exit();
} // end if timeframe = week


// XML for holiday hours - category must be holiday
if(!empty($holiday) && !empty($location_id)) {
	$sql = "SELECT hour_days.day_of_week, hour_days.open_time, hour_days.close_time, hour_days.is_closed, hour_days.is_tbd, hour_groupings.hour_category_id
      FROM hour_days
      JOIN hour_groupings
      ON hour_days.hour_grouping_id = hour_groupings.id
      JOIN hour_date_ranges
      ON hour_groupings.hour_date_range_id = hour_date_ranges.id
      WHERE hour_days.hour_grouping_id IN (
            SELECT hour_groupings.id
            FROM hour_date_ranges
            JOIN hour_groupings
            ON hour_groupings.hour_date_range_id = hour_date_ranges.id
            WHERE ( hour_date_ranges.description LIKE '%$holiday%' )
            AND hour_groupings.hour_type_id = 2
            AND hour_groupings.hour_location_id = $location_id )
      ORDER BY hour_days.day_of_week ASC";

	  $sth = $dbh->prepare($sql);
	  $status = $sth->execute();
	  
	  if($sth->errorCode() !== '00000'){
	    var_export($sth->errorInfo());
	  }//closes if errorCode…
	  
	  $results = $sth->fetchAll();
	  $count = count($results);
	  
	if(!$count) {
		$error = $doc->createElement('error');
		$error = $root->appendChild($error);
		$etext = $doc->createTextNode("No hours found based on holiday: '$holiday'");
		$etext = $error->appendChild($etext);
		header('Content-Type: text/xml');
		$xml = $doc->saveXML() . "\n";
		echo $xml;
		exit();
	} 
	// make sure is holiday 
	if($results[0]['hour_category_id'] != 5) {
		$error = $doc->createElement('error');
		$error = $root->appendChild($error);
		$etext = $doc->createTextNode("The holiday parameter sent: '$holiday', is not a holiday.");
		$etext = $error->appendChild($etext);
		header('Content-Type: text/xml');
		$xml = $doc->saveXML() . "\n";
		echo $xml;
		exit();
	} 

	$location_name = getName($location_login);
 
	// add holiday name and location name to xml  
	$holiday_name = $doc->createElement('holiday');
	$holiday_name = $root->appendChild($holiday_name);
	
	$htext = $doc->createTextNode(ucfirst($holiday));
	$htext = $holiday_name->appendChild($htext); 
	
	$location = $doc->createElement('location');
	$location = $root->appendChild($location);
	
	$name = $doc->createElement('name');
	$name = $location->appendChild($name);
	
	$ntext = $doc->createTextNode($location_name);
	$ntext = $name->appendChild($ntext);	
	
	/* ******* Below option is to output line by line, collapsed hours ******* */
	
	// matching hours set to false
	$match = false;
					
	// display hours, collapsing where appropriate
    for ($i = 0; $i < $count; $i++) {		      
      // variables to compare the next/prev values
      $next_open = isset($results[$i+1]['open_time']) ? $results[$i+1]['open_time'] : '0';
      $next_close = isset($results[$i+1]['close_time']) ? $results[$i+1]['close_time'] : '0';
      $next_closed = isset($results[$i+1]['is_closed']) ? $results[$i+1]['is_closed'] : '';
      //$next_month = isset(date('M', strtotime($results[$i+1]['day_of_week']))) ? date('M', strtotime($results[$i+1]['day_of_week'])) : '';
      
      // if the next set of hours is the same, and month is the same, set the range start date, change match to true, break the loop
      if ($results[$i]['open_time'] == $next_open && $results[$i]['close_time'] == $next_close && $results[$i]['is_closed'] == $next_closed && $match == false) {
          // assuming 'holiday' is category
          $start_range = date('M j', strtotime($results[$i]['day_of_week']));
          $match = true;
  	      continue;		      
      // if the next set of hours is the same AGAIN, just skip to the next one
      } else if ($results[$i]['open_time'] == $next_open && $results[$i]['close_time'] == $next_close && $results[$i]['is_closed'] == $next_closed && $match == true) {		      
        continue;		      
      // otherwise, display the hours  
      } else {		        
        // when a range has been set, display it
        if ($match == true) {		        
          $daystext = $start_range.'-';		          
          // exception/holiday hours display as dates
          if(date('M', strtotime($results[$i]['day_of_week'])) == date('M', strtotime($start_range))) {
          	$daystext .= date('j', strtotime($results[$i]['day_of_week']));
          } else {
          	$daystext .= date('M j', strtotime($results[$i]['day_of_week']));	
          }		        
        // otherwise, just display the current date
        } else {		        
          // holiday hours display as dates
          $daystext = date('M j', strtotime($results[$i]['day_of_week']));		        
        }//closes if-else		        
        
        // now display the hours (uses displayTime function to trim zero minutes)
        if ($results[$i]['is_tbd'] == 1) {
          $hourstext = 'TBD';
        } else if ($results[$i]['is_closed'] == 1) {
          $hourstext = 'Closed';
        } else if ($results[$i]['is_closed'] == 0 && $results[$i]['open_time'] == $results[$i]['close_time']) {
          $hourstext = 'Open 24 Hours';
        } else {
          $hourstext = displayTime($results[$i]['open_time']).' - '.displayTime($results[$i]['close_time']);
        }//closes if-else
        
        // write line to XML file
	    $line = $doc->createElement('line');
		$line = $location->appendChild($line);
		
		$days = $doc->createElement('days');
		$days = $line->appendChild($days);
		
		$dtext = $doc->createTextNode($daystext);
		$dtext = $days->appendChild($dtext);
		
		$hours = $doc->createElement('hours');
		$hours = $line->appendChild($hours);
		
		$htext = $doc->createTextNode($hourstext);
		$htext = $hours->appendChild($htext);
        
        //reset match to false to start the loop over
        $match = false;
        
      }//closes if
      
    }//closes for
	
	/* ******* Below is option to put each date's hours in the xml  *******
	$days = $doc->createElement('hour_days');
	$days = $location->appendChild($days);	
	
	// add hour days to xml
	foreach($results as $hour_day) {			
		$day = $doc->createElement('hour_day');
		$day = $days->appendChild($day);
		
		$dow = $doc->createElement('day_of_week');
		$dow = $day->appendChild($dow);
		
        $day_of_week = date('M j', strtotime($hour_day['day_of_week']));
        	
		$ctext = $doc->createTextNode($day_of_week);
		$ctext = $dow->appendChild($ctext);
		
		$otime = $doc->createElement('open_time');
		$otime = $day->appendChild($otime);
		
		$ottext = $doc->createTextNode(displayTime($hour_day['open_time']));
		$ottext = $otime->appendChild($ottext);
		
		$ctime = $doc->createElement('close_time');
		$ctime = $day->appendChild($ctime);
		
		$cttext = $doc->createTextNode(displayTime($hour_day['close_time']));
		$cttext = $ctime->appendChild($cttext);
		
		$closed = $doc->createElement('is_closed');
		$closed = $day->appendChild($closed);
		
		$clsdtext = $doc->createTextNode($hour_day['is_closed']?"Closed":"Open");
		$clsdtext = $closed->appendChild($clsdtext);
		
		$tbd = $doc->createElement('is_tbd');
		$tbd = $day->appendChild($tbd);
		
		$tbdtext = $doc->createTextNode($hour_day['is_tbd']?"TBD":"");
		$tbdtext = $tbd->appendChild($tbdtext);			
	} // end foreach results as day
	*/

	header('Content-Type: text/xml');
	$xml = $doc->saveXML() . "\n";
	echo $xml;
	exit();
			  
} // end if holiday
	
// if no timeframe or holiday passed
if(empty($timeframe) && empty($holiday))  {	

	// get hour groupings data
	$hour_grouping_sql = "
	SELECT `HourGrouping`.`id`, `HourGrouping`.`hour_date_range_id`, `HourGrouping`.`hour_location_id`, `HourGrouping`.`hour_type_id`, `HourGrouping`.`hour_category_id`, `HourGrouping`.`modified_by`, `HourGrouping`.`modified_timestamp`, `HourDateRange`.`description`, `HourDateRange`.`begin_date`, `HourDateRange`.`end_date`, `HourDateRange`.`modified_by`, `HourDateRange`.`modified_timestamp`, `HourLocation`.`name`, `HourType`.`type`, `HourCategory`.`category` 
	FROM `hour_groupings` AS `HourGrouping` 
	LEFT JOIN `hour_date_ranges` AS `HourDateRange` ON (`HourGrouping`.`hour_date_range_id` = `HourDateRange`.`id`) 
	LEFT JOIN `hour_locations` AS `HourLocation` ON (`HourGrouping`.`hour_location_id` = `HourLocation`.`id`) 
	LEFT JOIN `hour_types` AS `HourType` ON (`HourGrouping`.`hour_type_id` = `HourType`.`id`) 
	LEFT JOIN `hour_categories` AS `HourCategory` ON (`HourGrouping`.`hour_category_id` = `HourCategory`.`id`)
	WHERE `HourLocation`.`display` = 1
	$conditions
	ORDER BY `HourLocation`.`display_position`, `HourDateRange`.`begin_date`, `HourDateRange`.`end_date`"; 
	
	$sth = $dbh->prepare($hour_grouping_sql);
	$status = $sth->execute();
	$hour_grouping_results = $sth->fetchAll();
	$hour_grouping_count = count($hour_grouping_results);
	
	// for each hour grouping, write data for all date ranges, types, and categories
	foreach($hour_grouping_results as $hour_grouping) {
		//print_r($hour_grouping);
		$hour_grouping_id = $hour_grouping['id'];
		// get hour days data
		$hour_days_sql = "	
		SELECT `HourDay`.`id`, `HourDay`.`hour_grouping_id`, `HourDay`.`day_of_week`, `HourDay`.`open_time`, `HourDay`.`close_time`, `HourDay`.`is_closed`, `HourDay`.`is_tbd`, `HourDay`.`modified_by`, `HourDay`.`modified_timestamp` 
		FROM `hour_days` AS `HourDay` 
		WHERE `HourDay`.`hour_grouping_id` = $hour_grouping_id";
		
		$sth = $dbh->prepare($hour_days_sql);
		$status = $sth->execute();
		$hour_days_results = $sth->fetchAll();
		$hour_days_count = count($hour_days_results);
		
		// add all hour grouping data to xml doc
		$group = $doc->createElement('hour_grouping');
		$group = $root->appendChild($group);
		
		$groupid = $doc->createAttribute('id');
		$groupid->value = $hour_grouping_id;
		$group->appendChild($groupid);
		
		$location = $doc->createElement('hour_location');
		$location = $group->appendChild($location);
		
		$locationid = $doc->createAttribute('id');
		$locationid->value = $hour_grouping['hour_location_id'];
		$location->appendChild($locationid);
		
		$ltext = $doc->createTextNode($hour_grouping['name']);
		$ltext = $location->appendChild($ltext);
		
		$type = $doc->createElement('hour_type');
		$type = $group->appendChild($type);
		
		$typeid = $doc->createAttribute('id');
		$typeid->value = $hour_grouping['hour_type_id'];
		$type->appendChild($typeid);
		
		$ttext = $doc->createTextNode($hour_grouping['type']);
		$ttext = $type->appendChild($ttext);
		
		$category = $doc->createElement('hour_category');
		$category = $group->appendChild($category);
		
		$categoryid = $doc->createAttribute('id');
		$categoryid->value = $hour_grouping['hour_category_id'];
		$category->appendChild($categoryid);
		
		$ctext = $doc->createTextNode($hour_grouping['category']);
		$ctext = $category->appendChild($ctext);
		
		$dates = $doc->createElement('hour_date_range');
		$dates = $group->appendChild($dates);
		
		$datesid = $doc->createAttribute('id');
		$datesid->value = $hour_grouping['hour_date_range_id'];
		$dates->appendChild($datesid);
		
		$begin = $doc->createElement('begin_date');
		$begin = $dates->appendChild($begin);
		
		$btext = $doc->createTextNode($hour_grouping['begin_date']);
		$btext = $begin->appendChild($btext);
		
		$end = $doc->createElement('end_date');
		$end = $dates->appendChild($end);
		
		$etext = $doc->createTextNode($hour_grouping['end_date']);
		$etext = $end->appendChild($etext);
		
		$desc = $doc->createElement('description');
		$desc = $dates->appendChild($desc);
		
		$dtext = $doc->createTextNode($hour_grouping['description']);
		$dtext = $desc->appendChild($dtext);
		
		$days = $doc->createElement('hour_days');
		$days = $group->appendChild($days);
		
		foreach($hour_days_results as $hour_day) {	
			$day = $doc->createElement('hour_day');
			$day = $days->appendChild($day);
			
			$dayid = $doc->createAttribute('id');
			$dayid->value = $hour_day['id'];
			$day->appendChild($dayid);
			
			$dow = $doc->createElement('day_of_week');
			$dow = $day->appendChild($dow);
			
			// exception/holiday hours display as dates, regular hours display as days
	        if ($hour_grouping['category'] == 'Holiday' || $hour_grouping['category'] == 'Exception') {
	          $day_of_week = date('M j', strtotime($hour_day['day_of_week']));
	        } else {
	          $day_of_week = $hour_day['day_of_week'];
	        }		
			$ctext = $doc->createTextNode($day_of_week);
			$ctext = $dow->appendChild($ctext);
			
			$otime = $doc->createElement('open_time');
			$otime = $day->appendChild($otime);
			
			$ottext = $doc->createTextNode(displayTime($hour_day['open_time']));
			$ottext = $otime->appendChild($ottext);
			
			$ctime = $doc->createElement('close_time');
			$ctime = $day->appendChild($ctime);
			
			$cttext = $doc->createTextNode(displayTime($hour_day['close_time']));
			$cttext = $ctime->appendChild($cttext);
			
			$closed = $doc->createElement('is_closed');
			$closed = $day->appendChild($closed);
			
			$clsdtext = $doc->createTextNode($hour_day['is_closed']?"Closed":"Open");
			$clsdtext = $closed->appendChild($clsdtext);
			
			$tbd = $doc->createElement('is_tbd');
			$tbd = $day->appendChild($tbd);
			
			$tbdtext = $doc->createTextNode($hour_day['is_tbd']?"TBD":"");
			$tbdtext = $tbd->appendChild($tbdtext);
			
		} // end foreach hour_day
		
	} // end foreach hour_grouping
	
	//echo "Saving all the document:\n";
	
	header('Content-Type: text/xml');
	$xml = $doc->saveXML() . "\n";
	echo $xml;
	exit();
	
}
?>