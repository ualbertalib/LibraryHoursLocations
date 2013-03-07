<div class="locations form twelve columns nest">
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
<div class="first seven columns">
	<h2>Add Date Range</h2>
</div>
<div class="last five columns text-right">
	<?php echo $this->Form->submit('Save Date Range',array('name'=>'submit','div'=>false,'class'=>'medium blue button')); ?>
	<span style="width:10px;">&nbsp;</span>
	<?php echo $this->Form->submit('Cancel',array('name'=>'cancel','div'=>false,'class'=>'medium button')); ?>
</div>
<div style="clear:both;"></div>

<?php	  
	echo $this->Form->input('modified_by',array('type'=>'hidden','value'=>$_SERVER['REMOTE_USER']));
	echo '<fieldset>';		
	echo '<div class="first two columns"><label for="HourDateRangeBeginDateMonth">From</label></div>';
	echo '<div class="nine columns"><label for="HourDateRangeEndDateMonth">To</label></div>';
	echo '<div class="first two columns">';
	echo $this->Form->input('HourDateRange.begin_date',array('label'=>false,'type'=>'text'));
	echo '</div>';	
	echo '<div class="two columns">';
	echo $this->Form->input('HourDateRange.end_date', array('label'=>false,'type'=>'text'));
	echo '</div><div class="clear"></div>';
	echo '</fieldset>';
	echo '<fieldset>';
	echo '<div class="first two columns">';
	echo $this->Form->input('HourDateRange.hour_category_id',array('label'=>'Category','empty'=>true,'div'=>false));
	echo '</div>';
	echo '</fieldset>';
	echo '<fieldset>';
	echo '<fieldset>';
    echo '<div class="first eight columns">';
    echo $this->Form->input('HourDateRange.description',array('label'=>'Description <span class="after">(for admin use only; not displayed on public facing pages)</span>'));
    echo '</div><div class="clear"></div>';
	echo '</fieldset>';
    echo '<div class="first eight columns">';
    echo $this->Form->input('HourDateRange.print_note',array('label'=>'Print Note <span class="after">(displayed on hours print pages)</span>'));
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