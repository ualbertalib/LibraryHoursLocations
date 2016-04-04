<?php
//need to start a session because this page is called directly via ajax. the $_SESSION['language'] variable is set
// in index.php. Using sessions was the easiest way to keep the language as french for dates and other tasks
session_start();

if ($_SESSION['language']=='fr'){
  setlocale(LC_ALL, 'fr_FR'); 
}else{
  setlocale(LC_ALL, 'en_CA'); 
}

// pull in functions file, which includes dbConnect file
$directory = dirname(dirname(__FILE__));
require_once($directory.'/functions.php');




// default if no location, year, month selected
if (empty($location_id)) {
  
  $location_id = rv('location_id', 3); // 3 = Asian (first in locations list)
  
  $requestYear = rv('year', date('Y'));
  if (!preg_match('/^20[0-9]{2}$/', $requestYear)) {
	  $requestYear = date('Y');
  }

  $requestMonth = rv('month', date('n'));
  if (!is_numeric($requestMonth) || $requestMonth < 1 || $requestMonth > 12) {
    $requestMonth = date('n');
  }
  
  $requestMonth = ltrim($requestMonth, '0');
  
  if ($requestMonth*1 < 10) {
	  $requestMonth = '0'.$requestMonth;
  }

}//closes if

// set name id and reference URL (needed for AJAX reload)
$nameID = getNameIDs($location_id);
$refurl = getURL($nameID);

// calendar info
$date = strtotime("$requestYear-$requestMonth");
$days_in_month = date('t', $date);
$day = 1*date('d', $date);					
$month = 1*date('m', $date);					
$year = date('Y', $date);
$first_day = mktime(0,0,0, $month, 1, $year);  // generate the first day of the month
$title = utf8_encode(strftime('%B', $first_day));  // get the month name 
$blank = strftime('%w', $first_day);  // find out what day of the week the first day of the month falls on 

