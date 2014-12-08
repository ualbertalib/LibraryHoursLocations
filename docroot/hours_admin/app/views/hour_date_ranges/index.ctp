<div class="date-ranges index twelve columns nest">
	<div class="first eight columns"><h2><?php __('Date Ranges');?></h2></div>
  <div class="last four columns text-right">
        <?php
			// don't show add button for branch users
           	echo $this->Html->link(__('Add Date Range', true), array('action' => 'add'),array('class'=>'medium button'));
		?>
	</div>
	<div class="first seven columns nest">
			<?php 
				echo $form->create('HourDateRange',array('action'=>'search'));
				echo '<div class="searchform">';
				echo '<div class="first seven columns">';
				echo '	<label for="SearchKeywords">Filter by searching descriptions or dates</label>';
				echo '</div>';
				echo '<div class="first three columns">';
				echo $form->input('Search.keywords',array('label'=>false,'div'=>false));
				echo '</div>';
				echo '<div class="one column">';
				echo $form->submit('Filter',array('label'=>false,'div'=>false,'class'=>'medium button'));
				echo '</div>';
				echo '<div class="two columns">';
				echo $form->submit('Clear',array('label'=>false,'name'=>'ClearFilter','div'=>false,'class'=>'medium button'));
				echo '</div>';
				echo '</div>';
				echo $form->end();
			?>
	</div>
	<div class="last four columns message note">
		Yellow rows are date ranges that have passed.	
	</div>
    <div class="clear"></div>
    <table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('begin_date');?></th>
			<th><?php echo $this->Paginator->sort('end_date');?></th>
			<th><?php echo $this->Paginator->sort('Category','HourDateRange.hour_category_id');?></th>
			<th><?php echo $this->Paginator->sort('Description','HourDateRange.description');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($hour_date_ranges as $range):
		$class = null;
		$tdclass = null;
		$aclass = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
		if ($range['HourDateRange']['end_date'] < date("Y-m-d")) {
			$tdclass = ' style="background-color:#FFFFBB;"';
			//$aclass = "color: #999900";
		}

	?>
	<tr<?php echo $class;?>>
		<td<?php echo $tdclass;?>><?php echo $this->Html->link(date("F j, Y",strtotime($range['HourDateRange']['begin_date'])), array('action' => 'edit', $range['HourDateRange']['id']),array('style'=>"$aclass")); ?></td>
		<td<?php echo $tdclass;?>><?php echo $this->Html->link(date("F j, Y",strtotime($range['HourDateRange']['end_date'])), array('action' => 'edit', $range['HourDateRange']['id']),array('style'=>"$aclass")); ?></td>
		<td<?php echo $tdclass;?>><span class="hours-category <?php if ($range['HourCategory']['category'] == 'Summer Alternate') { echo 'summer-alternate'; } else { echo strtolower($range['HourCategory']['category']); } ?>"></span><?php echo $this->Html->link($range['HourCategory']['category'], array('action' => 'edit', $range['HourDateRange']['id']),array('style'=>"$aclass")); ?></td>
		<td<?php echo $tdclass;?>><?php echo $this->Html->link($range['HourDateRange']['description'], array('action' => 'edit', $range['HourDateRange']['id']),array('style'=>"$aclass")); ?></td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>
</div>
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
	//alert($(this).css("background-color"));
		if($(this).css("background-color")!=="rgb(255, 255, 187)" && $(this).css("background-color")!=="rgb(230, 230, 168)") {
			$(this).css("background-color","#F3F3F3");
			$(this).siblings().css("background-color","#F3F3F3");	
		} else {
			$(this).css("background-color","#E6E6A8");
			$(this).siblings().css("background-color","#E6E6A8");	
		}
	});
	$('td').mouseleave(function() {
		//alert($(this).css("background-color"));
		if($(this).css("background-color")!=="rgb(255, 255, 187)" && $(this).css("background-color")!=="rgb(230, 230, 168)") {
			$(this).css("background-color","#fff");
			$(this).siblings().css("background-color","#fff");
		} else {
			$(this).css("background-color","#FFFFBB");
			$(this).siblings().css("background-color","#FFFFBB");	
		}	
	});
	
</script>