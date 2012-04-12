<?php
class HourCategory extends AppModel {
	var $name = 'HourCategory';
	var $displayField = 'category';

	var $hasMany = array(
		'HourGrouping' => array(
			'className' => 'HourGrouping',
			'foreignKey' => 'hour_category_id',
			'dependent' => true
		)		
	);

}
?>