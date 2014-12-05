<?php
class HourGroupingsController extends AppController {

	var $name = 'HourGroupings';
	var $days = array(0=>'Monday',1=>'Tuesday',2=>'Wednesday',3=>'Thursday',4=>'Friday',5=>'Saturday',6=>'Sunday');
	
	function index() {
		$this->set('title_for_layout','Dashboard');
		// filter by keywords
		if(isset($this->passedArgs['Search.keywords'])) {
			$keywords = $this->passedArgs['Search.keywords'];
			$this->paginate['conditions'][] = array(
				'OR' => array(
					'HourLocation.name LIKE' => "%$keywords%",
                    'HourDateRange.begin_date LIKE' => "%$keywords%",
                    'HourDateRange.end_date LIKE' => "%$keywords%",
					'HourType.type LIKE' => "%$keywords%",
                    'HourCategory.category LIKE' => "%$keywords%"
				)
			);
			$this->data['Search']['keywords'] = $keywords;
		}
		
		// set editable locations and conditions based on login
		$editable_locations = $this->__checkLogin();
		if(!empty($editable_locations)) {
			$this->paginate['conditions'][] = "HourGrouping.hour_location_id IN ($editable_locations) AND HourGrouping.hour_type_id = 3";
		} else {
			// set default type to "Library"
			$this->Session->write('HourGroupings.hour_type_id',2);
		}
		
		// get filter variables passed from form - these take precedence
		if(isset($this->passedArgs['Filter.hour_location_id'])) {
			$hour_location_id = $this->passedArgs['Filter.hour_location_id'];
			if($hour_location_id != 'any') { $this->paginate['conditions']['HourGrouping.hour_location_id'] = $hour_location_id; }
			$this->Session->write('HourGroupings.hour_location_id',$hour_location_id);		 
		}
		if(isset($this->passedArgs['Filter.hour_type_id'])) {
			$hour_type_id = $this->passedArgs['Filter.hour_type_id'];
			if($hour_type_id != 'any') {$this->paginate['conditions']['HourGrouping.hour_type_id'] = $hour_type_id; }
			$this->Session->write('HourGroupings.hour_type_id',$hour_type_id);
		}
		if(isset($this->passedArgs['Filter.hour_category_id'])) {
			$hour_category_id = $this->passedArgs['Filter.hour_category_id'];
			if($hour_category_id != 'any') {$this->paginate['conditions']['HourGrouping.hour_category_id'] = $hour_category_id; }
			$this->Session->write('HourGroupings.hour_category_id',$hour_category_id);
		}
		if(isset($this->passedArgs['Filter.hour_date_range_id'])) {
			$hour_date_range_id = $this->passedArgs['Filter.hour_date_range_id'];
			if($hour_date_range_id != 'any') {$this->paginate['conditions']['HourGrouping.hour_date_range_id'] = $hour_date_range_id; }
			$this->Session->write('HourGroupings.hour_date_range_id',$hour_date_range_id);	
		}
		
		// read filter variables from session
		$session_location = $this->Session->read('HourGroupings.hour_location_id');
		if(!empty($session_location)) {
			if($session_location != 'any') { $this->paginate['conditions']['HourGrouping.hour_location_id'] = $session_location; }	 
		}
		$session_type = $this->Session->read('HourGrouping.hour_type_id');
		if(!empty($session_type)) {
			if($session_type != 'any') { $this->paginate['conditions']['HourGrouping.hour_type_id'] = $session_type; }
		}
		$session_category = $this->Session->read('HourGroupings.hour_category_id');
		if(!empty($session_category)) {
			if($session_category != 'any') { $this->paginate['conditions']['HourGrouping.hour_category_id'] = $session_category; }
		}
		$session_date_range = $this->Session->read('HourGroupings.hour_date_range_id');
		if(!empty($session_date_range)) {
			if($session_date_range != 'any') { $this->paginate['conditions']['HourGrouping.hour_date_range_id'] = $session_date_range; }	
		}
		$this->set(compact('session_location','session_type','session_category','session_date_range'));
		
		// common settings
		
		$this->paginate['fields'] = '`HourGrouping`.`id`,`HourDateRange`.`begin_date`, `HourDateRange`.`end_date`, `HourDateRange`.`description`, `HourLocation`.`name`, `HourType`.`type`, `HourCategory`.`category`';
		$this->paginate['limit'] = 40;
		$this->paginate['order'] = 'HourDateRange.end_date DESC, HourDateRange.begin_date DESC, HourLocation.name ASC';
        $hour_groupings = $this->paginate(); 
        if($_SERVER['REMOTE_USER'] != 'hours' && $_SERVER['REMOTE_USER'] != 'hours_admin') {
        	// for branches, go to detail view, or location if no reference hours
        	if(!empty($hour_groupings[0]['HourGrouping']['id'])) {	
        		$this->redirect(array('action'=>'view',$hour_groupings[0]['HourGrouping']['id']));
        	} else {
        		$this->redirect(array('controller'=>'hour_locations','action'=>'widget',$editable_locations));
        	}	
        }                              
		$locations = $this->HourGrouping->HourLocation->find('list');
		$types = $this->HourGrouping->HourType->find('list');
		$categories = $this->HourGrouping->HourCategory->find('list');
		$dateRanges = $this->HourGrouping->HourDateRange->find('all');
		// build date ranges list
		$dates = array();
		foreach($dateRanges as $result) {
			$key = $result['HourDateRange']['id'];
			$dates[$key] = date("M d, Y",strtotime($result['HourDateRange']['begin_date'])) . ' - ' . date("M d, Y",strtotime($result['HourDateRange']['end_date']))." (".$result['HourDateRange']['description'].")";	
		}
	
		$this->set(compact('hour_groupings','locations','types','categories','editable_locations','dates'));		
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash('No hour grouping ID passed to view.', 'flash_failure_short');
			//$this->redirect(array('action' => 'index'));
		}
		
