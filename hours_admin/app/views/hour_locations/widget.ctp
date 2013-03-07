<div class="locations view twelve columns nest">

<?php 
	// if no location set, show form to select one
	if(!isset($hourLocation['HourLocation']['name'])) {
		echo '<h2>Hours Widget</h2>';		
		echo $this->Form->create('HourLocation');
		echo '<div class="first four columns">';
		echo $this->Form->input('HourLocation.id',array('type'=>'select','label'=>'Select Location','options'=>$hourLocations));
 		echo '</div>';
 		echo '<div class="clear"></div>';
 		echo $this->Form->submit('Get Widget Code',array('name'=>'submit','div'=>false,'class'=>'medium button'));
 		echo $this->Form->end();	
	} else {	
		// function to display widget code and preview given a parameter string
		function displayCodePreview($paramString) {
			if(empty($paramString)) {
				$html = '<div class="warning message">Error: no parameter string for widget</div>';
			}
			$html = '
				<div class="script_code">
					&lt;script type="text/javascript" src="'.PUBLIC_URL.'/widget-hours-js.php?'.$paramString.'"&gt;&lt;/script&gt;
				</div>
				<h4>Preview:</h4>
				<div class="preview">
					<script type="text/javascript" src="'.PUBLIC_URL.'/widget-hours-js.php?'.$paramString.'"></script>
				</div>
			';
			return $html;
		}
?>
	<div class="first eight columns">
		<h2><?php  echo 'Hours Widgets for ' . $hourLocation['HourLocation']['name'];?></h2>
	</div>
	<div class="last three columns text-right">
		<?php 
		if(($_SERVER['REMOTE_USER'] != 'hours' || $_SERVER['REMOTE_USER'] != 'hours_admin') && !$reference_hours) {
				echo $this->Html->link(__('Edit Location',true),array('controller'=>'hour_locations','action'=>'edit',$hourLocation['HourLocation']['id']),array('class'=>'medium button'));
		}
		?>
	</div>
	<div class="clear"></div>
	<p>
	Choose from the four widget types below, and copy and paste the script tag for the desired widget into the source code of the web page where you want the widget to appear. Please <a href="http://helpdesk.library.ubc.ca/lsit/web-services-support/">request help</a> if you need assistance.
	</p>
	
	<h3>Weekly Hours Table Widget</h3>
		<div class="left_margin">
			<p>Embed a table that shows this week's hours, with reference hours added when available.</p>	
			<?php 
			if($hourLocation['HourLocation']['id'] == 6) {
			// If the location is IKBLC
				echo displayCodePreview("location1=6&location2=7&type2=2&location3=11");
			} elseif($hasReferenceHours) { // end if IKBLC (id=6) 
			//If the location DOES have reference hours:			
				echo displayCodePreview("location1=".$hourLocation['HourLocation']['id']."&location2=".$hourLocation['HourLocation']['id']);		
			} else { // end if location does  have reference hours
			//If the location does NOT have reference hours:
				echo displayCodePreview("location1=".$hourLocation['HourLocation']['id']);  
			} // end if location does not have reference hours
			?>
			<!-- only the table widget includes widget note -->
			<h4>Edit Widget Note</h4>
			<?php 
				echo $this->Form->create('HourLocation');
				echo $this->Form->input('id');
				echo $this->Form->input('widget_note',array('type'=>'textarea','rows'=>'4','cols'=>'60'));
				echo $this->Form->submit('Save Changes',array('name'=>'submit','div'=>false,'class'=>'medium blue button'));
			 	echo $this->Form->end();
			?>
		</div>
	<h3>Weekly Hours Text Widget</h3>
		<div class="left_margin">
			<p>Embed a collapsed text display of this week's hours.</p>
			<?php echo displayCodePreview("location1=".$hourLocation['HourLocation']['id']."&display=text"); ?>
		</div>
	<h3>Today's Hours with Reference Widget</h3>
                <div class="left_margin">
                        <p>Embed a table of today's building hours and reference hours, with widget note included.</p> 
			<?php
                        if($hourLocation['HourLocation']['id'] == 6) {
                        // If the location is IKBLC
                                echo displayCodePreview("location1=6&location2=7&type2=2&location3=11&shorttable=yes");
                        } elseif($hasReferenceHours) { // end if IKBLC (id=6) 
                        //If the location DOES have reference hours:                    
                                echo displayCodePreview("location1=".$hourLocation['HourLocation']['id']."&location2=".$hourLocation['HourLocation']['id']."&shorttable=yes");
                        } else { // end if location does  have reference hours
                        //If the location does NOT have reference hours:
                                echo displayCodePreview("location1=".$hourLocation['HourLocation']['id']."&shorttable=yes");
                        } // end if location does not have reference hours
                        ?>
                </div>
	<h3>Today's Hours Widget</h3>
		<div class="left_margin">
			<p>Embed a simple text line with the current day's hours.</p>
			<?php echo displayCodePreview("location1=".$hourLocation['HourLocation']['id']."&display=today"); ?>			
		</div>
	<h3>Today's Status Widget</h3>
		<div class="left_margin">
			<p>Embed a simple text line showing if a location is currently closed or open (with closing time).</p>
			<?php echo displayCodePreview("location1=".$hourLocation['HourLocation']['id']."&display=status"); ?>
		</div>
<?php } // end else if isset hourLocation name ?>
</div>
