<?php
// pull in dbConnect file
$directory = (dirname(__FILE__));
require_once($directory.'/dbConnect.php');


//**** HOURS PORTAL NON-QUERY FUNCTIONS ****


// remove zero minutes from open/close times
function displayTime($time) {

  $time = strtotime($time);
  $trimtime = date('g', $time);
  
  if (date('i', $time) != "00") {
    $trimtime .= date(':ia', $time);
  } else {
    $trimtime .= date('a', $time);
  }//closes if-else
  
  return $trimtime;
  
}//closes function


// Rod's request check function
function rv($key, $default=null) {
  
	if (isset($_REQUEST[$key])) {
		return $_REQUEST[$key];
	} else {
		return $default;
	}//closes if-else

}//closes function


// double-check that passed hidden values are (relatively) safe
function sanitize($string) {
	
	$string = strip_tags($string);
	$string = htmlentities($string);
	$string = stripslashes($string);

  return $string;

}//closes function


//**** HOURS PORTAL QUERY FUNCTIONS ****//


//**** Left-Side Table ****//


// retrieve and display locations and status in a dl based on (optional) branch id
function displayLocationsStatus($branchID = null) {
  
  global $dbh;
  
  if ($branchID == null) {
    $hourlocation = "";
  } else if ($branchID != null && is_int($branchID)) {
    $hourlocation = "AND hour_locations.id = $branchID";
  }//closes if-else
  
  $currentdate = date('Y-m-d');
  $currenttime = date('H:i:s');
  
  // for testing other dates and times
  //$currentdate = '2012-04-09';
  //$currenttime = '13:30:00';
  
  $sql = "SELECT hour_locations.id, hour_locations.name, hour_locations.parent_hour_location_id AS parent, hour_locations.login
          FROM hour_locations
          WHERE hour_locations.display = 1
          $hourlocation
          ORDER BY hour_locations.display_position";

  $sth = $dbh->prepare($sql);
  $status = $sth->execute();
  $results = $sth->fetchAll();
  $count = count($results);
  
  // alternating rows start with false (white row first)
  $alt = false;
  
  // variable to store code string
  $locationstable = '<dl id="locations-table">

      <span class="headers">
        <dt>Location</dt>
        <dd>Today</dd>
      </span>
      ';
  
  for ($i = 0; $i < $count; $i++) {

    // set the unique identifier as the login name (shorthanded library name)
    $id = $results[$i]['login'];
    
    // start the display with appropriate span tag (inserts alternating rows)
    if ($alt == false && $results[$i]['parent'] == NULL) {
      $locationstable .= '
      <a href="#view-'.$id.'" title="Click to see hours for this location"><span class="slide-out '.$id.'">';
      $alt = true;
    } else if ($alt == true && $results[$i]['parent'] == NULL) {
      $locationstable .= '
      <a href="#view-'.$id.'" title="Click to see hours for this location"><span class="alt slide-out '.$id.'">';
      $alt = false;
    } else if ($alt == false && $results[$i]['parent'] != NULL){
      $locationstable .= '
      <a href="#view-'.$id.'" title="Click to see hours for this location"><span class="alt slide-out '.$id.' sublevel">';
    } else if ($alt == true && $results[$i]['parent'] != NULL){
      $locationstable .= '
      <a href="#view-'.$id.'" title="Click to see hours for this location"><span class="slide-out '.$id.' sublevel">';
    }//closes if-elseif
    
    // add image
    $locationstable .= '<dt><img src="img/arrow.png" height="43" width="30" />';
    
    // indent "hack" for sublevels (padding/margin breaks display)
    if ($results[$i]['parent'] == NULL) {
      
      $locationstable .= $results[$i]['name'];
    
    } else {
      
      $locationstable .= '&nbsp; &nbsp; '.$results[$i]['name'];
      
    }//closes if-else
    
    // add status (uses displayCurrentStatus function to retrieve and calculate status)
    $locationstable .= '</dt><dd>'.displayCurrentStatus($currentdate, $currenttime, $results[$i]['id']).'</dd></span></a>';

  }//closes for
  
  // close list
  $locationstable .= '
    </dl>';
  
  return $locationstable;
  
}//closes function


