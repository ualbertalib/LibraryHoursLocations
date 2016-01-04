<div class="col-12-md locations form twelve columns">
<?php echo $this->Form->create('HourLocation');?>
<div class="col-md-6 first six columns">
	<h2><?php echo 'Edit Location: ' . $this->data['HourLocation']['name']; ?></h2>
       
</div>
<div class="col-md-5 last five columns text-right">
	<?php echo $this->Form->submit('Save Changes',array('name'=>'submit','div'=>false,'class'=>'btn btn-primary')); ?>
	<span style="width:10px;">&nbsp;</span>
	<?php echo $this->Form->submit('Cancel',array('name'=>'cancel','div'=>false,'class'=>'btn btn-primary')); ?>
	<?php // only super-admin can delete
		if($_SERVER['REMOTE_USER'] == 'hours') {
			echo '<span style="width:10px;">&nbsp;</span>';
			echo $this->Html->link(__('Delete Location', true), array('action' => 'delete', $this->data['HourLocation']['id']), array('class'=>'btn btn-danger'), sprintf(__('Are you sure you want to delete this location?', true))); 
		}
	?>
</div>
<div style="clear:both;"></div>
	<?php
	
	    echo $this->Form->input('id');
	    echo $this->Form->input('modified_by',array('type'=>'hidden','value'=>$_SERVER['REMOTE_USER']));
 		echo $this->Form->input('modified_timestamp',array('type'=>'hidden','value'=>date('Y-m-d H:i:s')));
	    // restrict to superadmin user only
		if($_SERVER['REMOTE_USER'] == 'hours') {
			echo '<div class="col-md-12 first twelve columns row">';
        	echo '<div class="bottom_margin">'.$this->Html->link('Hours Portal Notes (.doc)',array('action'=>'download','hours-portal-notes.doc')).'</div>';
        	echo '<div class="bottom_margin">Please select an Employee Directory Location and/or Division that corresponds to this Hours Location to enable cross-application functionality. Note: if an Hours Location Name is not entered, the default will be the Employee Directory Location name (if no Division provided) or the Employee Directory Division name (if selected).</div>';
        	echo '<div class="first four columns">';
        	echo $this->Form->input('location_id',array('label'=>'Employee Directory Location','empty'=>true, 'class'=>'form-control'));
        	echo '</div>';
        	echo '<div class="clear"></div>';
        	echo '<div class="first four columns">';
        	echo $this->Form->input('division_id',array('label'=>'Employee Directory Division','empty'=>true, 'class'=>'form-control'));
        	echo '</div>';
        	echo '<div class="clear"></div>';
        	echo '<div class="first four columns">';
        	echo $this->Form->input('name',array('label'=>'Hours Location Name', 'class'=>'form-control'));
        	echo $this->Form->input('login',array('class'=>'form-control'));
        	echo '</div>';
        	echo '<div class="clear"></div>';
        	echo '<div class="first four columns">';
        	echo '<label for="HourLocationDisplay">';
        	echo '  <span style="font-weight:bold;">Display on portal</span>';
        	echo    $this->Form->input('display',array('type'=>'checkbox','label'=>false, 'div'=>false, 'class'=>'form-control'));
        	echo '</label>';
        	echo '</div>';
        	echo '<div class="clear"></div>';
        	echo '<div class="first four columns">';
        	echo $this->Form->input('display_position',array('class'=>'form-control'));
        	echo '</div>';
        	echo '<div class="clear"></div>';
        	echo '<div class="first four columns">';
        	echo $this->Form->input('hour_location_id',array('label'=>'Parent Location','empty'=>true, 'class'=>'form-control'));
       		echo '</div>';
        	echo '<div class="clear"></div>';
		} else {
		// display the name
		}
		// open to all hours admin users
		echo $this->Form->input('description', array('type'=>'textarea','rows'=>'3','cols'=>'60', 'class'=>'form-control'));
		echo $this->Form->input('address', array('type'=>'textarea','rows'=>'3','cols'=>'60', 'class'=>'form-control'));

		echo '<div class="clear"></div><br><br>';
		echo $this->Form->input('phone', array('class'=>'form-control'));		
		
		echo $this->Form->input('email',array('label'=>'Email','empty'=>true, 'class'=>'form-control'));

		echo $this->Form->input('hours_notes',array('label'=>'Building Construction/Emergency Closure Notices','type'=>'textarea','rows'=>'2','cols'=>'60', 'class'=>'form-control'));
		echo $this->Form->input('widget_note',array('type'=>'textarea','rows'=>'2','cols'=>'60', 'class'=>'form-control'));
		// restrict to superadmin user only
		if($_SERVER['REMOTE_USER'] == 'hours') {
		echo $this->Form->input('url',array('label'=>'URL', 'class'=>'form-control'));
		echo $this->Form->input('accessibility_url',array('label'=>'Accessibility URL', 'class'=>'form-control'));
		echo $this->Form->input('map_code',array('type'=>'textarea','rows'=>'2','cols'=>'60', 'class'=>'form-control'));
		}
		//echo $this->Form->input('map_id');
	?>
<?php echo $this->Form->submit('Save Changes',array('name'=>'submit','div'=>false,'class'=>'btn btn-primary')); ?>
	<span style="width:10px;">&nbsp;</span>
<?php 
	echo $this->Form->submit('Cancel',array('name'=>'cancel','div'=>false,'class'=>'btn btn-primary')); 	
	echo $this->Form->end();
?>

</div>