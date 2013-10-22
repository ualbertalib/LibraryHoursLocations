<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

<title>Hours and Locations | UBC Library Signage &amp; Bookmarks</title>

<link rel="icon" type="image/vnd.microsoft.icon" href="http://www.library.ubc.ca/_ubc_clf/img/favicon.ico" />
<link href="css/print-hours.css" type="text/css" rel="stylesheet" media="all" />
<!--[if IE]><style>.bookmark { margin-left: 3.8%; }</style><![endif]-->

</head>

<body>

<div id="wrapper">
  
<?php 
require_once('functions.php');


//**** VARIABLES ****


// grab submitted hidden values or default to regular hours full view
$month = isset($_POST['month']) ? sanitize($_POST['month']) : '9';
$category = isset($_POST['category']) ? sanitize($_POST['category']) : '1';
$version = isset($_POST['version']) ? sanitize($_POST['version']) : 'full';


// grab the applicable ranges
// returns: ID, BEGIN, END
$ranges = getAllRanges($month, $category);

// set variables based on ranges (id returns SQL-friendly list of id(s), begin pulls in range first date, end pulls in range last date), message concatenates print_note from all ranges
$id = $ranges['id'];
$begin = $ranges['begin'];
$end = $ranges['end'];
$message = $ranges['message'];


// establish which title to display (if any), based on category and month
if ($category == 5) {

  switch ($month) {
  
    // December holiday hours
    case 12:
    $title = 'December Holiday';
    //$message = 'Unless otherwise noted, all locations, including Irving K. Barber Learning Centre, are CLOSED on Christmas, Boxing Day and New Year\'s Day.';
    break;
    
    // Easter holiday hours
    case 3:
    $title = 'Easter Holiday';
    //$message = '';
    break;
    
    // Easter holiday hours (month start may vary)
    case 4:
    $title = 'Easter Holiday';
    //$message = '';
    break;
    
    default:
    $title = '';
    //$message = '';
    break;
  
  }//closes switch

} else if ($category == 2) {

  switch ($month) {
    
    // summer intersession hours
    case 8:
    $title = 'Summer Intersession';
    //$message = 'Unless otherwise noted, all locations except Irving K. Barber Learning Centre are CLOSED on Labour Day.';
    break;
    
    // spring intersession hours
    case 5:
    $title = 'Spring Intersession';
    //$message = '';
    break;
    
    // spring intersession hours (month start may vary)
    case 4:
    $title = 'Spring Intersession';
    //$message = '';
    break;
    
    default:
    $title = '';
    //$message = '';
    break;
  
  }//closes switch
  
} else {

  switch ($month) {
    
    // fall/winter hours
    case 9:
    $title = 'Fall/Winter';
    //$message = ''/*Unless otherwise noted, all locations except Irving K. Barber Learning Centre are CLOSED on Thanksgiving and Remembrance Day. Modified hours will be posted for Christmas and Easter.*/;
    break;
    
    // summer session term 2
    case 7:
    $title = 'Summer Session Term 2';
    //$message = 'Unless otherwise noted, all locations except Irving K. Barber Learning Centre are CLOSED on BC Day.';
    break;
    
    // summer session term 1
    case 5:
    $title = 'Summer Session Term 1';
    //$message = 'Unless otherwise noted, all locations except Irving K. Barber Learning Centre are CLOSED on Victoria Day, Canada Day and Monday July 2<sup>nd</sup>.';
    break;
    
    default:
    $title = '';
    //$message = '';
    break;
  
  }//closes switch
  
}//closes if-elseif-else


