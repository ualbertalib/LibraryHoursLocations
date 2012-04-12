<?php
class Location extends AppModel {
	var $name = 'Location';
	var $displayField = 'location_name';
	var $order = 'location_name';
	
	var $hasMany = array(
		'HourLocation' => array(
			'className' => 'HourLocation',
			'foreignKey' => 'location_id'
		)
	);
	

}
?>