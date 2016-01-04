<div class="locations form col-md-12">
<?php 
	/*
if(!isset($this->data['HourDateRange']['begin_date'])) {
		$this->data['HourDateRange']['begin_date'] = '';	
	}
	if(!isset($this->data['HourDateRange']['end_date'])) {
		$this->data['HourDateRange']['end_date'] = '';	
	}		
*/
	echo $this->Form->create('HourDateRange');
?>
<div class="first col-md-7">
	<h2>Add Date Range</h2>
</div>
<div class="last five columns text-right">
	<?php echo $this->Form->submit('Save Date Range',array('name'=>'submit','div'=>false,'class'=>'btn btn-primary')); ?>
	<span style="width:10px;">&nbsp;</span>
	<?php echo $this->Form->submit('Cancel',array('name'=>'cancel','div'=>false,'class'=>'btn btn-primary')); ?>
</div>
<div style="clear:both;"></div> 

<?php	  
	echo $this->Form->input('modified_by',array('type'=>'hidden','value'=>$_SERVER['REMOTE_USER'], 'class'=>'form-control'));
	echo '<fieldset>';		
	echo '<div class="col-md-2"><label for="HourDateRangeBeginDateMonth">From</label></div>';
	echo '<div class="col-md-9 "><label for="HourDateRangeEndDateMonth">To</label></div>';
	echo '<div class="col-md-2" style="margin-left: 30px;">';
	echo $this->Form->input('HourDateRange.begin_date',array('label'=>false,'type'=>'text', 'class'=>'form-control'));
	echo '</div>';	
	echo '<div class="col-md-2" style="margin-left: 30px;">';
	echo $this->Form->input('HourDateRange.end_date', array('label'=>false,'type'=>'text', 'class'=>'form-control'));
	echo '</div><div class="clear"></div>';
	echo '</fieldset>';
	echo '<fieldset>';
	echo '<div class="">';
	echo $this->Form->input('HourDateRange.hour_category_id',array('label'=>'Category','empty'=>true,'div'=>false, 'class'=>'form-control'));
	echo '</div>';
	echo '</fieldset>';
	echo '<fieldset>';
	echo '<fieldset>';
    echo '<div class="" style="margin-left: 0px;">';
    echo $this->Form->input('HourDateRange.description',array('label'=>'Description <span class="after">(for admin use only; not displayed on public facing pages)</span>', 'class'=>'form-control'));
    echo '</div><div class="clear"></div>';
	echo '</fieldset>';
    echo '<div class="" style="margin-left: 0px;" >';
    echo $this->Form->input('HourDateRange.print_note',array('label'=>'Print Note <span class="after">(displayed on hours print pages)</span>', 'class'=>'form-control'));
    echo '</div><div class="clear"></div>';
	echo '</fieldset>';	
			
?>
	
<?php echo $this->Form->end();?>
</div>
<script>
	$(document).ready(function() {
		$("#HourDateRangeBeginDate").datepicker({ dateFormat: "MM d, yy", gotoCurrent: true, showOtherMonths: true, selectOtherMonths: true });
		$("#HourDateRangeEndDate").datepicker({ dateFormat: "MM d, yy", gotoCurrent: true, showOtherMonths: true, selectOtherMonths: true });
	});	
	$('[id^="HourDateRangeBeginDate"]').change(function() {
		$('#HourDateRangeEndDate').val($('#HourDateRangeBeginDate').val());
	});
	
	/*
$('[id^="HourDateRangeBeginDate"]').change(function() {
		var month = $('#HourDateRangeBeginDateMonth').val();
		var day = $('#HourDateRangeBeginDateDay').val();
		var year = $('#HourDateRangeBeginDateYear').val();
		$('#HourDateRangeEndDateMonth').val(month);
		$('#HourDateRangeEndDateDay').val(day);
		$('#HourDateRangeEndDateYear').val(year);
	});
*/
</script>