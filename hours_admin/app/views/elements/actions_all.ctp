                <h3>Manage</h3>
               <?php // if($_SERVER['REMOTE_USER'] == 'hours' || $_SERVER['REMOTE_USER'] == 'hours_admin') { ?>
                <!--	<li><?php // echo $this->Html->link(__('Date Ranges', true), array('controller' => 'hour_date_ranges','action' => 'index'), array('class'=>'green')); ?></li> -->
               <?php // } ?>
                <li><?php echo $this->Html->link(__('Hours', true), array('controller' => 'hour_date_ranges','action' => 'index'), array('class'=>'green')); ?></li>
                <li><?php echo $this->Html->link(__('Locations', true), array('controller' => 'hour_locations','action' => 'index'), array('class'=>'green')); ?></li>
                <hr/>
                <h3>Hours Widget</h3>
                <li><?php echo $this->Html->link(__('Widget Code', true), array('controller' => 'hour_locations', 'action' => 'widget'), array('class'=>'green')) ?></li>
                <hr/>
                <h3>Print</h3>
				<li><?php echo $this->Html->link(__('Print Hours',true), array('controller'=>'hour_print', 'action'=>'index')); ?></li>
