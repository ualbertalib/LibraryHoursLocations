<div class="locations view twelve columns nest">
<div class="first eight columns">
	<h2><?php echo $hourGrouping['HourLocation']['name'] . ' ' . $hourGrouping['HourCategory']['category'] . ' ' . $hourGrouping['HourType']['type'] . ' Hours'; ?></h2>
</div>
<div class="last four columns text-right">
	<?php 
		if($_SERVER['REMOTE_USER'] != 'hours' && $_SERVER['REMOTE_USER'] != 'hours_admin') {
			echo $this->Html->link(__('Edit Location',true),array('controller'=>'hour_locations','action'=>'edit',$hourGrouping['HourGrouping']['hour_location_id']),array('class'=>'medium button'));
		}
		echo '<span style="width:10px;">&nbsp;</span>';
		echo $this->Html->link(__('Edit Hours', true), array('action' => 'edit', $hourGrouping['HourGrouping']['id']),array('class'=>'medium button')); 
	?>
</div>
<div style="clear:both"></div>
<div>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<?php if($_SERVER['REMOTE_USER'] == 'hours' || $_SERVER['REMOTE_USER'] == 'hours_admin') { ?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Date Range'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo date("F d, Y",strtotime($hourGrouping['HourDateRange']['begin_date']))." - ".date("F d, Y",strtotime($hourGrouping['HourDateRange']['end_date'])).", ".$hourGrouping['HourDateRange']['description']; ?>
			&nbsp;
		</dd>
		<?php } // end if not branch login ?>
        <?php 
        foreach($hourGrouping['HourDay'] as $day) {
        ?>
            <dt<?php if ($i % 2 == 0) echo $class;?>>
		<?php 
			$days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
			if(array_search($day['day_of_week'],$days)!==false) {
				echo $day['day_of_week'];
			} else {
				echo date("n/j/Y",strtotime($day['day_of_week']));
			} 
		?>
	    </dt>
    		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
    			<?php
    			    if($day['is_closed']) {
                    	echo "Closed";
    			    } elseif($day['is_tbd']) {
    			        echo "TBD";
    			    } else {
    			        echo date('g:i a',strtotime($day['open_time']))." - ".date('g:i a',strtotime($day['close_time'])); 
    			    }            			    
                ?>
    			&nbsp;
    		</dd>
        <?php
        }
	    ?>
	</dl>
</div>
</div>