/* schema.org stuff
itemscope itemtype="http://schema.org/Library" */
?>
          <section class="hours" >
            
            <button class="prev-month" value="<?= $location_id ?>">&lt; <?php echo langConvert('Prev'); ?></button>
            <button class="next-month" value="<?= $location_id ?>"><?php echo langConvert('Next'); ?> &gt;</button>
            
            <span class="monthheading"><?= $title.' '.$year ?></span>
            
                        <section class="hours-table">
              
              <?php
              // display the month's hours in collapsed dl lists (by category, with colours corresponding to the calendar)
              echo displayHoursByMonth($month, $year, $location_id);
              ?>
              
            </section><!-- closes hours table -->
            
            <section class="calendar">
              
              <table class="month">
                <thead><tr>
                  <th>Su</th>
                  <th>Mo</th>
                  <th>Tu</th>
                  <th>We</th>
                  <th>Th</th>
                  <th>Fr</th>
                  <th>Sa</th>
                </tr></thead>
                
                <?php
                // this counts the days in the week, up to 7
                $day_count = 1;
                
                echo '
                <tbody>';
                
                if ($blank > 0) {
                    echo '
                  <tr>';
                }//closes if
                
                // first we take care of those blank days
                while ($blank > 0) { 
                  
                  echo '<td></td>'; 
                  
                  $blank = $blank-1; 
                  $day_count++;
                
                }//closes while 
                
                // this sets the first day of the month to 1 
                $day_num = 1;
                
                // count up the days, until we've done all of them in the month
                while ($day_num <= $days_in_month) {
                  
                  if ($day_count == 1) {
                    echo '
                  <tr>';
                  }//closes if
                  
                  $year_month_day="$year-";
                  
                  if ($month < 10) {
                    $year_month_day .= "0$month-";
                  } else {
                    $year_month_day .= "$month-";
                  }//closes if-else
                  
                  if ($day_num < 10) {
                    $year_month_day .= "0$day_num";
                  } else {
                    $year_month_day .= "$day_num";
                  }//closes if-else
                  
                  // find ranges that apply to this month
                  // returns: BEGIN_DATE, END_DATE, CATEGORY_ID 
                  $ranges = getRangesByMonth($month, $year, $location_id);
                  $countrange = count($ranges);
                  
                  for ($ii = 0; $ii < $countrange; $ii++) {
                    
                    // find matching ranges for the looped date
                    if ($ranges[$ii]['begin_date'] <= $year_month_day && $year_month_day <= $ranges[$ii]['end_date']) {
                    
                      // store the appropriate td for the given category (checking for matches to today's date)
                      switch ($ranges[$ii]['category_id']) {
                      
                        case 7:
                        $calendarday = (date("Y-m-d") == $year_month_day) ? '<td class="exception today"><strong>'.$day_num.'</strong>' : '<td class="exception">'.$day_num.'</td>';
                        break;
                        
                        case 6:
                        $calendarday = (date("Y-m-d") == $year_month_day) ? '<td class="exam today"><strong>'.$day_num.'</strong>' : '<td class="exam">'.$day_num.'</td>';
                        break;
                        
                        case 5:
                        $calendarday = (date("Y-m-d") == $year_month_day) ? '<td class="holiday today"><strong>'.$day_num.'</strong>' : '<td class="holiday">'.$day_num.'</td>';
                        break;
                        
                        case 4:
                        $calendarday = (date("Y-m-d") == $year_month_day) ? '<td class="summer-alternate today"><strong>'.$day_num.'</strong>' : '<td class="summer-alternate">'.$day_num.'</td>';
                        break;
                        
                        case 3:
                        $calendarday = (date("Y-m-d") == $year_month_day) ? '<td class="summer today"><strong>'.$day_num.'</strong>' : '<td class="summer">'.$day_num.'</td>';
                        break;
                        
                        case 2:
                        //New: Make intersession (category 2) the same as exception (category 7)
                        $calendarday = (date("Y-m-d") == $year_month_day) ? '<td class="intersession today"><strong>'.$day_num.'</strong>' : '<td class="intersession">'.$day_num.'</td>';
                        //$calendarday = (date("Y-m-d") == $year_month_day) ? '<td class="intersession today"><strong>'.$day_num.'</strong>' : '<td class="intersession">'.$day_num.'</td>';
                        break;
                        
                        case 1:
                        $calendarday = (date("Y-m-d") == $year_month_day) ? '<td class="regular today"><strong>'.$day_num.'</strong>' : '<td class="regular">'.$day_num.'</td>';
                        break;
                        
                        default:
                        $calendarday = '<td class="regular">'.$day_num.'</td>';
                        break;
                      
                      }//closes switch
                      
                    }//closes if
                    
                  }//closes for
                  
                  // now display calendar day (if set will display last looped result, which is highest category id) or just the date
                  if (isset($calendarday)) {
                    echo $calendarday;
                  } else {
                    echo '<td>'.$day_num.'</td>';
                  }//closes if-else
                  
                  $day_count++;
                  $day_num++;
                  
                  // make sure we start a new row every week
                  if ($day_count > 7)	{
                    echo '</tr>';
                    $day_count = 1;
                  }//closes if
                  
                  // reset calendar day to null before starting over the while loop (accounts for future dates not surrounded by other hours)
                  $calendarday = null;
                
              }//closes while
              
              // finish out the table with some blank details if needed
              if ($day_count > 1 && $day_count <= 7) {
                
                while ($day_count > 1 && $day_count <= 7) { 
                  echo '<td></td>'; 
                  $day_count++; 
                }//closes while
                
                echo '</tr>';
                
              }//closes if
              
              echo '
                </tbody>
              </table>'; 
              ?>
              
              <?php
              // reference URLs not all the same as database-saved, site URLs <-- ADD/DELETE HERE TO ADD/DELETE LOCATION (IF DIFFERENT REF URL NEEDED)
              if ($nameID === 'koerner') {
                $refurl = null;             
              }	else if ($nameID === 'law') {
                $refurl = null;
              }//closes if-elseif
              
              if (isset($refurl)) {
              ?>
              
              
              
              <?php
              }//closes if
              ?>
              
            </section><!-- closes calendar -->
            
            
            
          </section><!-- closes hours -->