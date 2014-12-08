<?php
class HourType extends AppModel {
	var $name = 'HourType';
    var $displayField = 'type';

	var $hasMany = array(
		'HourGrouping' => array(
			'className' => 'HourGrouping',
			'foreignKey' => 'hour_type_id',
			'dependent' => true
		)		
	);

}
?>