// retrieve and display locations and status in a dl based on (optional) branch id for the homepage
function displayLocationsStatusHomepage($branchID = null) {
  
  global $dbh;
  
  if ($branchID == null) {
    $hourlocation = "";
  } else if ($branchID != null && is_int($branchID)) {
    $hourlocation = "AND hour_locations.id = $branchID";
  }//closes if-else
  
  $currentdate = date('Y-m-d');
  $currenttime = date('H:i:s');
  
  $sql = "SELECT hour_locations.id, hour_locations.name, hour_locations.parent_hour_location_id AS parent, hour_locations.login
          FROM hour_locations
          WHERE hour_locations.display = 1
          $hourlocation
          ORDER BY hour_locations.display_position";

  $sth = $dbh->prepare($sql);
  $status = $sth->execute();
  $results = $sth->fetchAll();
  $count = count($results);
  
  // alternating rows start with false (white row first)
  $alt = false;
  
  // variable to store code string
  $locationstable = '<dl id="locations-table"><span class="headers"><dt>Location</dt><dd>at '.date('g:i a').'</dd></span>';
  
  for ($i = 0; $i < $count; $i++) {
    
    $id = $results[$i]['login'];
    $url = 'http://hours.library.ubc.ca/#view-'.$results[$i]['login'];
    
    // start the display with appropriate span tag (inserts alternating rows)
    if ($alt == false && $results[$i]['parent'] == NULL) {
      $locationstable .= '<a href="'.$url.'" title="Click to see this location"><span class="slide-out '.$id.'">';
      $alt = true;
    } else if ($alt == true && $results[$i]['parent'] == NULL) {
      $locationstable .= '<a href="'.$url.'" title="Click to see this location"><span class="alt slide-out '.$id.'">';
      $alt = false;
    } else if ($alt == false && $results[$i]['parent'] != NULL){
      $locationstable .= '<a href="'.$url.'" title="Click to see this location"><span class="alt slide-out sublevel '.$id.'">';
    } else if ($alt == true && $results[$i]['parent'] != NULL){
      $locationstable .= '<a href="'.$url.'" title="Click to see this location"><span class="slide-out sublevel '.$id.'">';
    }//closes if-elseif
    
    // add image
    $locationstable .= '<dt>';
    
    // indent "hack" for sublevels (padding/margin breaks display)
    if ($results[$i]['parent'] == NULL) {
      
      $locationstable .= $results[$i]['name'];
    
    } else {
      
      $locationstable .= '&nbsp; &nbsp; '.$results[$i]['name'];
      
    }//closes if-else
    
    // add status (uses displayCurrentStatus function to retrieve and calculate status)
    $locationstable .= '</dt><dd>'.displayCurrentStatus($currentdate, $currenttime, $results[$i]['id']).'</dd></span></a>';

  }//closes for
  
  // close list
  $locationstable .= '</dl>';
  
  // deal with apostrophe in St. Paul's
  return str_replace("'", "\'", $locationstable);
  
}//closes function