// if ranges found in database: based on version selected, set SQL limit, location count start and print header
if ($id && $version == "full") {
  
  $limit = "20"; // what location(s) not to include (Robson Square)
  $location_count = 1; //allows for hard-coded BMB note in first column
  
  $print = '
  <div id="header">

    <img src="img/ubc-library-logo-small.gif" width="346" height="60" alt="UBC Library Logo" id="logo" />
    
    <h1>'.$title.' Hours</h1>
    
    <h2>'.date('l, M j, Y', strtotime($begin)).' - '.date('l, M j, Y', strtotime($end)).'</h2>
    
    <p>'; if ($message) { $print .= '&mdash; '.$message.' &mdash;'; } $print .= '</p>

  </div><!-- closes header -->
  
  <div id="hours">
  
    <div class="column">';

} else if ($id && $version == "bookmark") {
  
  $limit = "20"; // what location(s) not to include (Robson Square)
  $location_count = 0;
  
  $print = '
    <div class="column">
    
      <h1>UBC Library Hours</h1>
      
      <h2>'.date('M j, Y', strtotime($begin)).' - '.date('M j, Y', strtotime($end)).'</h2>';

// if no range found in database
} else {
  
  $limit = ""; // what location(s) not to include
 
  $print = '
  <div id="header">

    <img src="img/ubc-library-logo-small.gif" width="346" height="60" alt="UBC Library Logo" id="logo" />
 
    <h1>Hours Unavailable</h1>
 
    <h2>Please return to the <a href="print.php">print page</a> and select another version.</h2>

  </div><!-- closes header -->
  
  <div id="hours">';
  
}//closes if-elseif-else


// set up hours match as false outside of the loop
$match = false;


// if range(s) found in database
if ($id && $begin && $end) {
  
  // grab the hours
  // returns: ID, NAME, PARENT, HOUR_CATEGORY_ID, DAY_OF_WEEK, OPEN_TIME, CLOSE_TIME, IS_CLOSED, IS_TBD, BEGIN_DATE, END_DATE
  $results = getAllHours($category, $limit, $id, $begin, $end);
  $count = count($results);

// if no range found in database
} else {

  $results = "";

}//closes if-elseif-else



//**** DISPLAY LOOP ****


