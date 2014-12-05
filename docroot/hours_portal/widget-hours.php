<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Branch Hours</title>

<style type="text/css">
.hours {
  background-color: #fff;
  border: 1px solid #ddd;
  border-spacing: 1px;
  margin-bottom: 0;
  max-width: 98%;
}
.hours caption {
  font-size: 125%;
  line-height: 100%;
  margin-bottom: 8px;
  margin-top: 0;
  font-weight: bold;
  text-align: center;
  background-color: #fff;
}
.hours th {
  font-size: 100%;
  background: #eee;
  text-align: left;
  border: 1px solid #ddd;
  text-transform: capitalize;
}
.hours th .nowrap {
  white-space: nowrap;
}
.hours td, .hours th {
  border-bottom: 1px solid #ddd;
  letter-spacing: 0;
  padding: 3px 5px;
}
.hours tr.even td {
  background: none;
}
.hours tr.odd {
  background-color: #EFEFEF;
}
.today {
  background-color: #FFFFBB !important;
}
.widget-note {
  font-size: 85%;
  background-color: #efefef;
}
.hours + p {
  margin-top: 8px;
}
.open, .closed {
  font-variant: small-caps;
  font-size : 120%;
}
.hours-widget {
  margin: 10px 0;
}
.hours-widget h2 {
  font-size: 120%;
  line-height: 110%;
  margin: 15px;
}
.hours-widget p {
  margin: 4px 0;
}
.hours-widget p .day {
  display: inline-block;
  width: 90px;
}
</style>

</head>

<body>

<script type="text/javascript" src="http://kemano.library.ubc.ca/~letitia/hours/widget-hours-js.php?location1=2&location2=2&shorttable=yes"></script>

<?php 
require_once('functions.php');


// NOTE: this widget can accept up to 3 locations/columns for a table view, but only 1 location for text/today/status views
// in addition, the status view will only display library (not reference) hours because of the way that function currently works


// grab submitted values (which locations/hours, what display)
$location1 = isset($_GET['location1']) ? sanitize($_GET['location1']) : 2;
$type1 = isset($_GET['type1']) ? sanitize($_GET['type1']) : 2;
$location2 = isset($_GET['location2']) ? sanitize($_GET['location2']) : 2;
$type2 = isset($_GET['type2']) ? sanitize($_GET['type2']) : 3;
$location3 = isset($_GET['location3']) ? sanitize($_GET['location3']) : '';
$type3 = isset($_GET['type3']) ? sanitize($_GET['type3']) : 2;
$display = isset($_GET['display']) ? sanitize($_GET['display']) : 'table';
$shorttable = isset($_GET['shorttable']) ? sanitize($_GET['shorttable']) : '';


// set current year, week, day, date, time
$currentyear = date('o');
$currentweek = date('W');
$currentdate = date('Y-m-d');
$currentday = date('l', strtotime($currentdate));
$currenttime = date('H:i:s');

// for testing different dates, times
//$currentdate = '2013-01-01';
//$currentyear = date('o', strtotime($currentdate)); // a "rounded" year that follows the 52 week count
//$currentweek = date('W', strtotime($currentdate));
//$currentday = date('l', strtotime($currentdate));
//$currenttime = '01:00:00';


// find first monday of the year and its week number
$firstmon = strtotime("mon jan {$currentyear}");
$firstmonweek = date('W', $firstmon);

// if the "rounded" year is in fact a year ahead of the current date (i.e. monday is previous year)
if (date('Y', strtotime($currentdate)) < $currentyear) {

  // go back to the previous year and grab that monday
  $lastyear = ($currentyear - 1);
  $lastyrfirstmon = strtotime("mon jan {$lastyear}");
  $thismon = date( 'Y-m-d', strtotime("+52 week " . date('Y-m-d', $lastyrfirstmon)) );

} else {

  // number of weeks to add depends on when the first monday occurs (first week or second week of the year)
  if ($firstmonweek == 01) {
    $weeksoffset = $currentweek - 1;
  } else {
    $weeksoffset = $currentweek - 2;
  }//closes if-else
  
  // calculate this week's monday using the above offset
	$thismon = date( 'Y-m-d', strtotime("+{$weeksoffset} week " . date('Y-m-d', $firstmon)) );

}//closes if-else

//echo 'Today: '.$currentyear.'<br />';
//echo 'This week: '.$currentweek.' <br />This Mon: '.$thismon.' <br/>First Mon: '.date('Y-m-d', $firstmon).' <br />First Mon Week: '.$firstmonweek;


