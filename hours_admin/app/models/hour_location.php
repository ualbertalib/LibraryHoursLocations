<?php
class HourLocation extends AppModel {
	var $name = 'HourLocation';
	var $displayField = 'name';
	var $order = 'name';
	var $validate = array(
		'id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'allowEmpty' => false,
				'required' => true,
				'on' => 'update',
			),
		),
		'division_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please select a Division',
				'allowEmpty' => true,
				'required' => false,
			),
		),
		'location_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please select a Location',
				'allowEmpty' => true,
				'required' => false,
			),
		),
		/*'service_point_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please select a Service Point',
				'allowEmpty' => true,
				'required' => false,
			),
		),*/
        'display_position' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Please enter a display position to place this location properly in the list',
				'allowEmpty' => false,
				'required' => true,
				'on' => 'c'
			),
		),		
		'phone' => array(
			'phone' => array(
				'rule' => array('phone','/^(?:\+?1)?[-. ]?\\(?[2-9][0-8][0-9]\\)?[-. ]?[2-9][0-9]{2}[-. ]?[0-9]{4}(, )?((?:\+?1)?[-. ]?\\(?[2-9][0-8][0-9]\\)?[-. ]?[2-9][0-9]{2}[-. ]?[0-9]{4})?$/'),
				'message' => 'Please enter one or two valid phone numbers with area code (comma and space between if two)',
				'allowEmpty' => true,
				'required' => false,
			),
		)

	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'HourGrouping' => array(
			'className' => 'HourGrouping',
			'foreignKey' => 'hour_location_id',
			'dependent' => true
		)		
	);

	var $belongsTo = array(
		'Division' => array(
			'className' => 'Division',
			'foreignKey' => 'division_id',
			'conditions' => '',
			'fields' => array('Division.id', 'Division.division_name'),
			'order' => ''
		),
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'location_id',
			'conditions' => '',
			'fields' => array('Location.id', 'Location.location_name'),
			'order' => ''
		)/*,
		'ServicePoint' => array(
			'className' => 'ServicePoint',
			'foreignKey' => 'service_point_id',
			'conditions' => '',
			'fields' => array('ServicePoint.id', 'ServicePoint.name'),
			'order' => ''
		)*/
	);
	

}
?>