  
<div class="col-md-12 locations index twelve columns nest">
    <div class="col-md-10 first ten columns"><h2><?php __('Locations');?></h2></div>
    <div class="col-md-2 last two columns text-right">
        <?php 
        	// only superadmin can add locations
        	if($_SERVER['REMOTE_USER'] == 'hours') {
        		echo $this->Html->link(__('Add Location', true), array('action' => 'add'),array('class'=>'btn btn-primary'));
        	}
        ?>
    </div>

	<table class='table' cellpadding="0" cellspacing="0">
	<tr>
			<th style="width: 28%;" class="sm" ><?php echo $this->Paginator->sort('Location','HourLocation.name');?></th>
			<?php if($_SERVER['REMOTE_USER'] == 'hours') { ?>
			<th style="width: 15%; text-align: left;"><?php echo $this->Paginator->sort('Display on Portal','HourLocation.display');?></th>
			<th><?php echo $this->Paginator->sort('URL','HourLocation.url');?></th>
			<?php } ?>
			<th><?php echo $this->Paginator->sort('Phone','HourLocation.phone');?></th>			
			<th><?php echo $this->Paginator->sort('Email','HourLocation.email');?></th>			
	</tr>	
	<?php
	$i = 0;
	foreach ($locations as $location):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}	
	?>
	<tr<?php echo $class;?>>
		<td>
		    <?php 
		        if(!empty($location['HourLocation']['parent_hour_location_id'])) { 
		            echo '<span style="text-indent:20px;">'.$this->Html->link($location['HourLocation']['name'], array('action' => 'edit', $location['HourLocation']['id'])).'</span>';
		        } else {
		            echo $this->Html->link($location['HourLocation']['name'], array('action' => 'edit', $location['HourLocation']['id'])); 
		        }    
            ?>
        </td>
        <?php if($_SERVER['REMOTE_USER'] == 'hours') { ?>
        <td>
        	<?php echo $this->Html->link($location['HourLocation']['display']? "Yes" : "No", array('action' => 'edit', $location['HourLocation']['id']));  ?>
        </td>
        <td><?php echo $this->Html->link($location['HourLocation']['url'], array('action' => 'edit', $location['HourLocation']['id']));  ?></td>
        <?php } ?>
        <td><?php echo $this->Html->link($location['HourLocation']['phone'], array('action' => 'edit', $location['HourLocation']['id']));  ?></td>
         <td><?php echo $this->Html->link($location['HourLocation']['email'], array('action' => 'edit', $location['HourLocation']['id']));  ?></td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
                <?php
                    $count = $this->params['paging'][$paginator->defaultModel()]['count']; //total count of records
                    $limit = $this->params['paging'][$paginator->defaultModel()]['options']['limit'];//records to be shown on a page
                    if($limit < $count) {
                        echo $this->Html->link(__('show all', true), array('action' => 'index', 'show:all'));
                    }
                ?>
	</div>
</div>

<script>
	$('td').mouseover(function() {
		$(this).css("background-color","#F3F3F3");
		$(this).siblings().css("background-color","#F3F3F3");	
	});
	$('td').mouseleave(function() {
		$(this).css("background-color","#fff");
		$(this).siblings().css("background-color","#fff");	
	});
	
</script>