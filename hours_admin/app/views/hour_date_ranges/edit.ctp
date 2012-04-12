<div class="locations form twelve columns nest">
<?php 
	// set variables for date formatting
	if(isset($this->data['HourDateRange']['begin_date'])) {
		$begin_date = date("F d, Y",strtotime($this->data['HourDateRange']['begin_date']));
	} else {
		$begin_date = date("F d, Y",strtotime($origValues['HourDateRange']['begin_date']));
	}
	if(isset($this->data['HourDateRange']['end_date'])) {
		$end_date = date("F d, Y",strtotime($this->data['HourDateRange']['end_date']));
	} else {
		$end_date = date("F d, Y",strtotime($origValues['HourDateRange']['end_date']));
	}
	// create the form (open form tag)
	echo $this->Form->create('HourDateRange');
?>

<div class="first seven columns">
	<h2>Edit Date Range</h2>
</div>
<div class="last five columns text-right">
	<?php echo $this->Form->submit('Save Changes',array('name'=>'submit','div'=>false,'class'=>'medium blue button')); ?>
	<span style="width:10px;">&nbsp;</span>
	<?php echo $this->Form->submit('Cancel',array('name'=>'cancel','div'=>false,'class'=>'medium button')); ?>
	<?php // admin and super-admin can delete
		if($_SERVER['REMOTE_USER'] == 'hours' || $_SERVER['REMOTE_USER'] == 'hours_admin') {
			echo '<span style="width:10px;">&nbsp;</span>';
			echo $this->Html->link(__('Delete Date Range', true), array('action' => 'delete', $this->data['HourDateRange']['id']), array('class'=>'medium red button'), sprintf(__('Are you sure you want to delete this date range?', true))); 
		}
	?> 
</div>
<div style="clear:both;"></div>

<?php	  
    echo $this->Form->input('id');
    echo $this->Form->input('modified_by',array('type'=>'hidden','value'=>$_SERVER['REMOTE_USER']));
	echo $this->Form->input('modified_timestamp',array('type'=>'hidden','value'=>date('Y-m-d H:i:s')));
    echo '<fieldset>';
    echo '<div class="first eight columns">';
    echo $this->Form->input('HourDateRange.description',array('label'=>'Description <span class="after">(for admin use only; not displayed on public facing pages)</span>'));
    echo '</div><div class="clear"></div>';
	echo '</fieldset>';
	echo '<fieldset>';
	echo '<div class="first two columns"><label for="HourDateRangeBeginDateMonth">From</label></div>';
	echo '<div class="nine columns"><label for="HourDateRangeEndDateMonth">To</label></div>';
	echo '<div class="first two columns">';
	echo $this->Form->input('HourDateRange.begin_date',array('label'=>false,'type'=>'text','value'=>$begin_date));
	echo '</div>';	
	echo $this->Form->input('HourDateRange.orig_begin_date',array('type'=>'hidden','value'=>$origValues['HourDateRange']['begin_date']));
	echo '<div class="two columns">';
	echo $this->Form->input('HourDateRange.end_date', array('label'=>false,'type'=>'text','value'=>$end_date));
	echo $this->Form->input('HourDateRange.orig_end_date',array('type'=>'hidden','value'=>$origValues['HourDateRange']['end_date']));
	echo '</div><div class="clear"></div>';
	echo '</fieldset>';
			
?>
	
<?php echo $this->Form->end();?>

<script>
	$(document).ready(function() {
		$("#HourDateRangeBeginDate").datepicker({ dateFormat: "MM d, yy", gotoCurrent: true, showOtherMonths: true, selectOtherMonths: true });
		$("#HourDateRangeEndDate").datepicker({ dateFormat: "MM d, yy", gotoCurrent: true, showOtherMonths: true, selectOtherMonths: true });
	});	
</script>
</div>