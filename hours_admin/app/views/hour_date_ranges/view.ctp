<div class="locations view twelve columns nest">
<div class="first eight columns">
	<h2><?php  __('Hour Date Range');?></h2>
</div>
<div class="last four columns text-right">
	<?php 
		echo $this->Html->link(__('Edit Date Range', true), array('action' => 'edit', $hourDateRange['HourDateRange']['id']),array('class'=>'medium button')); 
		if($_SERVER['REMOTE_USER'] == 'hours' || $_SERVER['REMOTE_USER'] == 'hours_admin') {	
	?>
		<span style="width:10px;">&nbsp;</span>
	<?php 
		echo $this->Html->link(__('Delete Date Range', true), array('action' => 'delete', $hourDateRange['HourDateRange']['id']), array('class'=>'medium button'), sprintf(__('Are you sure you want to delete this date range?', true)));
		} // end admin login	
	?>
</div>
<div style="clear:both"></div>
<div>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $hourDateRange['HourDateRange']['description']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Begin Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo date("F j, Y",strtotime($hourDateRange['HourDateRange']['begin_date'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('End Date'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo date("F j, Y",strtotime($hourDateRange['HourDateRange']['end_date'])); ?>
			&nbsp;
		</dd>
       <dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Print Note'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $hourDateRange['HourDateRange']['print_note']; ?>
			&nbsp;
		</dd>
	</dl>

</div>
</div>