// retrieve and display the current status in span tags based on a date, time and (required) branch id
function displayCurrentStatus($ymd, $time, $branchID) {
  
  global $dbh;
  
  // find day of week for given date
  $dayofweek = date('l', strtotime($ymd));

  $sql = "SELECT hour_days.open_time, hour_days.close_time, hour_days.is_closed, hour_days.is_tbd
          FROM hour_locations
          JOIN hour_groupings
          ON hour_locations.id=hour_groupings.hour_location_id
          JOIN hour_days
          ON hour_groupings.id=hour_days.hour_grouping_id
          WHERE hour_days.hour_grouping_id IN (
                SELECT hour_groupings.id
                FROM hour_groupings
                JOIN hour_date_ranges
                ON hour_groupings.hour_date_range_id = hour_date_ranges.id
                WHERE ( hour_date_ranges.begin_date <= '$ymd' AND '$ymd' <= hour_date_ranges.end_date )
                AND hour_groupings.hour_type_id = 2
                AND hour_groupings.hour_location_id = $branchID )
          AND hour_locations.display = 1
          AND ( hour_days.day_of_week = '$ymd' OR hour_days.day_of_week = '$dayofweek' )
          ORDER BY hour_groupings.hour_category_id DESC";

  $sth = $dbh->prepare($sql);
  $status = $sth->execute();
  $results = $sth->fetchAll();
  
  // set date for previous day (for checking previous day early morning closures)
  $prevymd = date('Y-m-d', ((strtotime($ymd) - 43200)));
    
  // find day of week for previous date
  $prevdayofweek = date('l', strtotime($prevymd));

  $prevsql = "SELECT hour_days.open_time, hour_days.close_time, hour_days.is_closed
              FROM hour_locations
              JOIN hour_groupings
              ON hour_locations.id=hour_groupings.hour_location_id
              JOIN hour_days
              ON hour_groupings.id=hour_days.hour_grouping_id
              WHERE hour_days.hour_grouping_id IN (
                    SELECT hour_groupings.id
                    FROM hour_groupings
                    JOIN hour_date_ranges
                    ON hour_groupings.hour_date_range_id = hour_date_ranges.id
                    WHERE ( hour_date_ranges.begin_date <= '$prevymd' AND '$prevymd' <= hour_date_ranges.end_date )
                    AND hour_groupings.hour_type_id = 2
                    AND hour_groupings.hour_location_id = $branchID )
              AND hour_locations.display = 1
              AND ( hour_days.day_of_week = '$prevymd' OR hour_days.day_of_week = '$prevdayofweek' )
              ORDER BY hour_groupings.hour_category_id DESC";

  $prevsth = $dbh->prepare($prevsql);
  $prevstatus = $prevsth->execute();
  $prevresults = $prevsth->fetchAll();

  // close time default is today's close time
  $close_time = $results[0]['close_time'];
  
  // if the previous close time falls between 12-4am, current day is not open 24 hours and current time is prior to today's opening, change close time to previous day's close time
  if ( (0 < $prevresults[0]['close_time'] && $prevresults[0]['close_time'] < 4) && ($results[0]['open_time'] != $results[0]['close_time'] && $results[0]['is_closed'] != 1) && $time < $results[0]['open_time'] ) {
 
    $close_time = $prevresults[0]['close_time'];
  
  // if previous day was closed or previous day's closing hours were in the afternoon and today's hours are after midnight and current time is prior to today's opening, change close time to midnight (to force "closed")
  } else if ( ($prevresults[0]['is_closed'] == 1 || ( ((12 < $prevresults[0]['close_time'] && $prevresults[0]['close_time'] < 24) || $prevresults[0]['close_time'] == 0) && (0 < $results[0]['close_time'] && $results[0]['close_time'] < 4) )) && $time < $results[0]['open_time']) {
    
    $close_time = '00:00:00';
    
  }//closes if-elseif
  
  // variable to store code string
  $currentstatus = '';
  
  //if no hours were found
  if (!$results) {
    
    $currentstatus .= '<span class="closed">N/A</span> currently';
  
  } else {

    // check for closed or open status on first result (which = highest category id)
    if ($close_time > $results[0]['open_time']) {
    
      // for closing hours before midnight (uses displayTime function to trim zero minutes)
      if ( ($results[0]['is_closed'] == 1) || ($results[0]['open_time'] > $time || $close_time <= $time) ) {
        $currentstatus .= '<span class="closed">Closed</span> currently';
      } else {
        $currentstatus .= '<span class="open">Open</span> until '.displayTime($close_time);
      }//closes if-else
      
    // for TBD time  
    } else if ($results[0]['is_tbd'] == 1) {

      $currentstatus .= '<span class="closed">N/A</span> currently';
    
    // for 24-hour time  
    } else if ($close_time == $results[0]['open_time'] && $results[0]['is_closed'] == 0) {
    
      $currentstatus .= '<span class="open">Open</span> 24 hrs';
    
    } else {
    
      // for closing hours on or after midnight (uses displayTime function to trim zero minutes)
      if ( ($results[0]['is_closed'] == 1) || ($close_time <= $time && $results[0]['open_time'] > $time) ) {
        $currentstatus .= '<span class="closed">Closed</span> currently';
      } else {
        $currentstatus .= '<span class="open">Open</span> until '.displayTime($close_time);
      }//closes if-else
    
    }//closes if-elseif-else
  
  }//closes if-else
  
  return $currentstatus;
  
}//closes function


//**** Location Info (Right-Side Panels) ****//


// get array of branch info based on (optional) name id
// returns: ID, NAME, DESCRIPTION, ADDRESS, PHONE, URL, HOUR_NOTES, MAP_CODE, LOGIN, BUILDING
function getBranchInfo($nameID = null) {
  
  global $dbh;
  
  if ($nameID == null) {
    $hourlocation = "";
  } else {
    $hourlocation = "AND hour_locations.login = $nameID";
  }//closes if-else
  
  $sql = "SELECT hour_locations.id, hour_locations.name, hour_locations.description, hour_locations.address, hour_locations.phone, hour_locations.url, hour_locations.hours_notes, hour_locations.map_code, hour_locations.login,
                 locations.location_name AS building
				  FROM hour_locations 
				  JOIN locations
				  ON hour_locations.location_id = locations.id
				  WHERE hour_locations.display = 1
          $hourlocation
				  ORDER BY hour_locations.display_position";
          
  $sth = $dbh->prepare($sql);
  $status = $sth->execute();
  $results = $sth->fetchAll();
  
  return $results;
  
}//closes function