		$hourGrouping = $this->HourGrouping->read(null, $id);

		// determine if date range is more than one week
		// subtract datetimes in seconds, divide by 86400 to convert to days, add one to be inclusive
        $days_in_range = (strtotime($hourGrouping['HourDateRange']['end_date']) - strtotime($hourGrouping['HourDateRange']['begin_date']))/86400 + 1; 	        
        // determine days of week in range
    	$days = array();
    	$first = new DateTime($hourGrouping['HourDateRange']['begin_date']);
    	$last = new DateTime($hourGrouping['HourDateRange']['end_date']);
    	while($first <= $last) {
    		$day = date('l', $first->format('U'));
    		$days[] = $day;
    		$first->modify('+1 day');
    	}
    	// sort by date or day of week depending on category
		if(array_key_exists('0',$hourGrouping['HourDay'])) {
			$hourGrouping['HourDay'] = $this->__sortHourDays($hourGrouping['HourDay'],$days);
		}	
		$this->set(compact('hourGrouping'));
		// for header
		$this->__checkLogin();
		if($_SERVER['REMOTE_USER']!='hours' && $_SERVER['REMOTE_USER']!='hours_admin') {
			$this->set('title_for_layout','Dashboard');
		} else {
			$this->set('title_for_layout','View Hours');
		}	
		
	}

	function add($id = null) {
		// for header
		$this->__checkLogin();
		$this->set('title_for_layout','Add Hours');
		// only admin and super-admin can add hour date ranges
		if($_SERVER['REMOTE_USER']!='hours' && $_SERVER['REMOTE_USER']!='hours_admin') {
			$this->Session->setFlash("You do not have permission to add hours.", 'flash_failure_short');
			$this->redirect(array('action'=>'index'));	
		}
	
		if (!empty($this->data)) {	
			if (array_key_exists('cancel', $this->params['form'])) { 
				$this->redirect(array('action'=>'index'));
			}	
			// convert times to 24 hour format and set TBD times to midnight
			foreach($this->data['HourDay'] as $key=>$day) {
				if($this->data['HourDay'][$key]['is_tbd']==1) {
					$this->data['HourDay'][$key]['open_time'] = date("H:i:s",strtotime("0:00:00"));
                                        $this->data['HourDay'][$key]['close_time'] = date("H:i:s",strtotime("0:00:00"));
				} else {
					$this->data['HourDay'][$key]['open_time'] = date("H:i:s",strtotime($day['open_time']));
					$this->data['HourDay'][$key]['close_time'] = date("H:i:s",strtotime($day['close_time']));
				}
			}
			// convert days of week to dates when applicable, and remove days not in date range
			$this->data['HourDay'] = $this->__convertDays($this->data);			
			// save data
			if ($this->HourGrouping->saveAll($this->data)) {
				$this->Session->setFlash("Hours added.", 'flash_success');
				$this->redirect(array('action'=>'view',$this->HourGrouping->id));
			} else {
                            debug($this); die();
                           
				$this->Session->setFlash('The hours could not be added.', 'flash_failure');
			}
		}
		if(!empty($id)) {
	       $this->data = $this->HourGrouping->read(null,$id);
	       $this->data['HourGrouping']['id'] = null;
		}
        $days = $this->days;
		$hourTypes = $this->HourGrouping->HourType->find('list');
		$hourCategories = $this->HourGrouping->HourCategory->find('list');
		$hourLocations = $this->HourGrouping->HourLocation->find('list');
		$hourDateRanges = $this->HourGrouping->HourDateRange->find('all',array('order'=>array('HourDateRange.begin_date','HourDateRange.end_date')));
		$this->set(compact('hourLocations', 'hourCategories', 'hourTypes', 'hourDateRanges', 'days'));
	}

	function edit($id = null) {
		// for header
		$this->__checkLogin();
		$this->set('title_for_layout','Edit Hours');
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('No hour grouping id passed to edit', 'flash_failure_short');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if (array_key_exists('cancel', $this->params['form'])) { 
				$this->redirect(array('action'=>'view',$this->HourGrouping->id));
			}
			// convert times to 24 hour format
			foreach($this->data['HourDay'] as $key=>$day) {
				$this->data['HourDay'][$key]['open_time'] = date("H:i:s",strtotime($day['open_time']));
				$this->data['HourDay'][$key]['close_time'] = date("H:i:s",strtotime($day['close_time']));
			}
			// save data	
			if ($this->HourGrouping->saveAll($this->data)) {                               
				$this->Session->setFlash("Hours updated.", 'flash_success');
				$this->redirect(array('action'=>'view',$this->HourGrouping->id));
			} else {
				$this->Session->setFlash("Hours could not be updated.", 'flash_failure');
			}
		}
		$this->data = $this->HourGrouping->read(null,$id);	

		// determine if date range is more than one week
		// subtract datetimes in seconds, divide by 86400 to convert to days, add one to be inclusive
        $days_in_range = (strtotime($this->data['HourDateRange']['end_date']) - strtotime($this->data['HourDateRange']['begin_date']))/86400 + 1;         
        // determine days of week in range
    	$days_of_week = array();
    	$first = new DateTime($this->data['HourDateRange']['begin_date']);
    	$last = new DateTime($this->data['HourDateRange']['end_date']);
    	while($first <= $last) {
    		$day = date('l', $first->format('U'));
    		$days_of_week[] = $day;
    		$first->modify('+1 day');
    	}
    	if(array_key_exists('0',$this->data['HourDay'])) {
			$hourdays = $this->__sortHourDays($this->data['HourDay'],$days_of_week);		
		}
		//$hourdays = $this->data['HourDay'];
		// get sorted list of days
		$days = array();
		$weekdays = array();
		$shortdays = array(); // for display next to dates
		foreach($hourdays as $key=>$day) {
			$days[$key] = $day['day_of_week'];
			if(in_array($day['day_of_week'],$this->days)) {
				$weekdays[$key] = $day['day_of_week'];
				$shortdays[$key] = substr($day['day_of_week'], 1, 3);
			} else {
				$weekdays[$key] = date("l",strtotime($day['day_of_week'])); 
				$shortdays[$key] = date("D",strtotime($day['day_of_week']));
			}
		}
		/*
if($this->data['HourGrouping']['hour_category_id'] == 5 || $this->data['HourGrouping']['hour_category_id'] == 7) {
			sort($weekdays);
		}
*/
		$this->data['HourDay'] = $hourdays;	
		$origValues = $this->data;        
		$hourTypes = $this->HourGrouping->HourType->find('list');
		$hourCategories = $this->HourGrouping->HourCategory->find('list');
		$hourLocations = $this->HourGrouping->HourLocation->find('list');
		$hourDateRanges = $this->HourGrouping->HourDateRange->find('all');
		$this->set(compact('hourDateRanges','hourLocations', 'hourCategories', 'hourTypes', 'days', 'weekdays', 'shortdays', 'origValues'));	
    }

	function delete($id = null) {
		// only admin and super-admin can delete hour groupings
		if($_SERVER['REMOTE_USER']!='hours' && $_SERVER['REMOTE_USER']!='hours_admin') {
			$this->Session->setFlash("You do not have permission to delete hours.", 'flash_failure_short');
			$this->redirect(array('action'=>'index'));	
		}
	
		if (!$id) {
			$this->Session->setFlash('No hour grouping ID passed to delete.', 'flash_failure_short');
			$this->redirect(array('action'=>'index'));
		}     
		$hg = $this->HourGrouping->read(null,$id);
		if ($this->HourGrouping->delete($id)) {                       
			$this->Session->setFlash("Hours deleted.", 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash("Hours not deleted.", 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	
	function __checkLogin() {
		$login = "'".$_SERVER['REMOTE_USER']."'";
		$locations = '';
		$sql = "SELECT id, name FROM hour_locations WHERE login = $login";
		$result = $this->HourGrouping->query($sql);	
		$count = 1;	
		if(!empty($result)) {
			foreach($result as $row) {
				$locations .= $row['hour_locations']['id'];
				if($count < count($result)) {
					$locations .= ",";
				}
				$count++;
			}
		}
		if(isset($result[0]['hour_locations']['name'])) {
			$this->set('location_name',$result[0]['hour_locations']['name']);
		}
		// for deciding whether or not to display dashboard link based on whether or not the branch has reference hours
		$this->set('reference_hours',1);
		if(isset($result[0]['hour_locations']['id']) && !empty($result[0]['hour_locations']['id'])) {
			$location_id = $result[0]['hour_locations']['id'];
			$sql2 = "SELECT id FROM hour_groupings WHERE hour_location_id = $location_id AND hour_type_id = 3";
			$result2 = $this->HourGrouping->query($sql2);
			if(empty($result2)) {
				$this->set('reference_hours',0);
			}
		}
		return $locations;
	}
	
	function __sortHourDays($hourData,$days = null) {
		$hourdays = array();
		if(!isset($days) || empty($days)) {
			$days = $this->days;
		}		
		// adjust array to make sure times are associated with correct days
		foreach($hourData as $hourday) {
			if(in_array($hourday['day_of_week'],$days)) {
				$switchon = $hourday['day_of_week'];
				$sortby = "day";
				switch($switchon) {				
					case 'Monday':
						$hourdays[0] = $hourday;
					break;
					case 'Tuesday':
						$hourdays[1] = $hourday;
					break;
					case 'Wednesday':
						$hourdays[2] = $hourday;
					break;
					case 'Thursday':
						$hourdays[3] = $hourday;
					break;
					case 'Friday':
						$hourdays[4] = $hourday;
					break;
					case 'Saturday':
						$hourdays[5] = $hourday;
					break;
					case 'Sunday':
						$hourdays[6] = $hourday;
					break;
				}							
			} else {
				$sortby = "date";
			}
		}
		if($sortby == "day") {
			ksort($hourdays);
		} else {
			$hourdays = $hourData;
			foreach($hourData as $hourday) {
			    $dates[] = $hourday['day_of_week']; 
			}			
			array_multisort($dates, SORT_ASC, $hourdays);							
		}	
		return $hourdays;
	}
	
	function __convertDays($data) {
		// check days of week and only include days in range
			//first get begin and end dates of range, then count how many days, then loop over days and get day of week
			$range = $this->HourGrouping->HourDateRange->read(null,$data['HourGrouping']['hour_date_range_id']);
			//debug($range);
			$begin = new DateTime($range['HourDateRange']['begin_date']);
			$end = new DateTime($range['HourDateRange']['end_date']);
			$count = 1;
			$newHourDays = array();
			while($begin <= $end) {				
				foreach($data['HourDay'] as $key=>$hourDay) {
					$date = $begin->format('Y-m-d');
                    $day_of_week = $hourDay['day_of_week'];                                 		
             		// find out which day of the week this date falls and add to new array if it should be included
                    $this_day_of_week = date('l', $begin->format('U'));
                    if((($this_day_of_week == $hourDay['day_of_week'] && $count < 8) || $date == $hourDay['day_of_week'])) {  
                    	$newHourDays[$key] = $hourDay;	
                    }  
				}				
				$begin->modify("+1 day");
				$count++;
			}
			return $newHourDays;
	}
	
	/* search code from http://mrphp.com.au/code/search-forms-cakephp */
	function search() {
		$url['action'] = 'index';
		foreach ($this->data as $k=>$v){
			foreach ($v as $kk=>$vv){
				$url[$k.'.'.$kk]=$vv;
			}
		}
		$this->redirect($url, null, true);
	}
	
	function filter() {
     $url['action'] = 'index';
     	if(isset($this->params['form']['ClearFilter'])) {
				foreach ($this->data as $k=>$v){
					foreach ($v as $kk=>$vv){
						$url[$k.'.'.$kk]='';
					}
				}
				$this->Session->delete('HourGroupings');
			} else {
          foreach ($this->data as $k=>$v){
                  foreach ($v as $kk=>$vv){
                          $url[$k.'.'.$kk]=$vv;
                  }
          }
     	}       
      $this->redirect($url, null, true);
     }
	
	function datatransfer() {
		$this->layout = false;
		$this->autoRender = false;
		
		$date_ranges = $this->HourGrouping->HourDateRange->find('all');
		$hour_groupings = $this->HourGrouping->find('all');
		$modifier = "'".$_SERVER['REMOTE_USER']."'";
		
		/*foreach($hour_days as $day) {
			$dayid = $day['HourDay']['id'];
			$rangeid = $day['HourDay']['hour_date_range_id'];
			$locationid = $day['HourDay']['hour_location_id'];
			$typeid = $day['HourDay']['hour_type_id'];
			$categoryid = $day['HourDay']['hour_category_id'];
			$sql = "UPDATE hour_days
					SET hour_grouping_id = (SELECT id 
											FROM hour_groupings 
											WHERE hour_date_range_id = $rangeid
											AND hour_location_id = $locationid
											AND hour_type_id = $typeid
											AND hour_category_id = $categoryid),
						modified_by = $modifier,
						modified_timestamp = now()					
					WHERE id = $dayid
			";
			
			$this->HourGrouping->query($sql);
		}*/
		
		/*foreach($date_ranges as $range) {	
			$range_id = $range['HourDateRange']['id'];	
			$begin = "'".$range['HourDateRange']['begin_date']."'";
			$end = "'".$range['HourDateRange']['end_date']."'";
			
			
			$sql = "UPDATE hour_days
					SET 
						hour_location_id = (SELECT hour_location_id FROM hour_date_ranges WHERE id = $range_id),
						hour_type_id = (SELECT hour_type_id FROM hour_date_ranges WHERE id = $range_id),
						hour_category_id = (SELECT hour_category_id FROM hour_date_ranges WHERE id = $range_id)				
					WHERE hour_date_range_id = $range_id
					"; 
			$sql = "UPDATE hour_days_perm
					SET hour_date_range_id = (SELECT id FROM hour_date_ranges_new WHERE begin_date = $begin AND end_date = $end)
					WHERE hour_date_range_id = $range_id
			";	
			debug($sql);
			debug("<br/>");	
			$this->HourGrouping->query($sql);		
		}*/
	}
	
	/**
 *
 *
 * Handles automatic pagination of model records.
 * Overriding default pagination method to add handling of "show all"
         * solution from http://www.sanisoft.com/blog/2011/02/28/cakephp-how-to-show-all-records-options-while-still-using-paginate/
 *
 * @param mixed $object Model to paginate (e.g: model instance, or 'Model', or 'Model.InnerModel')
 * @param mixed $scope Conditions to use while paginating
 * @param array $whitelist List of allowed options for paging
 * @return array Model query results
 * @access public
 * @link http://book.cakephp.org/view/1232/Controller-Setup
 */
	function paginate($object = null, $scope = array(), $whitelist = array()) {
		if (is_array($object)) {
			$whitelist = $scope;
			$scope = $object;
			$object = null;
		}
		$assoc = null;

		if (is_string($object)) {
			$assoc = null;
			if (strpos($object, '.')  !== false) {
				list($object, $assoc) = pluginSplit($object);
			}

			if ($assoc && isset($this->{$object}->{$assoc})) {
				$object =& $this->{$object}->{$assoc};
			} elseif (
				$assoc && isset($this->{$this->modelClass}) &&
				isset($this->{$this->modelClass}->{$assoc}
			)) {
				$object =& $this->{$this->modelClass}->{$assoc};
			} elseif (isset($this->{$object})) {
				$object =& $this->{$object};
			} elseif (
				isset($this->{$this->modelClass}) && isset($this->{$this->modelClass}->{$object}
			)) {
				$object =& $this->{$this->modelClass}->{$object};
			}
		} elseif (empty($object) || $object === null) {
			if (isset($this->{$this->modelClass})) {
				$object =& $this->{$this->modelClass};
			} else {
				$className = null;
				$name = $this->uses[0];
				if (strpos($this->uses[0], '.') !== false) {
					list($name, $className) = explode('.', $this->uses[0]);
				}
				if ($className) {
					$object =& $this->{$className};
				} else {
					$object =& $this->{$name};
				}
			}
		}

		if (!is_object($object)) {
			trigger_error(sprintf(
				__('Controller::paginate() - can\'t find model %1$s in controller %2$sController',
					true
				), $object, $this->name
			), E_USER_WARNING);
			return array();
		}
		$options = array_merge($this->params, $this->params['url'], $this->passedArgs);

		if (isset($this->paginate[$object->alias])) {
			$defaults = $this->paginate[$object->alias];
		} else {
			$defaults = $this->paginate;
		}

		if (isset($options['show'])) {
			$options['limit'] = $options['show'];
		}

		if (isset($options['sort'])) {
			$direction = null;
			if (isset($options['direction'])) {
				$direction = strtolower($options['direction']);
			}
			if ($direction != 'asc' && $direction != 'desc') {
				$direction = 'asc';
			}
			$options['order'] = array($options['sort'] => $direction);
		}

		if (!empty($options['order']) && is_array($options['order'])) {
			$alias = $object->alias ;
			$key = $field = key($options['order']);

			if (strpos($key, '.') !== false) {
				list($alias, $field) = explode('.', $key);
			}
			$value = $options['order'][$key];
			unset($options['order'][$key]);

			if ($object->hasField($field)) {
				$options['order'][$alias . '.' . $field] = $value;
			} elseif ($object->hasField($field, true)) {
				$options['order'][$field] = $value;
			} elseif (isset($object->{$alias}) && $object->{$alias}->hasField($field)) {
				$options['order'][$alias . '.' . $field] = $value;
			}
		}
		$vars = array('fields', 'order', 'limit', 'page', 'recursive');
		$keys = array_keys($options);
		$count = count($keys);

		for ($i = 0; $i < $count; $i++) {
			if (!in_array($keys[$i], $vars, true)) {
				unset($options[$keys[$i]]);
			}
			if (empty($whitelist) && ($keys[$i] === 'fields' || $keys[$i] === 'recursive')) {
				unset($options[$keys[$i]]);
			} elseif (!empty($whitelist) && !in_array($keys[$i], $whitelist)) {
				unset($options[$keys[$i]]);
			}
		}
		$conditions = $fields = $order = $limit = $page = $recursive = null;

		if (!isset($defaults['conditions'])) {
			$defaults['conditions'] = array();
		}

		$type = 'all';

		if (isset($defaults[0])) {
			$type = $defaults[0];
			unset($defaults[0]);
		}

		$options = array_merge(array('page' => 1, 'limit' => 20), $defaults, $options);
		$options['limit'] = (int) $options['limit'];
		if (empty($options['limit']) || $options['limit'] < 1) {
			$options['limit'] = 1;
		}

		extract($options);

		if (is_array($scope) && !empty($scope)) {
			$conditions = array_merge($conditions, $scope);
		} elseif (is_string($scope)) {
			$conditions = array($conditions, $scope);
		}
		if ($recursive === null) {
			$recursive = $object->recursive;
		}

		$extra = array_diff_key($defaults, compact(
			'conditions', 'fields', 'order', 'limit', 'page', 'recursive'
		));
		if ($type !== 'all') {
			$extra['type'] = $type;
		}

		if (method_exists($object, 'paginateCount')) {
			$count = $object->paginateCount($conditions, $recursive, $extra);
		} else {
			$parameters = compact('conditions');
			if ($recursive != $object->recursive) {
				$parameters['recursive'] = $recursive;
			}
			$count = $object->find('count', array_merge($parameters, $extra));
		}

                /*** code added for show all ***/
                // Show all records
                if ((isset($extra['show']) && 'all' == $extra['show']) || (isset($this->params['named']['show']) && 'all' == $this->params['named']['show'])) {
                    // adding condition to avoid division by zero when defining $pageCount below
                    if($count) {
                        $options['limit'] = $defaults['limit'] = $limit = $count;
                    }
                }
                /*** end code added for show all ***/

		$pageCount = intval(ceil($count / $limit));

		if ($page === 'last' || $page >= $pageCount) {
			$options['page'] = $page = $pageCount;
		} elseif (intval($page) < 1) {
			$options['page'] = $page = 1;
		}
		$page = $options['page'] = (integer)$page;

		if (method_exists($object, 'paginate')) {
			$results = $object->paginate(
				$conditions, $fields, $order, $limit, $page, $recursive, $extra
			);
		} else {
			$parameters = compact('conditions', 'fields', 'order', 'limit', 'page');
			if ($recursive != $object->recursive) {
				$parameters['recursive'] = $recursive;
			}
			$results = $object->find($type, array_merge($parameters, $extra));
		}
		$paging = array(
			'page'		=> $page,
			'current'	=> count($results),
			'count'		=> $count,
			'prevPage'	=> ($page > 1),
			'nextPage'	=> ($count > ($page * $limit)),
			'pageCount'	=> $pageCount,
			'defaults'	=> array_merge(array('limit' => 20, 'step' => 1), $defaults),
			'options'	=> $options
		);
		$this->params['paging'][$object->alias] = $paging;

		if (!in_array('Paginator', $this->helpers) && !array_key_exists('Paginator', $this->helpers)) {
			$this->helpers[] = 'Paginator';
		}
		return $results;
	}

}
?>
