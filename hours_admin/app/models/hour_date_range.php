<?php
class HourDateRange extends AppModel {
	var $name = 'HourDateRange';
	var $order = array('end_date'=>'desc', 'begin_date'=>'desc');

	var $hasMany = array(
		'HourGrouping' => array(
			'className' => 'HourGrouping',
			'foreignKey' => 'hour_date_range_id',
			'dependent' => true
		)		
	);
	
	var $belongsTo = array(
        'HourCategory' => array(
            'className' => 'HourCategory',
            'foreignKey' => 'hour_category_id',
            'fields' => 'category'
        )
	);
	
	// check that begin date is before end date	
	function dateOrder($check) {
        return strtotime($this->data['HourDateRange']['begin_date']) <= strtotime($this->data['HourDateRange']['end_date']); 
	}
	
	var $validate = array(
		'begin_date' => array(
			'dateRule' => array(
				'rule' => 'date',
				'allowEmpty' => false,
				'required' => true,
				'message' => 'Please enter or select a valid begin date'
			)	
		),
		'end_date' => array(
			'dateRule' => array(
				'rule' => 'date',
				'allowEmpty' => false,
				'required' => true,
				'message' => 'Please enter or select a valid end date'
			),
			'dateOrderRule' => array(
				'rule' => array('dateOrder'),
				'allowEmpty' => false,
				'required' => true,
				'message' => 'End date must follow begin date'		
			)			
		)
	);	
}
?>