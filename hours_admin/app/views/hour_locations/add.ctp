<div class="locations form twelve columns">
<?php echo $this->Form->create('HourLocation');?>
<div class="first eight columns">
	<h2>Add Location</h2>
</div>
<div class="last three columns">
	<?php echo $this->Form->submit('Save Location',array('name'=>'submit','div'=>false,'class'=>'medium blue button')); ?>
	<span style="width:10px;">&nbsp;</span>
	<?php echo $this->Form->submit('Cancel',array('name'=>'cancel','div'=>false,'class'=>'medium button')); ?>
</div>
<div class="clear"></div>
	<?php
		echo $this->Form->input('modified_by',array('type'=>'hidden','value'=>$_SERVER['REMOTE_USER']));
		echo '<div class="first twelve columns row">';
		echo '<div class="bottom_margin">'.$this->Html->link('Hours Portal Notes (.doc)',array('action'=>'download','hours-portal-notes.doc')).'</div>';
    	echo '<div class="bottom_margin">Please select an Employee Directory Location and/or Division that corresponds to this Hours Location to enable cross-application functionality. Note: if an Hours Location Name is not entered, the default will be the Employee Directory Location name (if no Division provided) or the Employee Directory Division name (if selected).</div>';
		echo '<div class="first four columns">';
		echo $this->Form->input('location_id',array('empty'=>true));
		echo '</div>';
		echo '<div class="clear"></div>';
		echo '<div class="first four columns">';
		echo $this->Form->input('division_id',array('empty'=>true));	
		echo '</div>';
		echo '<div class="clear"></div>';
        echo '<div class="first four columns">';
		echo $this->Form->input('name');	
		echo $this->Form->input('login');
		echo '</div>';
		echo '<div class="clear"></div>';
        echo '<div class="first four columns">';
		echo '<label for="HourLocationDisplay">';
		echo '	<span style="font-weight:bold;">Display on portal</span>';
		echo 	$this->Form->input('display',array('type'=>'checkbox','label'=>false, 'div'=>false));
		echo '</label>';
		echo '</div>';
		echo '<div class="clear"></div>';
        echo '<div class="first four columns">';
		echo $this->Form->input('display_position');
		echo '</div>';
		echo '<div class="clear"></div>';
		echo '<div class="first four columns">';		
		echo $this->Form->input('hour_location_id',array('label'=>'Parent Location','empty'=>true));
		echo '</div>';
        echo '<div class="clear"></div>';
		echo $this->Form->input('description', array('type'=>'textarea','rows'=>'3','cols'=>'60'));
		echo $this->Form->input('address', array('type'=>'textarea','rows'=>'3','cols'=>'60'));
		echo $this->Form->input('phone');
		echo $this->Form->input('hours_notes',array('label'=>'Building Construction/Emergency Closure Notices','type'=>'textarea','rows'=>'2','cols'=>'60'));
		echo $this->Form->input('widget_note',array('type'=>'textarea','rows'=>'2','cols'=>'60'));
		echo $this->Form->input('url',array('label'=>'URL'));
		echo $this->Form->input('accessibility_url',array('label'=>'Accessibility URL'));
		echo $this->Form->input('map_code',array('type'=>'textarea','rows'=>'2','cols'=>'60'));
	?>
	</fieldset>
<?php
	echo $this->Form->submit('Save Location',array('name'=>'submit','div'=>false,'class'=>'medium blue button')); 
?>
	<span style="width:10px;">&nbsp;</span>
<?php 
	echo $this->Form->submit('Cancel',array('name'=>'cancel','div'=>false,'class'=>'medium button')); 
	 
	echo $this->Form->end();

?>
</div>