// begin widget variable string depending on display
if ($display == 'table') {
  
  // account for single day table display
  if ($shorttable == "yes") {
   
    // for single day table
    $caption = '<caption>Today\'s Hours</caption>';
    
  } else {
    
    $caption = '<caption>This Week ';
    
    if (!$location2) {
      $caption .= '<br />';
    }//closes if
    
    $caption .= '('.date('F j, Y', strtotime($thismon)).')</caption>';
    
  }//closes if-else
  
  $widget = '
<table class="hours">
  '.$caption.'
  <tbody>
    <tr>';
  
  // for weekly table
  if ($shorttable != "yes") { $widget .= '
      <th>Hours</th>';
  }//closes if
  
  $widget .= '
      <th>';

  // for library hours in column 1
  if ($type1 == 2) {
    
    // exceptions for IKBLC widgets
    switch ($location1) {
    
      case 6:
      $widget .= 'IKBLC Building';
      break;
      
      case 7:
      $widget .= 'Music, Art &amp;<br /> Architecture Library';
      break;
      
      case 11:
      $widget .= 'Chapman<br />Learning Commons';
      break;
      
      default:
      $widget .= '<span class="nowrap">Open Hours</span>';
      break;
    
    }//closes switch
    
  // for reference hours in column 1
  } else if ($type1 == 3) {
    
    // exception for Koerner
    switch ($location1) {
    
      case 2:
      $widget .= 'Research Commons<br />&amp; Reference';
      break;
      
      default:
      $widget .= 'Reference Hours';
      break;
      
    }//closes switch
    
  }//closes if-elseif  
  
  $widget .= '</th>'; 
 
  // when a second column has been indicated
  if ($location2) {
    
    $widget .= '
        <th>';
   
    // for library hours in column 2
    if ($type2 == 2) {
    
      // exceptions for IKBLC widgets
      switch ($location2) {
      
        case 6:
        $widget .= 'IKBLC Building';
        break;
        
        case 7:
        $widget .= 'Music, Art &amp;<br /> Architecture Library';
        break;
        
        case 11:
        $widget .= 'Chapman<br />Learning Commons';
        break;
        
        default:
        $widget .= 'Open Hours';
        break;
      
      }//closes switch
      
    // for reference hours in column 2
    } else if ($type2 == 3) {
      
      // exception for Koerner
      switch ($location2) {
      
        case 2:
        $widget .= 'Research Commons<br />&amp; Reference';
        break;
        
        default:
        $widget .= 'Reference Hours';
        break;
      
      }//closes switch
      
    }//closes if-elseif
    
    $widget .= '</th>';
    
  }//closes if
 
  // when a third column has been indicated
  if ($location3) {
    
    $widget .= '
        <th>';
    
    // for library hours in column 3
    if ($type3 == 2) {
    
      // exceptions for IKBLC widgets
      switch ($location3) {
      
        case 6:
        $widget .= 'IKBLC Building';
        break;
        
        case 7:
        $widget .= 'Music, Art &amp;<br /> Architecture Library';
        break;
        
        case 11:
        $widget .= 'Chapman<br />Learning Commons';
        break;
        
        default:
        $widget .= 'Open Hours';
        break;
      
      }//closes switch
    
    // for reference hours in column 3
    } else if ($type3 == 3) {
      
      // exception for Koerner
      switch ($location3) {
      
        case 2:
        $widget .= 'Research Commons<br />&amp; Reference';
        break;
        
        default:
        $widget .= 'Reference Hours';
        break;
      
      }//closes switch
      
    }//closes if-elseif
    
    $widget .= '</th>';
    
  }//closes if

  $widget .= '
    </tr>';
  
  // begin table rows with gray background (= false)
  $alt = false;
  
  // limit loop for single day table
  if ($shorttable == "yes") {
    
    switch($currentday) {
    
      case "Monday":
      $start = 0;
      break;
      
      case "Tuesday":
      $start = 1;
      break;
      
      case "Wednesday":
      $start = 2;
      break;
      
      case "Thursday":
      $start = 3;
      break;
      
      case "Friday":
      $start = 4;
      break;
      
      case "Saturday":
      $start = 5;
      break;
      
      case "Sunday":
      $start = 6;
      break;
    }//closes switch;
    
    $days = 1 + $start;
  
  } else {
  
    $start = 0;
    $days = 7;
  
  }//closes if-else
  
  // for loop to display day rows
  for ($i = $start; $i < $days; $i++) {
   
    // change day and date with each iteration
    switch ($i) {
      
      case 0:
      $day = 'Monday';
      $ymd = $thismon;
      break;
      
      case 1:
      $day = 'Tuesday';
      $ymd = date('Y-m-d', (strtotime($thismon) + (1*86400)));
      break;
      
      case 2:
      $day = 'Wednesday';
      $ymd = date('Y-m-d', (strtotime($thismon) + (2*86400)));
      break;
      
      case 3:
      $day = 'Thursday';
      $ymd = date('Y-m-d', (strtotime($thismon) + (3*86400)));
      break;
      
      case 4:
      $day = 'Friday';
      $ymd = date('Y-m-d', (strtotime($thismon) + (4*86400)));
      break;
      
      case 5:
      $day = 'Saturday';
      $ymd = date('Y-m-d', (strtotime($thismon) + (5*86400)));
      break;  
      
      case 6:
      $day = 'Sunday';
      $ymd = date('Y-m-d', (strtotime($thismon) + (6*86400)));
      break;
      
    }//closes switch
    
    // grab hours based on date, location and library/reference type
    // returns: OPEN_TIME, CLOSE_TIME, IS_CLOSED, TYPE, CATEGORY
    $column1 = getHoursByDate($ymd, $location1, $type1);
    $column2 = getHoursByDate($ymd, $location2, $type2);
    $column3 = getHoursByDate($ymd, $location3, $type3);
   
    // start row display with appropriate tr tag (sets up alternating rows)
    if ($alt == false) {
      
      $row = '
    <tr class="even'; if ($currentday == $day) { $row .= ' today'; } $row .= '">';
      $alt = true;
      
    } else {
      
      $row = '
    <tr class="odd '; if ($currentday == $day) { $row .= ' today'; } $row .= '">';
      $alt = false;
      
    }//closes if-else
    
    // no day column for single day table
    if ($shorttable != "yes") {
      
      // add in day of week
      $row .= '<td>'.$day.'</td>';
      
    }//closes if
    
    // for first column returns
    if ($column1) {
      
      $row .= '<td>';
      
      // display library hours as closed, 24 hrs or a range
      if ($column1[0]['is_closed'] == 1) {
        $row .= 'Closed</td>';
      } else if ($column1[0]['open_time'] == $column1[0]['close_time'] && $column1[0]['is_closed'] == 0) {
        $row .= 'Open 24 Hrs</td>';
      } else {
        $row .= displayTime($column1[0]['open_time']).' - '.displayTime($column1[0]['close_time']).'</td>';
      }//closes if-elseif-else
      
    // for no return
    } else {
      
      $row .= 'N/A</td>';
      
    }//closes if-else
    
    // for indicated second location
    if ($location2) {
    
      $row .= '<td>';
      
      // for second column returns
      if ($column1 && $column2) {
          
        // display library hours as closed, 24 hrs or a range (and reference desk displays as closed if library is closed)
        if ($column2[0]['is_closed'] == 1 || ($location1 == $location2 && $column1[0]['type'] == 2 && $column2[0]['type'] == 3 && $column1[0]['is_closed'] == 1) ) {
          $row .= 'Closed</td>';
        } else if ($column2[0]['open_time'] == $column2[0]['close_time'] && $column2[0]['is_closed'] == 0) {
          $row .= 'Open 24 Hrs</td>';
        } else {
          $row .= displayTime($column2[0]['open_time']).' - '.displayTime($column2[0]['close_time']).'</td>';
        }//closes if-elseif-else
      
      // for no return
      } else {
      
        $row .= 'N/A</td>';
      
      }//closes if-else
    
    }//closes if
    
    // for indicated third location
    if ($location3) {
      
      $row .= '<td>';
      
      // for third column returns
      if ($column3) {
        
        // display library hours as closed, 24 hrs or a range
        if ($column3[0]['is_closed'] == 1) {
          $row .= 'Closed</td>';
        } else if ($column3[0]['open_time'] == $column3[0]['close_time'] && $column3[0]['is_closed'] == 0) {
          $row .= 'Open 24 Hrs</td>';
        } else {
          $row .= displayTime($column3[0]['open_time']).' - '.displayTime($column3[0]['close_time']).'</td>';
        }//closes if-elseif-else
        
      // for no return 
      } else {
      
        $row .= 'N/A</td>';
      
      }//closes if-else
      
    }//closes if
    
    // close the row
    $row .= '</tr>';
    
    // add the row to the widget
    $widget .= $row;
    
  }//closes for
  
  // grab name for monthly hours link
  $URLname = getNameIDs($location1);
 
  // grab available widget or emergency closure notes for location 1
  $note1 = getWidgetNote($location1);
  $emergencynote1 = getHoursNotes($URLname);

  // when a note is included
  if ($note1 || $emergencynote1) {
   
    // add a row for notes 
    $widget .= '
    <tr class="widget-note"><td colspan="4"><em>Note:</em> &nbsp;';
    
    if ($note1) {
      $widget .= $note1;
    }//closes if
    
    if ($note1 && $emergencynote1) {
      $widget .= '<br /><br />';
    }//closes if
    
    if ($emergencynote1) {
      $widget .= $emergencynote1;
    }//closes if
    
    $widget .= '</tr>';
    
  }//closes if

  // end the widget table, display the link
  $widget .= '
  </tbody>
</table>

<p><strong>See Also:</strong> <a href="http://hours.library.ualberta.ca/#view-'.$URLname.'">Hours Monthly View</a></p>';

} else if ($display == 'text') {
  
  // put the week's dates in an array
  $ymd = array($thismon, date('Y-m-d', (strtotime($thismon) + (1*86400))), date('Y-m-d', (strtotime($thismon) + (2*86400))), date('Y-m-d', (strtotime($thismon) + (3*86400))), date('Y-m-d', (strtotime($thismon) + (4*86400))), date('Y-m-d', (strtotime($thismon) + (5*86400))), date('Y-m-d', (strtotime($thismon) + (6*86400))) );

  $weeklyhours = array();
  
  // pull in each days hours and add it to a weekly array  
  for ($i = 0; $i < 7; $i++) {
    
    // grab hours based on date and location (library hours)
    // returns: OPEN_TIME, CLOSE_TIME, IS_CLOSED, TYPE, CATEGORY
    $dailyhours = getHoursByDate($ymd[$i], $location1, $type1);
    array_push($weeklyhours, $dailyhours);
    
  }//closes for

  $widget = '
<div class="hours-widget">
  <h2>Hours This Week</h2>';

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
    
    // otherwise, display the hours  
    } else {
    
      $widget .= '
  <p><span class="day">';
    
      // when a range has been set, display it
      if ($match == true) {
        $widget .= $start_range.'-'.date('D', strtotime($ymd[$i]));
      } else {
        $widget .= date('l', strtotime($ymd[$i]));
      }//closes if-else
      
      $widget .= '</span> ';
      
      // now display the hours
      if ($weeklyhours[$i][0]['is_closed'] == 1) {
        $widget .= 'Closed';
      } else if ($weeklyhours[$i][0]['is_closed'] == 0 && $weeklyhours[$i][0]['open_time'] == $weeklyhours[$i][0]['close_time']) {
        $widget .= 'Open 24 Hours';
      } else {
        $widget .= displayTime($weeklyhours[$i][0]['open_time']).'-'.displayTime($weeklyhours[$i][0]['close_time']);
      }//closes if-elseif-else
      
      $widget .= '</p>';
      
    }//closes if-elseif-else
    
    //reset match to false to start the loop over
    $match = false;
    
  }//closes for

  $widget .= '