// get name ID or name IDs array based on (optional) branch id
function getNameIDs($branchID = null) {
	
  global $dbh;
  
  if ($branchID == null) {
    $hourlocation = "";
  } else {
    $hourlocation = "AND id = $branchID";
  }//closes if-else

	$sql = "SELECT login
				  FROM hour_locations
				  WHERE display = 1
          $hourlocation
          ORDER BY display_position";
 
  $stmt=$dbh->prepare($sql);
  $stmt->execute();
 
  if ($branchID == null) {
    
    $nameids = array();
    
    while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
      array_push($nameids, $row['login']);
    }//closes while
    
	  return $nameids;
    
  } else {
    
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $nameid=$row['login'];
    
    return $nameid;
    
  }//closes if-else

}//closes function


// get the location id based on name ID
function getID($name_id) {
	
  global $dbh;

	$sql = "SELECT id
				  FROM hour_locations
				  WHERE login='$name_id'";
 
  $stmt=$dbh->prepare($sql);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  $id=$row['id'];

	return $id;

}//closes function


// get the saved lat long for Google maps based on name ID
function getMapCode($name_id) {
	
  global $dbh;

	$sql = "SELECT map_code
				  FROM hour_locations
				  WHERE login='$name_id'";
 
  $stmt=$dbh->prepare($sql);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  $map=$row['map_code'];

	return $map;

}//closes function


// get the location name based on name ID
function getName($name_id){
  
	global $dbh;

	$sql = "SELECT name
				  FROM hour_locations
				  WHERE login='$name_id'";
         
  $stmt=$dbh->prepare($sql);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  $name=$row['name'];
				
	// Xwi7xwa needs special characters that are not part of the Georgia set and need to be faked with <u> tags
	return ($name == "X̱wi7x̱wa Library") ? "<u>X</u>wi7<u>x</u>wa Library" : $name;

}//closes function


//get the building name based on name ID and (optional) $prepend for adding text before the function (e.g. "at ")
function getBuilding($name_id, $prepend = '') {
  
	global $dbh;
	
	$sql = "SELECT hour_locations.name,
				         locations.location_name
				  FROM hour_locations 
				  JOIN locations
				  ON hour_locations.location_id=locations.id
				  WHERE display=1 
				  AND hour_locations.login='$name_id'";
         
  $stmt=$dbh->prepare($sql);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  $locationName = $row['name'];
  $buildingName = $row['location_name'];
	
	// check to see if the location is the building or not - if it is, nothing happens
	if ($locationName !== $buildingName) {
		return $prepend . $buildingName;
	}//closes if

}//closes function


// get the location description based on name ID
function getDescription($name_id) {
	
  global $dbh;
	
	$sql = "SELECT description
				  FROM hour_locations
				  WHERE login='$name_id'";

  $stmt=$dbh->prepare($sql);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  $description=$row['description'];

	return $description;

}//closes function


// get the location address based on name ID
function getAddress($name_id) {
	
  global $dbh;

	$sql = "SELECT address
				  FROM hour_locations
          WHERE login='$name_id'";

  $stmt=$dbh->prepare($sql);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  $address=$row['address'];

	return $address;

}//closes function


// get the phone number based on name ID
function getPhone($name_id) {
	
  global $dbh;
	
	$sql = "SELECT phone
				  FROM hour_locations
				  WHERE login='$name_id'";

  $stmt=$dbh->prepare($sql);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  $phone=$row['phone'];

	return $phone;

}//closes function


// get the location url based on name ID
function getURL($name_id) {
  
	global $dbh;

	$sql = "SELECT url
				  FROM hour_locations
				  WHERE login='$name_id'";

  $stmt=$dbh->prepare($sql);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  $url=$row['url'];

	return $url;

}//closes function


// get the accessibility url based on name ID
function getAccessURL($name_id) {
  
	global $dbh;

	$sql = "SELECT accessibility_url
				  FROM hour_locations
				  WHERE login='$name_id'";

  $stmt=$dbh->prepare($sql);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  $url=$row['accessibility_url'];

	return $url;

}//closes function


// get the location hours note, if available, based on name ID
function getHoursNotes($name_id) {
  
	global $dbh;
	
	$sql = "SELECT hours_notes
				  FROM hour_locations
				  WHERE login='$name_id'";

  $stmt=$dbh->prepare($sql);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  
  // replace database line breaks with breaks
  $hours_notes=preg_replace('/\s\s+/', '<br /><br />', $row['hours_notes']);

	if ($hours_notes != '') {
		return '<p class="clear warning message hours-note">'.$hours_notes.'</p>';
	}//closes if

}//closes function


