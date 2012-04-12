<?php
class Division extends AppModel {
	var $name = 'Division';
	var $displayField = 'division_name';
	var $order = 'division_name';
	
	var $hasMany = array(
		'HourLocation' => array(
			'className' => 'HourLocation',
			'foreignKey' => 'division_id'
		)
	);
	

}
?>