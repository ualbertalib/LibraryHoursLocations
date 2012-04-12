<?php
class HourGrouping extends AppModel {
	var $name = 'HourGrouping';	
	
	var $validate = array(
		'hour_location_id' => array(
			'rule' => 'numeric',			
			'allowEmpty' => false,
			'required' => true			
		),
		'hour_type_id' => array(
			'rule' => 'numeric',			
			'allowEmpty' => false,
			'required' => true			
		),
		'hour_category_id' => array(	
			'rule' => 'numeric',		
			'allowEmpty' => false,
			'required' => true			
		),
		'hour_date_range_id' => array(	
			'rule' => 'numeric',		
			'allowEmpty' => false,
			'required' => true			
		)
	);

    var $hasMany = array(
		'HourDay' => array(
			'className' => 'HourDay',
			'foreignKey' => 'hour_grouping_id',
			'dependent' => true
		)		
	);

	var $belongsTo = array(
		'HourDateRange' => array(
			'className' => 'HourDateRange',
			'foreignKey' => 'hour_date_range_id'
		),
		'HourLocation' => array(
            'className' => 'HourLocation',
            'foreignKey' => 'hour_location_id',
            'fields' => 'name',
            'order' => 'display_position'
        ),
        'HourType' => array(
            'className' => 'HourType',
            'foreignKey' => 'hour_type_id',
            'fields' => 'type'
        ),
        'HourCategory' => array(
            'className' => 'HourCategory',
            'foreignKey' => 'hour_category_id',
            'fields' => 'category'
        )
	);
}
?>