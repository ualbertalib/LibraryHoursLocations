<div class="locations form fit twelve columns nest">

<?php 
	echo $this->Form->create('HourGrouping');
?>
<div class="first seven columns">
	<h2><?php echo $this->data['HourLocation']['name'] . ' ' . $this->data['HourCategory']['category'] . ' ' . $this->data['HourType']['type'] . ' Hours'; ?></h2>
</div>
<div class="last five columns text-right">
	<?php echo $this->Form->submit('Save Changes',array('name'=>'submit','div'=>false,'class'=>'medium blue button')); ?>
	<span style="width:10px;">&nbsp;</span>
	<?php echo $this->Form->submit('Cancel',array('name'=>'cancel','div'=>false,'class'=>'medium button')); ?>
	<?php // admin and super-admin can delete
		if($_SERVER['REMOTE_USER'] == 'hours' || $_SERVER['REMOTE_USER'] == 'hours_admin') {
			echo '<span style="width:10px;">&nbsp;</span>';
			echo $this->Html->link(__('Delete Hours', true), array('action' => 'delete', $this->data['HourGrouping']['id']), array('class'=>'medium red button'), sprintf(__('Are you sure you want to delete hours from '. date("F d, Y",strtotime($this->data['HourDateRange']['begin_date']))." - ".date("F d, Y",strtotime($this->data['HourDateRange']['end_date'])). ' for ' . $this->data['HourLocation']['name'], true))); 
		}
	?>
