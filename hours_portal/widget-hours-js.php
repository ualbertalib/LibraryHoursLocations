<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once('functions.php');


// NOTE: this widget can accept up to 3 locations/columns for a table view, but only 1 location for text/today/status views
// in addition, the status view will only display library (not reference) hours because of the way that function currently works


// grab submitted values (which locations/hours, what display)
$location1 = isset($_GET['location1']) ? sanitize($_GET['location1']) : 2;
$type1 = isset($_GET['type1']) ? sanitize($_GET['type1']) : 1;

$display = isset($_GET['display']) ? sanitize($_GET['display']) : 'table';
$shorttable = isset($_GET['shorttable']) ? sanitize($_GET['shorttable']) : '';

// send widget as javascript
header('content-type:application/x-javascript');
?>

var hourswidget = "";

<?php
// set current year, week, day, date, time
$currentyear = date('o');
$currentweek = date('W');
$currentdate = date('Y-m-d');
$currentday = date('l', strtotime($currentdate));
$currenttime = date('H:i:s');

// for testing different dates, times
//$currentdate = '2013-02-11';
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


// begin widget variable string depending on display
if ($display == 'table') {
  
    // account for single day table display
  if ($shorttable == "yes") {
   
    // for single day table
    $caption = '<caption>Today&#39;s Hours</caption>';
    
  } else {
    
    $caption = '<caption>This Week ';
    
    
      $caption .= '<br />';
    
    
    $caption .= '('.date('F j, Y', strtotime($thismon)).')</caption>';
    
  }//closes if-else
?>

hourswidget += '<table class="hours"><?= $caption; ?><tbody><tr>';

<?php
// for weekly table
if ($shorttable != "yes") {
?>

hourswidget += '<th>HOURS</th>';

<?php } ?>

hourswidget += '<th>';


hourswidget += '</th>'; 



//hourswidget += '<th id="1">';


hourswidget += '</tr>';

<?php
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
   
   //echo "column1: {$ymd} . location1: $location1 . type1: $type1 \n";
   //print_r($column1);
   
   
  //  $column2 = getHoursByDate($ymd, $location2, $type2);
   //echo "column2: {$ymd} . location1: $location2 . type1: $type2 \n";
    //print_r($column2);
   
   // $column3 = getHoursByDate($ymd, $location3, $type3);
    //echo "column3: {$ymd} . location3: $location3 . type1: $type3 \n";
    //print_r($column3);
   
    // start row display with appropriate tr tag (sets up alternating rows)
    if ($alt == false) {
?>

var row = '<tr class="even';

<?php
      if ($currentday == $day) {
?>

row += ' today';

<?php
      }//closes if
?>

row += '">';

<?php
      $alt = true;
      
    } else {
?>

var row = '<tr class="odd';

<?php
      if ($currentday == $day) {
?>

row += ' today';

<?php
}
?>

row += '">';

<?php
      $alt = false;
      
    }//closes if-else
    
    // no day column for single day table
    if ($shorttable != "yes") {
?>

row += '<td><?= $day; ?></td>';

<?php
    }//closes if
    
    // for first column returns
    if ($column1) {
?>

row += '<td>';

<?php
      // display library hours as closed, 24 hrs or a range
      if ($column1[0]['is_closed'] == 1) {
?>

row += 'Closed</td>';

<?php
      } else if ($column1[0]['open_time'] == $column1[0]['close_time'] && $column1[0]['is_closed'] == 0) {
?>

row += 'Open 24 Hrs</td>';

<?php
      } else {
?>

row += '<?= displayTime($column1[0]['open_time']); ?> - <?= displayTime($column1[0]['close_time']); ?></td>';

<?php
      }//closes if-elseif-else
      
    // for no return
    } else {
?>
      
row += '<td>N/A</td>';

<?php      
    }//closes if-else
    

    
    // for indicated third location
   
?>

row += '</tr>';
    
hourswidget += row;

<?php
  }//closes for
  
  // grab name for monthly hours link
  $URLname = getNameIDs($location1);

  // grab available widget or emergency closure notes for location 1
  $note1 = getWidgetNote($location1);
  $emergencynote1 = getHoursNotes($URLname);
  
  // when a note is included
  if ($note1 || $emergencynote1) {
?>

hourswidget += '<tr class="widget-note"><td colspan="4"><em>Note:</em> &nbsp;';

<?php  
    if ($note1) {
?>

hourswidget += '<?= $note1; ?>';

<?php
    }//closes if
    
    if ($note1 && $emergencynote1) {
?>

hourswidget += '<br /><br />';

<?php
    }//closes if
    
    if ($emergencynote1) {
?>

hourswidget += '<?= $emergencynote1; ?>';

<?php
    }//closes if
?>

hourswidget += '</tr>';

<?php
  }//closes if