// get the location widget note based on branch ID
function getWidgetNote($branchID){
  
	global $dbh;
	
	$sql = "SELECT widget_note
				  FROM hour_locations
				  WHERE id='$branchID'";

  $stmt=$dbh->prepare($sql);
  $stmt->execute();
  $row=$stmt->fetch(PDO::FETCH_ASSOC);
  
  // replace database line breaks with breaks
  $widget_note=preg_replace('/\s\s+/', '<br /><br />', ($row['widget_note']));

	return $widget_note;

}//closes function


//**** Calendar ****//


// get array of relevant date ranges based on a month, year and (optional) branch id
// returns: BEGIN_DATE, END_DATE, CATEGORY_ID 
function getRangesByMonth($month, $year, $branchID = null) {
  
  global $dbh;
  
  if ($branchID == null) {
    $hourlocation = "";
  } else {
    $hourlocation = "AND hour_groupings.hour_location_id = $branchID";
  }//closes if-else
  
  // set first day of the month for checking longer (i.e. spanning multiple months) ranges
  $ymd = $year.'-'.$month.'-01';
  
  $sql = "SELECT hour_date_ranges.begin_date, hour_date_ranges.end_date,
                 hour_groupings.hour_category_id AS category_id
          FROM hour_date_ranges
          JOIN hour_groupings
          ON hour_groupings.hour_date_range_id = hour_date_ranges.id
          WHERE (  (EXTRACT(YEAR FROM hour_date_ranges.begin_date) = $year AND EXTRACT(MONTH FROM hour_date_ranges.begin_date) = $month)
                OR (EXTRACT(YEAR FROM hour_date_ranges.end_date) = $year AND EXTRACT(MONTH FROM hour_date_ranges.end_date) = $month)
                OR (hour_date_ranges.begin_date <= '$ymd' AND '$ymd' <= hour_date_ranges.end_date)  )
          AND hour_groupings.hour_type_id = 2
          $hourlocation
          ORDER BY hour_groupings.hour_category_id ASC";

  $sth = $dbh->prepare($sql);
  $status = $sth->execute();
  $results = $sth->fetchAll();
  
  return $results;
  
}//closes function