</div>';

} else if ($display == 'today') {
  
  // grab hours based on date and location
  // returns: OPEN_TIME, CLOSE_TIME, IS_CLOSED, TYPE, CATEGORY
  $today = getHoursByDate($currentdate, $location1, $type1);
  
  $widget = '
<div class="hours-widget">
  <p><strong>Today\'s Hours:</strong> &nbsp;';
  
  // if hours returned
  if ($today) {
    
    // display library hours as closed, 24 hrs or a range
    if ($today[0]['is_closed'] == 1) { 
      $widget .= 'Closed All Day</p>';
    } else if ($today[0]['open_time'] == $today[0]['close_time'] && $today[0]['is_closed'] == 0) {
      $widget .= 'Open 24 Hrs</p>';
    } else {
      $widget .= displayTime($today[0]['open_time']).' - '.displayTime($today[0]['close_time']).'</p>';
    }//closes if-elseif-else
    
  // if no return
  } else {
    
    $widget .= 'N/A';
    
  }//closes if-else

  $widget .= '
</div>';

} else if ($display == 'status') {
  
  // grab status based on date, time and location (library hours only)
  $status = displayCurrentStatus($currentdate, $currenttime, $location1);
  
  $widget = '
<div class="hours-widget">
  <p><strong>'.$status.'</strong></p>
</div>';

} else if ($display == 'homepage') {
  
  // grab full table display for the homepage
  $widget = displayLocationsStatusHomepage();

} else {
  
  $widget = '
<div class="hours-widget">
  <p>Sorry, no hours available for this view.</p>
</div>';

}//closes if-elseif-else


// now display final widget
echo $widget;
?>


</body>
</html>