?>

hourswidget += '</tbody></table><p><strong>See Also:</strong> <a href="http://hours.library.ualberta.ca/#view-<?= $URLname; ?>">Hours Monthly View</a></p>';

<?php
} else if ($display == 'text') {
  
  // put the week's dates in an array
  $ymd = array($thismon, date('Y-m-d', (strtotime($thismon) + (1*86400))), date('Y-m-d', (strtotime($thismon) + (2*86400))), date('Y-m-d', (strtotime($thismon) + (3*86400))), date('Y-m-d', (strtotime($thismon) + (4*86400))), date('Y-m-d', (strtotime($thismon) + (5*86400))), date('Y-m-d', (strtotime($thismon) + (6*86400))) );

  $weeklyhours = array();
  
  // pull in each days hours and add it to a weekly array  
  for ($i = 0; $i < 7; $i++) {
    
    // grab hours based on date and location (library hours)
    // returns: OPEN_TIME, CLOSE_TIME, IS_CLOSED, TYPE, CATEGORY
    $dailyhours = getHoursByDate($ymd[$i], $location1, $type1);
    
    //print_r($dailyhours);
    
    array_push($weeklyhours, $dailyhours);
    
  }//closes for
?>

hourswidget = '<div class="hours-widget"><h2>Hours This Week</h2>';

<?php
  // set up match as false outside the loop
  $match = false;

  // display week's hours, collapsing where appropriate
  for ($i = 0; $i < 7; $i++) {
    
    //  echo 'test' . $weeklyhours[$i][0]['open_time'];
    // variables to compare the next values
 
    //  echo $weeklyhours[$i][0]['open_time'];
          $next_open = isset($weeklyhours[$i+1][0]['open_time']) ? $weeklyhours[$i+1][0]['open_time'] : '0';
          $next_close = isset($weeklyhours[$i+1][0]['close_time']) ? $weeklyhours[$i+1][0]['close_time'] : '0';
          $next_closed = isset($weeklyhours[$i+1][0]['is_closed']) ? $weeklyhours[$i+1][0]['is_closed'] : '0';
            if (! isset($weeklyhours[$i][0]['open_time'])) {
                $next_open="N/A";
                $current_open = "N/A";
            }
            if (! isset($weeklyhours[$i][0]['close_time'])){
                $next_close="N/A";
            }
             if (! isset($weeklyhours[$i][0]['is_closed'])){
                $next_closed="N/A";
            }
           
  
    
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
?>

hourswidget += '<p><span class="day">';

<?php
      // when a range has been set, display it
      if ($match == true) {
?>

hourswidget += '<?= $start_range; ?>-<?= date('D', strtotime($ymd[$i])); ?>';

<?php
      } else {
?>

hourswidget += '<?= date('l', strtotime($ymd[$i])); ?>';

<?php
      }//closes if-else
?>

hourswidget += '</span> ';

<?php
      // now display the hours
      if ($weeklyhours[$i][0]['is_closed'] == 1) {
?>

hourswidget += 'Closed';

<?php
  }else if ($current_open){
          //javascript
          echo "hourswidget += 'N/A';";
          
      } else if ($weeklyhours[$i][0]['is_closed'] == 0 && $weeklyhours[$i][0]['open_time'] == $weeklyhours[$i][0]['close_time']) {
?>

hourswidget += 'Open 24 Hours';

<?php
    
          
      } else {
?>

hourswidget += '<?= displayTime($weeklyhours[$i][0]['open_time']); ?>-<?= displayTime($weeklyhours[$i][0]['close_time']); ?>';

<?php
      }//closes if-elseif-else
?>

hourswidget += '</p>';

<?php
    }//closes if-elseif-else
   
    //reset match to false to start the loop over
    $match = false;
    
  }//closes for
?>

hourswidget += '</div>';