</div>
<div style="clear:both;"></div>
	
 	<?php
 			echo $this->Form->input('id');
	 		echo $this->Form->input('modified_by',array('type'=>'hidden','value'=>$_SERVER['REMOTE_USER']));
 			echo $this->Form->input('modified_timestamp',array('type'=>'hidden','value'=>date('Y-m-d H:i:s')));
 		
 	    // only admin and super-admin can edit these
 	    if($_SERVER['REMOTE_USER']=='hours' || $_SERVER['REMOTE_USER']=='hours_admin') {	
	        echo '<div class="first five columns">';
			echo $this->Form->input('HourGrouping.hour_location_id',array('label'=>'Location','empty'=>true,'div'=>false));
			echo $this->Form->input('HourGrouping.orig_hour_location_id',array('type'=>'hidden','value'=>$origValues['HourGrouping']['hour_location_id']));
			echo '</div>';
			//echo '<div class="two columns">';
			echo $this->Form->input('HourGrouping.hour_category_id',array('type'=>'hidden','div'=>false));
	        echo $this->Form->input('HourGrouping.orig_hour_category_id',array('type'=>'hidden','value'=>$origValues['HourGrouping']['hour_category_id']));
	        //echo '</div>';
			echo '<div class="two columns">';
			echo $this->Form->input('HourGrouping.hour_type_id',array('label'=>'Type','empty'=>true,'div'=>false));
			echo $this->Form->input('HourGrouping.orig_hour_type_id',array('type'=>'hidden','value'=>$origValues['HourGrouping']['hour_type_id']));			
	        echo '</div><div style="clear:both;"></div>';
			echo $this->Form->input('HourGrouping.hour_date_range_id',array('type'=>'hidden','value'=>$origValues['HourGrouping']['hour_date_range_id']));
			if($this->data['HourGrouping']['hour_type_id'] != 3) {
			echo '<div class="first twelve columns nest row">';
			echo '<div class="first five columns"><label for="HourGroupingHourDateRangeId">Date Range</label>'. date("F d, Y",strtotime($this->data['HourDateRange']['begin_date']))." - ".date("F d, Y",strtotime($this->data['HourDateRange']['end_date'])).", ".$this->data['HourDateRange']['description']."</div>";
			echo '<div class="five columns"><label for="HourGroupingHourCategoryId">Category</label><span class="hours-category ';  if ($this->data['HourCategory']['category'] == 'Summer Alternate') { echo 'summer-alternate'; } else { echo strtolower($this->data['HourCategory']['category']); } echo '"></span> '.$this->data['HourCategory']['category']."</div>";
			echo '</div>'; // end first twelve columns nest row
			}
		} else {
			// hidden values for branch login form
			echo $this->Form->input('HourGrouping.hour_location_id',array('type'=>'hidden'));
			echo $this->Form->input('HourGrouping.orig_hour_location_id',array('type'=>'hidden','value'=>$origValues['HourGrouping']['hour_location_id']));
            echo $this->Form->input('HourGrouping.hour_type_id',array('type'=>'hidden'));
			echo $this->Form->input('HourGrouping.orig_hour_type_id',array('type'=>'hidden','value'=>$origValues['HourGrouping']['hour_type_id']));
            echo $this->Form->input('HourGrouping.hour_category_id',array('type'=>'hidden'));
			echo $this->Form->input('HourGrouping.orig_hour_category_id',array('type'=>'hidden','value'=>$origValues['HourGrouping']['hour_category_id']));
			echo $this->Form->input('HourGrouping.hour_date_range_id',array('type'=>'hidden','value'=>$origValues['HourGrouping']['hour_date_range_id']));
		}
			
		foreach($days as $key => $day) {
		
	?>	
		<div class="first two columns">
			<?php 
				echo $this->Form->input('HourDay.'.$key.'.id',array('type'=>'hidden'));
				echo $this->Form->input('HourDay.'.$key.'.modified_by',array('type'=>'hidden','value'=>$_SERVER['REMOTE_USER']));
 				echo $this->Form->input('HourDay.'.$key.'.modified_timestamp',array('type'=>'hidden','value'=>date('Y-m-d H:i:s')));
				if($this->data['HourGrouping']['hour_category_id'] == 5 || $this->data['HourGrouping']['hour_category_id'] == 7) {					
					echo '<span id="'.$weekdays[$key].'_text">'.$shortdays[$key].", ".date("m/d/Y",strtotime($this->data['HourDay'][$key]['day_of_week'])).'</span>';
				} else {
					echo '<span id="'.$weekdays[$key].'_text">'.$day.'</span>';
				}	
				echo $this->Form->input('HourDay.'.$key.'.day_of_week',array('type'=>'hidden','value'=>$day));
			?>
		</div>
		<div class="three columns">
			<?php
				$value = '';
				if(isset($this->data['HourDay'][$key]['open_time'])) {
					$value = date("g:i a",strtotime($this->data['HourDay'][$key]['open_time']));
				}
				echo $this->Form->input('HourDay.'.$key.'.open_time',array('type'=>'text','label'=>false, 'div'=>false, 'placeholder'=>'Enter open time, e.g. 10:00 am', 'value'=>$value));
			?>		
		</div>
		<div class="three columns">
			<?php  
				$value = '';
				if(isset($this->data['HourDay'][$key]['close_time'])) {
					$value = date("g:i a",strtotime($this->data['HourDay'][$key]['close_time']));
				}
				echo $this->Form->input('HourDay.'.$key.'.close_time',array('type'=>'text','label'=>false, 'div'=>false, 'placeholder'=>'Enter close time, e.g. 4:00 pm', 'value'=>$value));
			?>
		</div>
		<div class="two columns">
			<?php 
				echo '<label for="HourDay'.$key.'IsClosed">';
				echo 	$this->Form->input('HourDay.'.$key.'.is_closed',array('type'=>'checkbox','label'=>false, 'div'=>false, 'class'=>'is_closed'));
				echo '	<span>Is Closed</span>';
				echo '</label>';
			 ?>
		</div>
		<div class="last two columns">
			<?php 
				if($_SERVER['REMOTE_USER']=='hours' || $_SERVER['REMOTE_USER']=='hours_admin') { // not relevant for reference hours
					echo '<label for="HourDay'.$key.'IsTbd">';
					echo 	$this->Form->input('HourDay.'.$key.'.is_tbd',array('type'=>'checkbox','label'=>false, 'div'=>false, 'class'=>'is_closed'));
					echo '	<span>Is TBD</span>';
					echo '</label>';
				}
			 ?>
		</div>
		<div style="clear:both;"></div>
		
		
<?php	
	}	// end foreach day
?>

<div style="height:10px;">&nbsp;</div>

<?php 
	
	echo $this->Form->end();
?>

</div>