// retrieve and display the hours in category-based dls based on a month, year and (optional) branch id
function displayHoursByMonth($month, $year, $branchID = null) {
  
  global $dbh;
  
  if ($branchID == null) {
    $hourlocation = "";
  } else {
    $hourlocation = "AND hour_groupings.hour_location_id = $branchID";
  }//closes if-else
  
  // set first day of the month for longer (i.e. spanning multiple months) range checking
  $ymd = $year.'-'.$month.'-01';
  
  $sql = "SELECT hour_days.day_of_week, hour_days.open_time, hour_days.close_time, hour_days.is_closed, hour_days.is_tbd,
                 hour_groupings.hour_category_id,
                 hour_categories.category,
                 hour_date_ranges.begin_date, hour_date_ranges.end_date
          FROM hour_days
          JOIN hour_groupings
          ON hour_days.hour_grouping_id = hour_groupings.id
          JOIN hour_date_ranges
          ON hour_groupings.hour_date_range_id = hour_date_ranges.id
          JOIN hour_categories
          ON hour_groupings.hour_category_id = hour_categories.id
          WHERE hour_days.hour_grouping_id IN (
                SELECT hour_groupings.id
                FROM hour_date_ranges
                JOIN hour_groupings
                ON hour_groupings.hour_date_range_id = hour_date_ranges.id
                WHERE ( (EXTRACT(YEAR FROM hour_date_ranges.begin_date) = $year 
                        AND EXTRACT(MONTH FROM hour_date_ranges.begin_date) = $month)
                        OR  
                        (EXTRACT(YEAR FROM hour_date_ranges.end_date) = $year 
                        AND EXTRACT(MONTH FROM hour_date_ranges.end_date) = $month)
                        OR
                        (hour_date_ranges.begin_date <= '$ymd' AND '$ymd' <= hour_date_ranges.end_date)  )
                AND hour_groupings.hour_type_id = 2
                $hourlocation )
          AND ( EXTRACT(MONTH FROM hour_days.day_of_week) = $month
                OR hour_days.day_of_week = 'Monday'
                OR hour_days.day_of_week = 'Tuesday'
                OR hour_days.day_of_week = 'Wednesday'
                OR hour_days.day_of_week = 'Thursday'
                OR hour_days.day_of_week = 'Friday'
                OR hour_days.day_of_week = 'Saturday'
                OR hour_days.day_of_week = 'Sunday' )
          ORDER BY hour_date_ranges.begin_date, hour_groupings.id,
                   hour_days.day_of_week = 'Monday' DESC,
                   hour_days.day_of_week = 'Tuesday' DESC,
                   hour_days.day_of_week = 'Wednesday' DESC,
                   hour_days.day_of_week = 'Thursday' DESC,
                   hour_days.day_of_week = 'Friday' DESC,
                   hour_days.day_of_week = 'Saturday' DESC,
                   hour_days.day_of_week = 'Sunday' DESC,
                   hour_days.day_of_week ASC";

  $sth = $dbh->prepare($sql);
  $status = $sth->execute();
  
  if($sth->errorCode() !== '00000'){
    var_export($sth->errorInfo());
  }//closes if
  
  $results = $sth->fetchAll();
  $count = count($results);
  
  // matching hours set to false
  $match = false;
  
  // variable to store code string
  $hours = '';
  
  // if no hours were found
  if (!$results) {
    
    $hours .= '
              <h6>Hours Unavailable</h6>
              <p>No hours currently listed for this month.</p>';
  
  } else {
    
    // display hours by category, collapsing where appropriate
    for ($i = 0; $i < $count; $i++) {
      
      // variables to compare the next/prev values
      $next_open = isset($results[$i+1]['open_time']) ? $results[$i+1]['open_time'] : '0';
      $next_close = isset($results[$i+1]['close_time']) ? $results[$i+1]['close_time'] : '0';
      $next_closed = isset($results[$i+1]['is_closed']) ? $results[$i+1]['is_closed'] : '';
      $prev_category = isset($results[$i-1]['hour_category_id']) ? $results[$i-1]['hour_category_id'] : '0';
      $next_category = isset($results[$i+1]['hour_category_id']) ? $results[$i+1]['hour_category_id'] : '0';
      
      // show color block and heading when category is new
      if ($results[$i]['hour_category_id'] != $prev_category) {   
        $hours .= '
              <h6><span class="hours-category '; if ($results[$i]['hour_category_id'] == 4) { $hours .= 'summer-alternate'; } else { $hours .= strtolower($results[$i]['category']); } $hours .= '"></span>';
        
        if ($results[$i]['hour_category_id'] == 4 || $results[$i]['hour_category_id'] == 3) { $hours .= 'Summer Hours'; } else { $hours .= $results[$i]['category'].' Hours'; }
        
        if ($results[$i]['hour_category_id'] != 5 && $results[$i]['hour_category_id'] != 7) { $hours .= ' ('.date('M j', strtotime($results[$i]['begin_date'])).'-'.date('M j', strtotime($results[$i]['end_date'])).')'; } $hours .= '</h6>
              <dl class="'; if ($results[$i]['hour_category_id'] == 4) { $hours .= 'summer-alternate'; } else { $hours .= strtolower($results[$i]['category']); } $hours .= '">';
      }//closes if
      
      // if the next set of hours is the same (and the same category), set the range start date, change match to true, break the loop
      if ($results[$i]['open_time'] == $next_open && $results[$i]['close_time'] == $next_close && $results[$i]['is_closed'] == $next_closed && $match == false && $results[$i]['hour_category_id'] == $next_category) {
        
        // exception/holiday hours display as dates, regular hours display as days
        if ($results[$i]['hour_category_id'] == 5 || $results[$i]['hour_category_id'] == 7) {
          $start_range = date('M j', strtotime($results[$i]['day_of_week']));
        } else {
          $start_range = date('D', strtotime($results[$i]['day_of_week']));
        }//closes if-else
        
        $match = true;
        continue;
      
      // if the next set of hours is the same (and the same category) AGAIN, just skip to the next one
      } else if ($results[$i]['open_time'] == $next_open && $results[$i]['close_time'] == $next_close && $results[$i]['is_closed'] == $next_closed && $match == true && $results[$i]['hour_category_id'] == $next_category) {
      
        continue;
      
      // otherwise, display the hours  
      } else {
        
        $hours .= '
                <dt>';
        
        // when a range has been set, display it
        if ($match == true) {
        
          $hours .= $start_range;
          
          // exception/holiday hours display as dates, regular hours display as days
          if ($results[$i]['hour_category_id'] == 5 || $results[$i]['hour_category_id'] == 7) {
            if ($results[$i]['begin_date'] == $results[$i]['end_date']) {
              $hours .= ' &amp '.date('j', strtotime($results[$i]['day_of_week']));
            } else {
              $hours .= '-'.date('j', strtotime($results[$i]['day_of_week']));
            }//closes if-else
          } else {
            $hours .= '-'.date('D', strtotime($results[$i]['day_of_week']));
          }//closes if-else
        
        // otherwise, just display the current date
        } else {
        
          // exception/holiday hours display as dates, regular hours display as days
          if ($results[$i]['hour_category_id'] == 5 || $results[$i]['hour_category_id'] == 7) {
            $hours .= date('M j', strtotime($results[$i]['day_of_week']));
          } else {
            $hours .= date('l', strtotime($results[$i]['day_of_week']));
          }//closes if-else
        
        }//closes if-else
        
        $hours .= '</dt><dd>';
        
        // now display the hours (uses displayTime function to trim zero minutes)
        if ($results[$i]['is_tbd'] == 1) {
          $hours .= 'TBD';
        } else if ($results[$i]['is_closed'] == 1) {
          $hours .= 'Closed';
        } else if ($results[$i]['is_closed'] == 0 && $results[$i]['open_time'] == $results[$i]['close_time']) {
          $hours .= 'Open 24 Hours';
        } else {
          $hours .= displayTime($results[$i]['open_time']).' - '.displayTime($results[$i]['close_time']);
        }//closes if-else
        
        $hours .= '</dd>';
        
        // close dl, when next category is new
        if ($results[$i]['hour_category_id'] != $next_category) {
          $hours .= '
              </dl>
            ';
        }//closes if
        
        //reset match to false to start the loop over
        $match = false;
        
      }//closes if
      
    }//closes for
    
  }//closes if-else
  
  return $hours;
  
}//closes function