<?php
} else if ($display == 'today') {
  
  // grab hours based on date and location
  // returns: OPEN_TIME, CLOSE_TIME, IS_CLOSED, TYPE, CATEGORY
  $today = getHoursByDate($currentdate, $location1, $type1);
    
?>

hourswidget = '<div class="hours-widget"><p><strong>Today\'s Hours:</strong> &nbsp;';

<?php
  // if hours returned
  if ($today) {
    
    // display library hours as closed, 24 hrs or a range
    if ($today[0]['is_closed'] == 1) {
?>

hourswidget += 'Closed All Day</p>';

<?php
    } else if ($today[0]['open_time'] == $today[0]['close_time'] && $today[0]['is_closed'] == 0) {
?>

hourswidget += 'Open 24 Hrs</p>';

<?php
    } else {
?>

hourswidget += '<?= displayTime($today[0]['open_time']); ?> - <?= displayTime($today[0]['close_time']); ?></p>';

<?php
    }//closes if-elseif-else
    
  // if no return
  } else {
?>

hourswidget += 'N/A';

<?php
  }//closes if-else
?>

hourswidget += '</div>';

<?php
} else if ($display == 'status') {
  
  // grab status based on date, time and location (library hours only)
  $status = displayCurrentStatus($currentdate, $currenttime, $location1);
?>

hourswidget = '<div class="hours-widget"><p><strong><?= $status; ?></strong></p></div>';

<?php
} else if ($display == 'homepage') {
  
  // grab status based on date, time and location (library hours only)
  $widget = displayLocationsStatusHomepage();
?>

hourswidget = '<?= $widget; ?>';

<?php
} else {
?>

hourswidget = '<div class="hours-widget"><p>Sorry, no hours available for this view.</p></div>';

<?php
}//closes if-elseif-else
?>

if (typeof jQuery != 'undefined') {  

<?php
  // add table styles with jQuery
  if ($display == 'table') {
?>

document.write(hourswidget);

jQuery('.hours').css({ 'background-color' : '#fff', 'border' : '1px solid #ddd', 'border-spacing' : '1px', 'margin-bottom' : '0', 'max-width' : '98%' });
jQuery('.hours caption').css({ 'font-size' : '125%', 'line-height' : '100%', 'margin-bottom' : '8px', 'margin-top' : '0', 'font-weight' : 'bold', 'text-align' : 'center', 'background-color' : '#fff' });
jQuery('.hours th').css({ 'font-size' : '100%', 'color' : '#333', 'background-color' : '#eee', 'text-align' : 'left', 'border' : '1px solid #ddd', 'text-transform' : 'capitalize' });
jQuery('.hours th .nowrap').css({ 'white-space' : 'nowrap' });
jQuery('.hours td, .hours th').css({ 'border-bottom' : '1px solid #ddd', 'letter-spacing' : '0', 'padding' : '3px 5px' });
jQuery('.hours tr.even td').css('background', 'none');
jQuery('.hours tr.odd').css('background-color', '#efefef');
jQuery('.widget-note').css({ 'font-size' : '85%', 'background-color' : '#efefef' });
jQuery('.hours + p').css('margin-top', '8px');

<?php
    if ($shorttable != 'yes') {
?>

jQuery('.hours tr.today').css('background-color', '#ffffbb');

<?php
    }//closes if
?>

<?php
  // add text styles with jQuery, write into div (if it exits)
  } else if ($display == 'text') {
  
    // grab name for div id
    $URLname = getNameIDs($location1);
?>

// required to accommodate Learning Commons fancy box js
if (document.getElementById('<?= $URLname; ?>')) {
  document.getElementById('<?= $URLname; ?>').innerHTML=hourswidget;
} else {
  document.write(hourswidget);
}//closes if-else

jQuery('.hours-widget').css('margin', '10px 0');
jQuery('.hours-widget h2').css({ 'font-size' : '120%', 'line-height' : '110%', 'margin-bottom' : '15px' });
jQuery('.hours-widget p').css('margin', '4px 0');
jQuery('.hours-widget p .day').css({ 'display' : 'inline-block', 'width' : '90px' });

<?php
  // add status styles with jQuery
  } else if ($display == 'status') {
?>

document.write(hourswidget);

jQuery('.hours-widget').css('margin', '10px 0');
jQuery('.hours-widget .open, .hours-widget .closed').css({ 'font-variant' : 'small-caps', 'font-size' : '120%' });

<?php
  // add today styles with jQuery
  } else if ($display == 'today') {
?>

document.write(hourswidget);

jQuery('.hours-widget').css('margin', '10px 0');

<?php
  } else if ($display == 'homepage') {
?>

document.write(hourswidget);

<?php
  }// closes if-elseif
?>

} else {

document.write(hourswidget);

}//closes if-else
