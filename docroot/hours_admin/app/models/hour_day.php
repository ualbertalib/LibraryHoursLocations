<?php
class HourDay extends AppModel {
	var $name = 'HourDay';
	var $displayField = 'day_of_week';
	

	var $belongsTo = array(
		'HourGrouping' => array(
			'className' => 'HourGrouping',
			'foreignKey' => 'hour_grouping_id'
		)
	);
}
?>