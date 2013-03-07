<div class="locations form fit twelve columns nest">

<?php 
	echo $this->Form->create('HourGrouping');
?>
<div class="first nine columns">
	<h2>Add Hours</h2>
</div>
<div class="last three columns text-right">
	<?php echo $this->Form->submit('Save Hours',array('name'=>'submit','div'=>false,'class'=>'medium blue button')); ?>
	<span style="width:10px;">&nbsp;</span>
	<?php echo $this->Form->submit('Cancel',array('name'=>'cancel','div'=>false,'class'=>'medium button')); ?>
</div>
<div style="clear:both;"></div>	
 	<?php
 	
 		echo $this->Form->input('modified_by',array('type'=>'hidden','value'=>$_SERVER['REMOTE_USER']));
 		
 		$date_range_options = array();
		foreach($hourDateRanges as $dateRange) {
			$date_range_options[$dateRange['HourDateRange']['id']] = date("m/d/Y",strtotime($dateRange['HourDateRange']['begin_date']))." - ".date("m/d/Y",strtotime($dateRange['HourDateRange']['end_date'])).", ".$dateRange['HourDateRange']['description'].", ".$dateRange['HourCategory']['category']; 
		}
	        echo '<div class="first five columns">';
			echo $this->Form->input('HourGrouping.hour_location_id',array('label'=>'Location','empty'=>true,'div'=>false));
			echo '</div>';
			echo '<div class="two columns">';
			echo $this->Form->input('HourGrouping.hour_type_id',array('label'=>'Type','div'=>false));
	        echo '</div><div style="clear:both;"></div>';
			echo '<div class="first twelve columns nest row">';
			echo '<div class="first five columns">';
			echo $this->Form->input('HourGrouping.hour_date_range_id',array('empty'=>true,'options'=>$date_range_options));
			echo '</div>';
			echo '<div id="categoryDisplay" class="five columns" style="display:none;"><label for="HourGroupingHourCategoryId">Category</label><span class="hours-category"></span><span class="hours-category-name"></span></div>'; // filled in by AJAX
			echo '</div>'; // end first twelve columns nest row                               
			echo $this->Form->input('HourGrouping.hour_category_id',array('type'=>'hidden','value'=>1,'div'=>false));
	?>
	<div style="height:10px;">&nbsp;</div>
	<div id="hourDays">
	<?php						
		foreach($days as $key => $day) {
	?>	
		<div id="<?php echo $day ?>">
		<div class="first two columns">
			<?php 
				echo $this->Form->input('HourDay.'.$key.'.id',array('type'=>'hidden'));
				echo $this->Form->input('HourDay.'.$key.'.modified_by',array('type'=>'hidden','value'=>$_SERVER['REMOTE_USER']));
				echo '<span id="'.$day.'_text">'.$day.'</span>';
				echo $this->Form->input('HourDay.'.$key.'.day_of_week',array('type'=>'hidden','value'=>$day));
			?>
		</div>
		<div class="three columns">
			<?php
				if(isset($this->data['HourDay'][$key]['open_time']) && !empty($this->data['HourDay'][$key]['open_time'])) {
					$value = date("g:i a",strtotime($this->data['HourDay'][$key]['open_time']));
				} else {
					$value = '';
				}	
				echo $this->Form->input('HourDay.'.$key.'.open_time',array('type'=>'text','label'=>false, 'div'=>false, 'placeholder'=>'Enter open time, e.g. 10:00 am', 'value'=>$value));
			?>		
		</div>
		<div class="three columns">
			<?php  
				if(isset($this->data['HourDay'][$key]['close_time']) && !empty($this->data['HourDay'][$key]['close_time'])) {
					$value = date("g:i a",strtotime($this->data['HourDay'][$key]['close_time']));
				} else {
					$value = '';
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
				echo '<label for="HourDay'.$key.'IsTbd">';
				echo 	$this->Form->input('HourDay.'.$key.'.is_tbd',array('type'=>'checkbox','label'=>false, 'div'=>false, 'class'=>'is_tbd'));
				echo '	<span>Is TBD</span>';
				echo '</label>';
			 ?>
		</div>		
		<div style="clear:both;"></div>
		</div>
<?php	
	}	// end foreach day
?>
	</div>  <!-- end hourDays -->
<?php 
	
	echo $this->Form->end();
?>

</div>

<script>
	$('.is_tbd').live("click", function() {
        if($(this).attr('checked')) {
        	var substr_end = $(this).attr('id').indexOf('IsTbd');
            var idstart = $(this).attr('id').substr(0,substr_end);
            $("#"+idstart+"OpenTime").val("0:00");
            $("#"+idstart+"CloseTime").val("0:00");
            
        }
    });

	$('.is_closed').live("click", function() {
        if($(this).attr('checked')) {
        	var substr_end = $(this).attr('id').indexOf('IsClosed');
            var idstart = $(this).attr('id').substr(0,substr_end);
            $("#"+idstart+"OpenTime").val("0:00");
            $("#"+idstart+"CloseTime").val("0:00");
            
        }
    });
    
    $('#HourGroupingHourDateRangeId').change(function() {
    	//var categoryid = 1; // get from date range table
        //if($('#HourGroupingHourCategoryId').val().length) { categoryid = $('#HourGroupingHourCategoryId').val(); }
    	update_days($(this).val()); 
    });
    
/*
    $('#HourGroupingHourCategoryId').change(function() {
    	if($('#HourGroupingHourDateRangeId').val().length) {
    		update_days($('#HourGroupingHourDateRangeId').val(),$(this).val());
    	}
    });
*/
    
    function update_days(daterangeid) {  
	    //show all days in case some were previously hidden
        var days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        for(day in days) {
                $('#'+days[day]).show();
        }  
        //reset day of week text and values in case they were altered previously and remove days fields the exceed one week
        switch_days(days);
        $('#extraDays').remove();
    
        //lookup date range id and create list of days of week in the range
        //hide any days of week by div id that are not in range         
        var ajaxdata={
                //url:"<?php echo SCRIPT_URL; ?>/hours_date_range_lookup.php",
                url:"http://kemano.library.ubc.ca/~jdearles/ltk/staffdirectory/hours_date_range_lookup.php",
                dataType:'jsonp',
                data:{
                    daterangeid: daterangeid,
                    sortby: "weekday"
                },
                async: false,
                success:
                        function(data){
                                // loop over data.daysout and hide days in the list
                                for(var i in data.daysout) {
                                        $('#'+data.daysout[i]).hide();
                                }
                                for(var j in data.daysin) {                                                                                                            
                                    if(typeof data.datesin[j] != 'undefined') {
                                       	var day_plus_date = displayDate(data.datesin[j]);
                                    	$('#'+data.daysin[j]+'_text').text(day_plus_date);
                                    	switch(data.daysin[j]) {                                
                                                case 'Monday':
                                                        $('#HourDay0DayOfWeek').val(data.datesin[j]);
                                                break;
                                                case 'Tuesday':
                                                        $('#HourDay1DayOfWeek').val(data.datesin[j]);
                                                break;
                                                case 'Wednesday':
                                                        $('#HourDay2DayOfWeek').val(data.datesin[j]);
                                                break;
                                                case 'Thursday':
                                                        $('#HourDay3DayOfWeek').val(data.datesin[j]);
                                                break;
                                                case 'Friday':
                                                        $('#HourDay4DayOfWeek').val(data.datesin[j]);;
                                                break;
                                                case 'Saturday':
                                                        $('#HourDay5DayOfWeek').val(data.datesin[j]);
                                                break;
                                                case 'Sunday':
                                                        $('#HourDay6DayOfWeek').val(data.datesin[j]);
                                                break;
                                        } // end switch                              
                                     } //end if data.datesin.length       
                                } // end for var j in data.daysin
                                var html = ''
                                if(data.datesin.length > 6) {
	                                html += '<div id="extraDays">'; // so day fields after the first week can be removed
                                }
                                for(var k in data.datesin) {
                                    if(k > 6) {
                                        var day_plus_date = displayDate(data.datesin[k]);
                                        html += '<div id="'+data.datesin[k]+'">' +
                                                        '<div class="first two columns">' +
                                                                '<input type="hidden" id="HourDay'+k+'Id" name="data[HourDay]['+k+'][id]"/><input type="hidden" id="HourDay'+k+'ModifiedBy" value="hours" name="data[HourDay]['+k+'][modified_by]"/><span id="'+data.datesin[k]+'_text">'+day_plus_date+'</span><input type="hidden" id="HourDay'+k+'DayOfWeek" value="'+data.datesin[k]+'" name="data[HourDay]['+k+'][day_of_week]" />' +              
                                                        '</div>' +
                                                        '<div class="three columns">' +
                                                                '<input type="text" id="HourDay'+k+'OpenTime" value="" placeholder="Enter open time, e.g. 10:00 am" name="data[HourDay]['+k+'][open_time]" />' +                
                                                        '</div>' +
                                                        '<div class="three columns">' +
                                                                '<input type="text" id="HourDay'+k+'CloseTime" value="" placeholder="Enter close time, e.g. 4:00 pm" name="data[HourDay]['+k+'][close_time]" />' +              
                                                        '</div>' +                                                      
                                                        '<div class="two columns">' +
                                                                '<label for="HourDay'+k+'IsClosed">' +
                                                                        '<input type="hidden" value="0" id="HourDay'+k+'IsClosed_" name="data[HourDay]['+k+'][is_closed]"/>' +
                                                                        '<input type="checkbox" id="HourDay'+k+'IsClosed" value="1" class="is_closed" name="data[HourDay]['+k+'][is_closed]"/>' +
                                                                        '<span>Is Closed</span>' +
                                                                '</label>' +            
                                                        '</div>' +
                                                        '<div class="last two columns">' +
                                                                '<label for="HourDay'+k+'IsTbd">' +
                                                                        '<input type="hidden" value="0" id="HourDay'+k+'IsTbd_" name="data[HourDay]['+k+'][is_tbd]"/>' +
                                                                        '<input type="checkbox" id="HourDay'+k+'IsTbd" value="1" class="is_tbd" name="data[HourDay]['+k+'][is_tbd]" checked="checked"/>' +     
                                                                        '<span>Is TBD</span>' +
                                                                '</label>' +            
                                                        '</div>' +              
                                                        '<div style="clear: both;"/>' +
                                                        '</div>';
	                            } // end if(k > 6)                               
                    }  // end for(var k in data.datesin)
                    html += '</div>'; // closes extraDays div
                    // add divs for additional dates
                    $('#hourDays').append(html);
                    $('#HourGroupingHourCategoryId').val(data.categoryid);
                    var lowerCategory = data.category.toLowerCase();
                    if(lowerCategory == "summer alternate") { lowerCategory = "summer-alternate"; }
                    $('.hours-category').removeClass().addClass("hours-category").addClass(lowerCategory); // category name
                    $('span.hours-category-name').html(data.category);
                    $('#categoryDisplay').show();
                    // return
                    return true;
                }, // end success function
                type:'get'
        };
        jQuery.ajax(ajaxdata);
    }    
    
    function switch_days(days) {
    	//reset day of week text and values in case they were altered previously 
    	for(var j in days) {
        	$('#'+days[j]+'_text').text(days[j]);
        	switch(days[j]) {				
				case 'Monday':
					$('#HourDay0DayOfWeek').val(days[j]);
				break;
				case 'Tuesday':
					$('#HourDay1DayOfWeek').val(days[j]);
				break;
				case 'Wednesday':
					$('#HourDay2DayOfWeek').val(days[j]);
				break;
				case 'Thursday':
					$('#HourDay3DayOfWeek').val(days[j]);
				break;
				case 'Friday':
					$('#HourDay4DayOfWeek').val(days[j]);;
				break;
				case 'Saturday':
					$('#HourDay5DayOfWeek').val(days[j]);
				break;
				case 'Sunday':
					$('#HourDay6DayOfWeek').val(days[j]);
				break;
			}				
	    }    
    }
    
    function displayDate(date) {
    	var shortDays = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
    	var dateObj = new Date(date);
		var day_of_week = dateObj.getDay();		
		var dateParts = date.split("-");
		var formattedDate = dateParts[1] + "/" + dateParts[2] + "/" + dateParts[0];
		var display_date = shortDays[day_of_week] + ', ' + formattedDate;
		return display_date;
    }
    
    $('input[id*="CloseTime"], input[id*="OpenTime"]').live("keydown.autocomplete", function() {
    	$(this).autocomplete({
        source: function(request, response) {
            url = "http://kemano.library.ubc.ca/~jdearles/ltk/staffdirectory/hours_autocomplete.php?term="+request.term;
            $.getJSON(url + '&callback=?', function(data) {
                console.log(data);
                response(data);
            });
        }
		});
	});

    $(document).ready(function() {
    	$('.is_tbd').attr("checked","checked"); 
	/*
	$('.is_tbd').each(function() {
                if($(this).attr('checked')) {
                            var substr_end = $(this).attr('id').indexOf('IsTbd');
                            var idstart = $(this).attr('id').substr(0,substr_end);
                            $("#"+idstart+"OpenTime").val("0:00");
                            $("#"+idstart+"CloseTime").val("0:00");
                }
        });
	*/   
		    $('.is_tbd').live("click", function() {
		        if($(this).attr('checked')) {
		            var substr_end = $(this).attr('id').indexOf('IsTbd');
		            var idstart = $(this).attr('id').substr(0,substr_end);
		            $("#"+idstart+"OpenTime").val("0:00");
		            $("#"+idstart+"CloseTime").val("0:00");
		            
		        }
		    });
		
			$('.is_closed').live("click", function() {
		        if($(this).attr('checked')) {
		            var substr_end = $(this).attr('id').indexOf('IsClosed');
		            var idstart = $(this).attr('id').substr(0,substr_end);
		            $("#"+idstart+"OpenTime").val("0:00");
		            $("#"+idstart+"CloseTime").val("0:00");
			    $("#"+idstart+"IsTbd").removeAttr("checked");
		        }
		    });
            
            $('input[id*="CloseTime"], input[id*="OpenTime"]').autocomplete({
	            source: function(request, response) {
	                url = "http://kemano.library.ubc.ca/~jdearles/ltk/staffdirectory/hours_autocomplete.php?term="+request.term;
	                $.getJSON(url + '&callback=?', function(data) {
	                    console.log(data);
	                    response(data);  
	                });
	            }
        	});	
        	
        	$('input[id*="CloseTime"], input[id*="OpenTime"]').change(function() {
        		var nextTBD = $(this).nextElementInDom('.is_tbd');
	        	nextTBD.removeAttr("checked");  
        	});
        	
        	if($('#HourGroupingHourDateRangeId').val().length) {
        		var categoryid = 1;
        		if($('#HourGroupingHourCategoryId').val().length) { categoryid = $('#HourGroupingHourCategoryId').val(); }
        		update_days($('#HourGroupingHourDateRangeId').val(), categoryid);
        	}
    });
    
    // from http://www.blograndom.com/blog/2011/04/jquery-find-next-element-in-dom-by-selector/
    (function( $ ){
    $.fn.nextElementInDom = function(selector, options) {
        var defaults = { stopAt : 'body' };
        options = $.extend(defaults, options);

        var parent = $(this).parent();
        var found = parent.find(selector);

        switch(true){
            case (found.length > 0):
                return found;
            case (parent.length === 0 || parent.is(options.stopAt)):
                return $([]);
            default:
                return parent.nextElementInDom(selector);
        }
    };
    })( jQuery );
    
</script>
