<?php
// pull in functions file, which includes dbConnect file
$directory = dirname(dirname(__FILE__));
require_once($directory.'/functions.php');

// establish year and month for calendar
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
?>

<ul class="breadcrumb expand">
  <li><a href="http://www.library.ualberta.ca" title="UofA Library">Library Home</a> <span class="divider">/</span></li>
  <li>Hours &amp; Locations</li>
</ul>

<div class="grid">

  <header id="hours-header">
    <h1><a href="" title="Hours and Locations"><img src="img/hours-locations-header.gif" title="Hours and Locations" /></a></h1>
  </header>
  
  <div class="row-fluid">
    
    <div class="span5 left-side">
      
      <div class="switcher visible-phone">
        <p><strong>See:</strong> <a class="switch-map"><img src="img/maps.png" /> Map View</a></p>
      </div>
      
      <?= displayLocationsStatus(); ?>
      
    </div><!-- closes first column -->
    
    <div class="span7 right-side">
    
      <div id="map">
        
        <div id="api">
        </div>
        
        <div id="slide-content" class="scrollpane">
        
          <?php
          // grab array of library name ids (login names)
          $name_id = getNameIDs();
          
          // loop through these for the panel display
          for ($i = 0; $i < count($name_id); $i++) {
          
            // smaller functions used for future flexibility
            $nameid = $name_id[$i];
            $id = getID($name_id[$i]);
            $url = getURL($name_id[$i]);
            $name = getName($name_id[$i]);
            $building = getBuilding($name_id[$i], 'at ');
            $description = getDescription($name_id[$i]);
            $notes = getHoursNotes($name_id[$i]);
            $map = getMapCode($name_id[$i]);
            $address = getAddress($name_id[$i]);
            $phone = getPhone($name_id[$i]);
            $accessurl = getAccessURL($name_id[$i]);
            $currentdate = date('Y-m-d');
            $currenttime = date('H:i:s');
            ?>
            
            <a name="view-<?= $nameid ?>"></a>
            <div id="<?= $nameid ?>" class="branch">
            
              <div class="controls visible-phone">
                <p class="status"><?= displayCurrentStatus($currentdate, $currenttime, $id); ?></p>
                <p><a class="button green show-locations">&laquo; All Locations</a></p>
              </div>
              
              <a class="return-to-map close-box">Close</a>
              
              <section class="bio">
                
                <header>
                  <h1><a href="<?= $url; ?>"><?= $name; ?></a></h1>
                  <h3><?= $building; ?></h3>
                </header>
                
                <p class="theurl"><a href="<?= $url ?>" class="external"><?= $url ?></a></p>
                
               
              
              </section><!-- closes bio -->
              
              <?= $notes ?>
              
              <?php if ($nameid != "hamber" && $nameid != "stpauls" && $nameid != "music") { ?>
              
              <section id="calendar_<?= $nameid ?>" class="calendar_wrapper"> 
                
                <?php $location_id = $id;
                include('includes/calendar.inc.php');
                ?>
                
              </section><!-- closes calendar wrapper -->
              
              <?php } else if ($nameid == "music") { ?>
                          
              
              <?php } else { ?>
              
              
              <?php } ?>

              <section class="bio">
                 <div class="quiet">
                  <?= $description; ?>
                </div>

              </section>
              
              <section class="contact">
              
                <span class="library-img"></span>
                
                <div class="small-left">
                  
                  <h5>Address</h5>
                  
                  <address>
                    <a href="http://maps.google.com/maps?q=<?= $map ?>+(<?= urlencode($name) ?>)&z=16&ll=<?= $map ?>&iwloc=A" title="View on Google Maps" class="external"><?= $address ?></a>
                  </address>
                  
                  <?php if ($accessurl != '') { ?>
                  <p><a href="<?= $accessurl ?>" title="Disability Access" class="external"><img src="img/wheelchair.gif" alt="Disability Access" height="32" width="32" class="disability" /> Disability Access</a></p>
                  <?php }//closes if ?>
                  
                  <p class="hidden-phone"><a class="button return-to-map">Map Overview</a></p>
                  
                </div>
                
                <h5>Phone</h5>
                <p class="visible-desktop"><?= $phone ?></p>
                <p class="hidden-desktop"><a href="tel:1+<?= $phone ?>"><?= $phone ?></a></p>
                
                <div class="controls visible-phone">
                  <p><a class="button green show-locations map-view">&laquo; Map Overview</a></p>
                </div>
              
              </section><!-- closes contact -->
            
            </div><!-- closes branch -->
          
          <?php }//closes for ?>
        
        </div><!-- closes slide-content -->
        
      </div><!-- closes map --><br />
      
    </div><!-- closes second column -->
    
  </div><!-- closes row-fluid -->

  <div class="admin">
    
    <?php
    // footer links depend on dev/prod server
    if ($_SERVER['SERVER_NAME'] != "kemano.library.ubc.ca" && $_SERVER['SERVER_NAME'] != "hours-dev.library.ubc.ca") { ?>
    <a href="http://hours.library.ualberta.ca">Staff Admin Login</a><br />
    <?php } else { ?>
    <a href="http://hours.library.ualberta.ca">Dev Admin Login</a><br />
    <?php } ?>
    <a href="print.php">Signage and Bookmarks</a>
   
  </div><!-- closes admin -->

</div><!-- closes grid -->

<script type="text/javascript">

  // pass month and year to js
  var initialMonth = <?php echo $requestMonth; ?>;
  var initialYear = <?php echo $requestYear; ?>;
  var branchYM = {};
   
  $(function(){
    $('.branch').each(
      function(){
        branchYM[$(this).attr('id')]={year:initialYear,month:initialMonth};
      }
    );
  });

  // for calendar display
	function displayMonth(branch,id,offset) {
		
    branchYM[branch].month += offset;
    
    // going backward from Jan
		if (branchYM[branch].month == 0) {
			branchYM[branch].year--;
			branchYM[branch].month=12;
		}
		
    // going forward from Dec
    if (branchYM[branch].month == 13) {
			branchYM[branch].year++;
			branchYM[branch].month=1;
		}
		
    var loadUrl = "includes/calendar.inc.php";
		
    $('#calendar_'+branch).load(
		  loadUrl, {
			  location_id:id,
			  year:branchYM[branch].year,
			  month:branchYM[branch].month
		  },
		  function() {
			  $('#calendar_'+branch)[0].innerHTML+='';
        // again, need to hack the height for jScrollPane to work w/toggle description, so animate empty dd when library panel selected 
        $('#library dl.toggle dd.toggle-item.empty').animate({ height: '0px' }, 0, function() {});
        // now re-initialize scrollpane and display it (if needed)
        setTimeout(function() {    
          $('.scrollpane').jScrollPane();
          $('.jspVerticalBar').show();
        }, 20);
		  }
		);
    
	};//closes function
	
	$(".prev-month").live(
		'click',
		function(){
			var branch = $(this).parents("div").attr("id");
			var value = parseInt($(this).attr("value")); 
			displayMonth(branch,value,-1);
		}
	);  

	$(".next-month").live(
		'click',
		function(){
			var branch = $(this).parents("div").attr("id");
			displayMonth(branch,$(this).val(),1);
		}
	);  
  
</script>