//**** Print ****//


// get array of date range information based on month(s) range starts in and category/categories 
// returns: ID, BEGIN, END
function getAllRanges($month, $category) {
  
  global $dbh;
  
  // determine range(s) to work with based on passed parameters
 $sql = "SELECT DISTINCT hour_date_ranges.id, hour_date_ranges.begin_date, hour_date_ranges.end_date, hour_date_ranges.print_note
         FROM hour_date_ranges
         JOIN hour_groupings
         ON hour_groupings.hour_date_range_id = hour_date_ranges.id
         WHERE EXTRACT(MONTH FROM hour_date_ranges.begin_date) IN ($month)
         AND hour_groupings.hour_category_id IN ($category)
         ORDER BY hour_date_ranges.begin_date ASC";
 
 $sth = $dbh->prepare($sql);
 $status = $sth->execute();
 $range = $sth->fetchAll();
    
  // store range ids in an array
  $id = array();

  $last = count($range)-1;
  $message = '';

  for ($i = 0; $i < count($range); $i++) {
    array_push($id, $range[$i]['id']);
    if(!empty($range[$i]['print_note'])) {
    	$message .= $range[$i]['print_note'];
    	if($i < $last && !empty($range[$i+1]['print_note'])) {
	  		$message .= " ";
	  	}
  	}
  }//closes for

  // ids return in SQL-friendly list, begin pulls in first date, end pulls in last date
  $last = count($range)-1;
  $id = implode("', '", array_unique($id));
  $begin = isset($range[0]['begin_date']) ? $range[0]['begin_date'] : '';
  $end = isset($range[$last]['end_date']) ? $range[$last]['end_date'] : '';
 
  $return = array('id' => $id, 'begin' => $begin, 'end' => $end, 'message' => $message);
 
  return $return;
 
}//closes function