// if no database results returned or no valid version selected
if (!$results || ($version != "full" && $version != "bookmark")) {
  
  $print .= '
    <div class="column">
    
      <p>Sorry, no hours available for this view. Please contact <a href="http://helpdesk.library.ubc.ca/lsit/web-services-support/">Web Services</a> if you are unable to locate the correct print hours.</p>
      
    </div><!-- closes column -->';
  
// otherwise, proceed through hours loop
} else {

  // loop through each day from the hour_days table
  for ($i = 0; $i < $count; $i++) {
    
    // set the previous location id
    $prev_id = isset($results[$i-1]['id']) ? $results[$i-1]['id'] : '0';
  
    // skip UA (combines with RBSC)
    if ($results[$i]['name'] == 'University Archives') {
      continue;
    }//closes if
    
    // when a location id changes, display the name
    if ($prev_id != $results[$i]['id']) {
      
      // new column every set number of locations (5 for full view, 9 for bookmark view, 4 for December holiday)
      if (($location_count != 1 && $location_count%5 == 0 && $version == "full" && ($category != 5 && $month != 12)) || ($location_count != 0 && $location_count%9 == 0 && $version == "bookmark") || ($location_count != 1 && $location_count%4 == 0 && $version == "full" && ($category == 5 && $month == 12))) {
      
        $print .= '
      
    </div><!--closes column -->
    <div class="column">';
      
      }//closes if
      
      // now add to location count (adding extra to Okanagan, which runs long in fall '12, Dec '12)
      if ($results[$i]['name'] == 'Okanagan Library') {
        if ($category == 5 && $month == 12) {
          $location_count = $location_count+5;
        } else {
          $location_count = $location_count+3;
        }//closes if-else
      } else {
        $location_count++;
      }//closes if-else
      
      // adjust names for special cases, otherwise display sub-locations as h4 and locations as h3
      if ($results[$i]['name'] == 'Biomedical Branch Library') {
    
        $print .= '
          
      <h3>Biomedical Branch Library</h3>
      <h5>at Vancouver General Hospital</h5>
      <p class="note">Access is limited to building pass-holders after 7pm Monday through Friday and all day Saturday and Sunday.</p>';
      
      } else if ($results[$i]['name'] == "Hamber Library") {
      
      $print .= '
          
      <h3>Hamber Library</h3>
      <h5>at Children\'s and Women\'s Health Centre</h5>';
      
      } else if ($results[$i]['name'] == "Rare Books and Special Collections") {
      
        $print .= '
      
      <h4>Rare Books and Special Collections &amp; University Archives</h4>';
        
      } else if ($results[$i]['name'] == "X̱wi7x̱wa Library") { 
      
        $print .= '
          
      <h3><span class="unicode">X̱wi7x̱wa</span> Library</h3>';
    
      } else if ($results[$i]['parent'] != null) { 
        
        $print .= '
          
      <h4>'.$results[$i]['name'].'</h4>';
      
      } else {
        
        $print .= '
          
      <h3>'.$results[$i]['name'].'</h3>';
        
      }//closes if-elseif-else
      
    }//closes if
    
    
    //**** COLLAPSE HOURS DISPLAY **** 
    
    
    // next variables to compare the next set of hours with the current set of hours
    $open_time = isset($results[$i+1]['open_time']) ? $results[$i+1]['open_time'] : '0';
    $close_time = isset($results[$i+1]['close_time']) ? $results[$i+1]['close_time'] : '0';
    $is_closed = isset($results[$i+1]['is_closed']) ? $results[$i+1]['is_closed'] : '0';
    $is_tbd = isset($results[$i+1]['is_tbd']) ? $results[$i+1]['is_tbd'] : '0';
    $location_id = isset($results[$i+1]['id']) ? $results[$i+1]['id'] : '0';
    
    // prev variables to compare previous set of hours with the current set of hours
    $prev_location = isset($results[$i-1]['id']) ? $results[$i-1]['id'] : '0';
    $prev_date = isset($results[$i-1]['begin_date']) ? $results[$i-1]['begin_date'] : '0';
    
    // when same location has a new range to display (should apply to summer and exam hours only), show the start date
    if ($prev_location == $results[$i]['id'] && $prev_date != $results[$i]['begin_date'] && $results[$i]['hour_category_id'] != '5' && $results[$i]['hour_category_id'] != '7') {
      
      // exam hours are date range, summer hours are starting date
      if ($results[$i]['hour_category_id'] == '6') {
        $print .= '<p class="bold change"><span>'.date('F j', strtotime($results[$i]['begin_date'])).'-'.date('F j', strtotime($results[$i]['end_date'])).':</span></p>';
      } else {
        $print .= '<p class="bold change"><span>Starting '.date('F j', strtotime($results[$i]['begin_date'])).':</span></p>';
      }//closes if-else
    
    }//closes if
    
    // if the next set of hours is the same (and not a different location or a second range), set the range start date, change match to true, break the loop
    if (($results[$i]['open_time'] == $open_time && $results[$i]['close_time'] == $close_time) && ($results[$i]['is_closed'] == $is_closed && $results[$i]['is_tbd'] == $is_tbd) && $match == false && $results[$i]['id'] == $location_id && $results[$i]['day_of_week'] != 'Sunday') {
      
      // holiday/exception hours display as dates, regular hours display as days
      if ($results[$i]['hour_category_id'] == 5 || $results[$i]['hour_category_id'] == 7) {
      
        // if looking at a holiday hours layout, drop "exception" look
        if ($category == 5) {
          $start_range = date('M j', strtotime($results[$i]['day_of_week']));
        } else {
          $start_range = '<strong>*'.date('M j', strtotime($results[$i]['day_of_week']));
        }//closes if-elseif
        
        $thismonth = date('M', strtotime($results[$i]['day_of_week']));
        
      } else {
        $start_range = date('D', strtotime($results[$i]['day_of_week']));
      }//closes if-else
      
      $match = true;
      continue;
    
    // if the next set of hours is the same (and not a different location or a second range) AGAIN, just skip to the next one
    } else if (($results[$i]['open_time'] == $open_time && $results[$i]['close_time'] == $close_time) && ($results[$i]['is_closed'] == $is_closed && $results[$i]['is_tbd'] == $is_tbd) && $match == true && $results[$i]['id'] == $location_id && $results[$i]['day_of_week'] != 'Sunday') {
    
     continue;
    
    // otherwise, display the hours  
    } else  {
      
      $print .= '
      <p><span class="day">';
      
      // when a range has been set, echo it
      if ($match == true) {
      
        $print .= $start_range;
        
        // holiday/exception hours display as dates, regular hours display as days
        if ($results[$i]['hour_category_id'] == 5 || $results[$i]['hour_category_id'] == 7) {
        
          // display month again only if month has changed  
          if ($thismonth == date('M', strtotime($results[$i]['day_of_week']))) {
          
            if ($results[$i]['begin_date'] == $results[$i]['end_date']) {
              $print .= ' &amp '.date('j', strtotime($results[$i]['day_of_week']));
            } else {
              $print .= '-'.date('j', strtotime($results[$i]['day_of_week']));
            }//closes if-else
          
          } else {
          
            if ($results[$i]['begin_date'] == $results[$i]['end_date']) {
              $print .= ' &amp<br /> '.date('M j', strtotime($results[$i]['day_of_week']));
            } else {
              $print .= '-'.date('M j', strtotime($results[$i]['day_of_week']));
            }//closes if-else
          
          }//close if-else
          
          // closes "exception" look
          if ($category != 5) {
            $print .= '</strong>';
          }//closes if
            
        } else {
          
          $print .= '-'.date('D', strtotime($results[$i]['day_of_week']));
        
        }//closes if-else
      
      // otherwise, just display the current date
      } else {
      
        // holiday/exception hours display as dates, regular hours display as days
        if ($results[$i]['hour_category_id'] == 5) {
          
          // if looking at a holiday hours layout, drop "exception" look
          if ($category == 5) {
            $print .= date('M j', strtotime($results[$i]['day_of_week']));
          } else {
            $print .= '<strong>*'.date('D, M j', strtotime($results[$i]['day_of_week'])).'</strong>';
          }//closes if-else
          
        } else if ($results[$i]['hour_category_id'] == 7) {
          
          $print .= '<strong>*'.date('D, M j', strtotime($results[$i]['day_of_week'])).'</strong>';
        
        } else {
        
          $print .= date('l', strtotime($results[$i]['day_of_week']));
        
        }//closes if-else
      
      }//closes if-else
      
      $print .= '</span>';
    
      // now display the hours, first checking for TBD or closed booleans
      if ($results[$i]['is_tbd'] == 1) {
     
        $print .= '<span class="closed">N/A</span></p>';
        
      } else if ($results[$i]['is_closed'] == 1) {
     
        $print .= '<span class="closed">Closed</span></p>';
      
      } else if ($results[$i]['is_closed'] == 0 && $results[$i]['open_time'] == $results[$i]['close_time']) {
      
        $print .= 'Open 24 Hours';
      
      } else {
      
        $print .= displayTime($results[$i]['open_time']).'-'.displayTime($results[$i]['close_time']).'</p>';
      
      }//closes if-else
      
      //reset match to false to start the loop over
      $match = false;
      
    }//closes if-elseif-else
    
    
    //**** ENDING IMAGE/MESSAGE **** 
    
    
    // add additional info and close the column div during final iteration
    if ($i == $count-1) {
    
      // add qr code and "printed on" to full version
      if ($version == "full") {
        
        $print .= '
      
      <h3></h3>
      <img src="img/qr.png" height="155" width="155" />
      <p class="bottom"><strong>Current as of '.date('M j, Y').'</strong> <br />Check hours.library.ubc.ca for latest hours information.</p>
      
    </div><!-- closes column -->';
        
      // add message (if applicable) to bookmark version
      } else if ($version == "bookmark") {
      
        if ($message) {
          
          $print .= '
        
      <p class="bottom">'.$message.'</p>';
        
        }//closes if  
        
        $print .= '
        
    </div><!-- closes column -->';
      
      }//closes if-elseif
      
    }//closes if
    
  }//closes for

}//closes if-else


//**** SET FINAL DISPLAY **** 


// bookmarks repeat output four times, all others echo just once
if ($results && $version == "bookmark") {

  echo '
  <div class="bookmark">'
    .$print
    .$print.'
  </div><!-- closes bookmark -->
  
  <div class="bookmark">'
    .$print
    .$print.'
  ';

} else {
  
  echo $print;

}//closes if-else
?>
    
  </div><!-- closes hours/bookmark -->

</div><!-- closes wrapper -->

</body>
</html>