<script>
    $('.is_closed').click(function() {
        if($(this).attr('checked')) {
            if($(this).attr('id').indexOf('IsClosed') != -1) {
        		var substr_end = $(this).attr('id').indexOf('IsClosed');
        	} else if ($(this).attr('id').indexOf('IsTbd') != -1) {
        		var substr_end = $(this).attr('id').indexOf('IsTbd');
        	}	
            var idstart = $(this).attr('id').substr(0,substr_end);
            $("#"+idstart+"OpenTime").val("0:00");
            $("#"+idstart+"CloseTime").val("0:00");
            
        }
    });
    
/*
    $('#HourGroupingHourCategoryId').change(function() {   		
	    	//Need to keep original order, which is by date or by day of week depending on category when initially loading the page
	    	//Compare original category (HourGroupingOrigHourCategoryId) with new one
	    	if($(this).val() != $('#HourGroupingOrigHourCategoryId').val()) {
	    		if(($(this).val() == 5 || $(this).val() == 7) && ($('#HourGroupingOrigHourCategoryId').val() !== 5 && $('#HourGroupingOrigHourCategoryId').val() !== 7)) {
	    			// change weekday names to dates
	    			update_days($('#HourGroupingHourDateRangeId').val(),$(this).val(),"weekday");
	    		} else if (($(this).val() !== 5 && $(this).val() !== 7) && ($('#HourGroupingOrigHourCategoryId').val() == 5 || $('#HourGroupingOrigHourCategoryId').val() == 7)) {
	    			// change dates to weekday names
	    			update_days($('#HourGroupingHourDateRangeId').val(),$(this).val(),"date");
	    		}
	    	}    	
    });
*/
  
    function update_days(daterangeid, sortby) {
    	//lookup date range id and create list of days of week in the range
    	//hide any days of week by div id that are not in range    	
    	var ajaxdata={
                url:"<?php echo SCRIPT_URL; ?>/hours_date_range_lookup.php",
                dataType:'jsonp',
                data:{
                    daterangeid: daterangeid,
                    sortby: sortby
                },
                async: false,
                success:
                        function(data){
                        	$('#HourGroupingHourCategoryId').val(data.categoryid);
                            if(data.categoryid == 5 || data.categoryid == 7) {
                                // loop over data.days and hide days in the list
                                for(var i in data.daysout) {
                                	$('#'+data.daysout[i]).hide();
                                }
                                //alert("in success function");
                                for(var j in data.daysin) {
                                	//alert(typeof data.datesin[j]);
                                	if((typeof data.datesin[j] !== 'undefined') && data.datesin[j].length) {
                                		//alert("if datesin defined and has length");
	                                	$('#'+data.daysin[j]+'_text').text(data.datesin[j]);
	                                	$('#HourDay'+j+'DayOfWeek').val(data.datesin[j]);
									} else {
										//alert("change text and value");
										$('#'+data.daysin[j]+'_text').text(data.daysin[j]);
	                                	$('#HourDay'+j+'DayOfWeek').val(data.daysin[j]);
									}	
                                }
                                return true;
                },
                type:'get'
        };
        jQuery.ajax(ajaxdata);
    }
    
    $(document).ready(function() {
            $('.is_closed').each(function() {
                if($(this).attr('checked')) {
                    if($(this).attr('id').indexOf('IsClosed') != -1) {
	        		var substr_end = $(this).attr('id').indexOf('IsClosed');
		        	} else if ($(this).attr('id').indexOf('IsTbd') != -1) {
		        		var substr_end = $(this).attr('id').indexOf('IsTbd');
		        	}	
		            var idstart = $(this).attr('id').substr(0,substr_end);
		            $("#"+idstart+"OpenTime").val("0:00");
		            $("#"+idstart+"CloseTime").val("0:00");
                }    
            });
            
            
            $('input[id*="CloseTime"], input[id*="OpenTime"]').autocomplete({
	            source: function(request, response) {
                        url = "<?php echo ADMIN_URL; ?>/hours_autocomplete.php?term="+request.term;
	                //url = "http://kemano.library.ubc.ca/~jdearles/ltk/staffdirectory/hours_autocomplete.php?term="+request.term;
	                $.getJSON(url + '&callback=?', function(data) {
	                    console.log(data);
	                    response(data);
	                });
	            }
        	});
        	
        	//update_days($('#HourGroupingHourDateRangeId').val(), $('#HourGroupingHourCategoryId').val());
    });
</script>
