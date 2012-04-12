<div class="locations view twelve columns nest">
<div class="first eight columns">
	<h2><?php echo $hourLocation['HourLocation']['name'] . ' Details'; ?></h2>
</div>
<div class="last four columns" style="text-align:right;">
	<?php 
		echo $this->Html->link(__('Edit Location', true), array('action' => 'edit', $hourLocation['HourLocation']['id']),array('class'=>'medium button')); 
	?>
</div>
<div style="clear:both"></div>

	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Location Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $hourLocation['HourLocation']['name']; ?>
			&nbsp;
		</dd>
		<?php if($_SERVER['REMOTE_USER'] == 'hours') { ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Login'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $hourLocation['HourLocation']['login']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Display on portal'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $hourLocation['HourLocation']['display'] ? 'Yes' : 'No'; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Display Position'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $hourLocation['HourLocation']['display_position']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Parent Location'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $hourLocation['HourLocation']['parent_location_name']; ?>
			&nbsp;
		</dd>
		<?php } ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Description'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php if($hourLocation['HourLocation']['name'] == 'Library') { echo '<blockquote>'.$hourLocation['HourLocation']['description'].'</blockquote>'; } else { echo $hourLocation['HourLocation']['description']; } ?>
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Address'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $hourLocation['HourLocation']['address']; ?>
			&nbsp;
		</dd>		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Phone'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $hourLocation['HourLocation']['phone']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Emergency Closure Notices'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $hourLocation['HourLocation']['hours_notes']; ?>
			&nbsp;
		</dd>	
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Widget Note'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $hourLocation['HourLocation']['widget_note']; ?>
			&nbsp;
		</dd>
		<?php if($_SERVER['REMOTE_USER'] == 'hours') { ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('URL'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo '<a href="'.$hourLocation['HourLocation']['url'].'">'.$hourLocation['HourLocation']['url'].'</a>'; ?>
			&nbsp;
        </dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Accessibility URL'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo '<a href="'.$hourLocation['HourLocation']['accessibility_url'].'">'.$hourLocation['HourLocation']['accessibility_url'].'</a>'; ?>
			&nbsp;
		</dd>	
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Map Code'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $hourLocation['HourLocation']['map_code']; ?>
			&nbsp;
		</dd>
		<?php } ?>
		
	</dl>

</div>