// get all matching hours based on category, range id(s), range begin date and range end date, limiting to specific locations when indicated
// returns: ID, NAME, PARENT, HOUR_CATEGORY_ID, DAY_OF_WEEK, OPEN_TIME, CLOSE_TIME, IS_CLOSED, IS_TBD, BEGIN_DATE, END_DATE
function getAllHours($category, $limit, $id, $begin, $end) {
  
  global $dbh;
  
  $limit = "AND hour_locations.id NOT IN ($limit)";
  $today = date('Y-m-d');
  
  // for all non-holiday hours
  if ($category == 1 || $category == 2 || $category == 3 || $category == 4) {
  
    // grab the hours based on range(s), including unusual holiday hours or exceptions
    $sql = "SELECT hour_locations.id, hour_locations.name, hour_locations.parent_hour_location_id AS parent,
                   hour_groupings.hour_category_id,
                   hour_days.day_of_week, hour_days.open_time, hour_days.close_time, hour_days.is_closed, hour_days.is_tbd,
                   hour_date_ranges.begin_date, hour_date_ranges.end_date
           FROM hour_locations
           JOIN hour_groupings
           ON hour_locations.id=hour_groupings.hour_location_id
           JOIN hour_days
           ON hour_groupings.id=hour_days.hour_grouping_id
           JOIN hour_date_ranges
           ON hour_groupings.hour_date_range_id=hour_date_ranges.id
           WHERE hour_locations.display = 1
           AND hour_groupings.hour_type_id = 2
           $limit
           AND ( ( hour_groupings.hour_date_range_id IN ('$id') )
                 OR ( hour_groupings.hour_category_id = 5
                      AND hour_days.is_closed = 0
                      AND EXTRACT(MONTH FROM hour_date_ranges.begin_date) NOT IN (1, 3, 4, 12)
                      AND '$begin' <= hour_date_ranges.begin_date
                      AND '$end' >= hour_date_ranges.end_date
                      AND '$today' <= hour_date_ranges.end_date )
                 OR ( hour_groupings.hour_category_id IN (6, 7)
                      AND '$begin' <= hour_date_ranges.begin_date
                      AND '$end' >= hour_date_ranges.end_date
                      AND '$today' <= hour_date_ranges.end_date ) )
           ORDER BY hour_locations.display_position,
                    hour_date_ranges.begin_date,
                    hour_days.day_of_week = 'Monday' DESC,
                    hour_days.day_of_week = 'Tuesday' DESC,
                    hour_days.day_of_week = 'Wednesday' DESC,
                    hour_days.day_of_week = 'Thursday' DESC,
                    hour_days.day_of_week = 'Friday' DESC,
                    hour_days.day_of_week = 'Saturday' DESC,
                    hour_days.day_of_week = 'Sunday' DESC,
                    hour_days.day_of_week ASC";
    
    $sth = $dbh->prepare($sql);
    $status = $sth->execute();
    $results = $sth->fetchAll();
  
  // for holiday hours
  } else if ($category == 5) {
  
    // grab the hours based on range(s) only, no exceptions
    $sql = "SELECT hour_locations.id, hour_locations.name, hour_locations.parent_hour_location_id AS parent,
                   locations.location_name,
                   hour_groupings.hour_category_id,
                   hour_days.day_of_week, hour_days.open_time, hour_days.close_time, hour_days.is_closed, hour_days.is_tbd,
                   hour_date_ranges.begin_date, hour_date_ranges.end_date
           FROM hour_locations
           JOIN locations
           ON hour_locations.location_id=locations.id
           JOIN hour_groupings
           ON hour_locations.id=hour_groupings.hour_location_id
           JOIN hour_days
           ON hour_groupings.id=hour_days.hour_grouping_id
           JOIN hour_date_ranges
           ON hour_groupings.hour_date_range_id=hour_date_ranges.id
           WHERE hour_locations.display = 1
           AND hour_groupings.hour_type_id = 2
           $limit
           AND hour_groupings.hour_date_range_id IN ('$id')
           ORDER BY hour_locations.display_position,
                    hour_days.day_of_week ASC";
    
    $sth = $dbh->prepare($sql);
    $status = $sth->execute();
    $results = $sth->fetchAll();
    
  }//closes if-elseif
  
  return $results;
  
}//closes function


//**** WIDGETS ****//


// get a day's hours based on date, branchID and library/reference hours type
// returns: OPEN_TIME, CLOSE_TIME, IS_CLOSED, TYPE, CATEGORY
function getHoursByDate($ymd, $branchID, $type) {
  
  global $dbh;
  
  // only two types accepted
  if ($type != '2' && $type != '3') {
    $type = 2;
  }//closes if

  $subquery = "";
  
  // only library hours need date range constraint (reference hours only have one range)
  if ($type == 2) {
    $subquery = "AND (hour_date_ranges.begin_date <= '$ymd' AND '$ymd' <= hour_date_ranges.end_date)";
  }//closes if
 
  $dayofweek = date('l', strtotime($ymd));
  
  $sql = "SELECT hour_days.open_time, hour_days.close_time, hour_days.is_closed,
                 hour_groupings.hour_type_id AS type, hour_groupings.hour_category_id AS category
          FROM hour_days
          JOIN hour_groupings
          ON hour_days.hour_grouping_id = hour_groupings.id
          JOIN hour_date_ranges
          ON hour_groupings.hour_date_range_id = hour_date_ranges.id
          WHERE hour_days.hour_grouping_id IN (
                SELECT hour_groupings.id
                FROM hour_groupings
                JOIN hour_date_ranges
                ON hour_groupings.hour_date_range_id = hour_date_ranges.id
                JOIN hour_days
                ON hour_groupings.id = hour_days.hour_grouping_id
                WHERE hour_groupings.hour_type_id = '$type'
                AND hour_groupings.hour_location_id = '$branchID'
                $subquery )
          AND ( hour_days.day_of_week = '$ymd' OR hour_days.day_of_week = '$dayofweek' )
          ORDER BY hour_groupings.hour_category_id DESC
          LIMIT 1";
 
  $sth = $dbh->prepare($sql);
  $status = $sth->execute();
  $results = $sth->fetchAll();
  
  return $results;
 
}//closes function
?>
