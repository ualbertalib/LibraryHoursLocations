<?php
class HourLocationsController extends AppController {

	var $name = 'HourLocations';

	function index() {
		$this->set('title_for_layout','Manage Locations');
		$this->__checkLogin();
        // filter by keywords
		if(isset($this->passedArgs['Search.keywords'])) {
			$keywords = $this->passedArgs['Search.keywords'];
			$this->paginate['conditions'][] = array(
				'OR' => array(
					'Login.login LIKE' => "%$keywords%",
					'Person.first_name LIKE' => "%$keywords%",
                                        'Person.last_name LIKE' => "%$keywords%",
                                        'System.system_name LIKE' => "%$keywords%"
				)
			);
			$this->data['Search']['keywords'] = $keywords;
			$title[] = __('Keywords',true).': '.$keywords;
		}
		if($_SERVER['REMOTE_USER'] != 'hours' && $_SERVER['REMOTE_USER'] != 'hours_admin') { 
            //get login and limit to location(s) that login can edit
            $editable_locations = $this->__checkLogin();            
            if(!empty($editable_locations)) {
                $this->paginate['conditions'][] = "HourLocation.id IN ($editable_locations)";
            }
        }
        if($_SERVER['REMOTE_USER'] == 'hours_admin') {
        	// don't include locations not displayed on portal for hours_admin
        	$this->paginate['conditions'][] = "HourLocation.display = 1";
        }
		$this->HourLocation->recursive = 1;
		$this->paginate['limit'] = 40;
		$this->paginate['order'] = array('HourLocation.display_position'=>'asc');
		$this->paginate['show']='all';
        $locations = $this->paginate();
        if(count($locations) == 1) {
        	$this->redirect(array('action'=>'view',$locations[0]['HourLocation']['id']));
        }           
		$this->set(compact('locations'));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash('No location ID passed to view.', 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		$this->set('title_for_layout','Edit Locations');
		$this->__checkLogin();
		$hourLocation = $this->HourLocation->read(null, $id);
		$hourLocation['HourLocation']['parent_location_name'] = '';
		if(!empty($hourLocation['HourLocation']['parent_hour_location_id'])) {
			$parentLocation = $this->HourLocation->read(null, $hourLocation['HourLocation']['parent_hour_location_id']);
			$hourLocation['HourLocation']['parent_location_name'] = $parentLocation['HourLocation']['name'];		
		}
		$this->set(compact('hourLocation'));
	}

	function add() {
		$this->set('title_for_layout','Add Location Details');
		$this->__checkLogin();
		// only super-admin can add hour locations
		if($_SERVER['REMOTE_USER']!='hours') {
			$this->Session->setFlash("You do not have permission to add hours locations.", 'flash_failure_short');
			$this->redirect(array('action'=>'index'));	
		}
	
		if (!empty($this->data)) {
			if (array_key_exists('cancel', $this->params['form'])) { 
				$this->redirect(array('action'=>'index'));
			}
		    // set name based on selected location, division, or service point if available
        	if(!empty($this->data['HourLocation']['location_id'])) {
                $location = $this->HourLocation->Location->read(null,$this->data['HourLocation']['location_id']);
        	    if(empty($this->data['HourLocation']['name'])) {
        	    	$this->data['HourLocation']['name'] = $location['Location']['location_name'];
        	    }	
        	} elseif (!empty($this->data['HourLocation']['division_id'])) {
        	   $division = $this->HourLocation->Division->read(null,$this->data['HourLocation']['division_id']);
               if(empty($this->data['HourLocation']['name'])) {
        	   		$this->data['HourLocation']['name'] = $division['Division']['division_name'];  
        	   }		
        	}
        	// set parent location id, if any
            if(!empty($this->data['HourLocation']['hour_location_id'])) {
                $this->data['HourLocation']['parent_hour_location_id'] = $this->data['HourLocation']['hour_location_id'];
            }
        	// format phone and fax numbers for consistency
            if(!empty($this->data['HourLocation']['phone'])) {
                $this->data['HourLocation']['phone'] = $this->__formatPhone($this->data['HourLocation']['phone']);
            }  
			if ($this->HourLocation->save($this->data)) {
				$this->Session->setFlash("Hours location added.", 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The hours location could not be added.', 'flash_failure');
			}
		}
        
		$locations = $this->HourLocation->Location->find('list');
		$divisions = $this->HourLocation->Division->find('list');
		$hourLocations = $this->HourLocation->find('list',array('order'=>array('HourLocation.display_position'=>'asc')));
		//$servicePoints = $this->HourLocation->ServicePoint->find('list');
		$this->set(compact('locations', 'divisions', 'hourLocations'));
	}

	function edit($id = null) {
		$this->set('title_for_layout','Edit Location Details');
		$this->__checkLogin();
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('No hours location ID passed to edit.', 'flash_failure_short');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {    
			if (array_key_exists('cancel', $this->params['form'])) { 
				$this->redirect(array('action'=>'view',$this->HourLocation->id));
			}        
            // set name based on selected location, division, or service point if available
        	if(!empty($this->data['HourLocation']['location_id'])) {
                $location = $this->HourLocation->Location->read(null,$this->data['HourLocation']['location_id']);
        	    if(empty($this->data['HourLocation']['name'])) {
        	    	$this->data['HourLocation']['name'] = $location['Location']['location_name'];
        	    }
        	} elseif (!empty($this->data['HourLocation']['division_id'])) {
        	   $division = $this->HourLocation->Division->read(null,$this->data['HourLocation']['division_id']);
        	   if(empty($this->data['HourLocation']['name'])) {
        	   		$this->data['HourLocation']['name'] = $division['Division']['division_name'];  
        	   }  
        	}
        	// set parent location id, if any
            if(!empty($this->data['HourLocation']['hour_location_id'])) {
                $this->data['HourLocation']['parent_hour_location_id'] = $this->data['HourLocation']['hour_location_id'];
            } 
        	// format phone and fax numbers for consistency
            if(!empty($this->data['HourLocation']['phone'])) {
                $this->data['HourLocation']['phone'] = $this->__formatPhone($this->data['HourLocation']['phone']);
            }
            if ($this->HourLocation->save($this->data)) {                                
				$this->Session->setFlash("Hours location updated.", 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash("Hours location could not be updated.", 'flash_failure');
			}
		}
		if (empty($this->data)) {
            $this->data = $this->HourLocation->read(null, $id);
            if(!empty($this->data['HourLocation']['parent_hour_location_id'])) {
                $this->data['HourLocation']['hour_location_id'] = $this->data['HourLocation']['parent_hour_location_id'];
            }
        }    
		$locations = $this->HourLocation->Location->find('list');
		$divisions = $this->HourLocation->Division->find('list');
		$hourLocations = $this->HourLocation->find('list',array('order'=>array('HourLocation.display_position'=>'asc')));
		//$servicePoints = $this->HourLocation->ServicePoint->find('list');
		$this->set(compact('locations', 'divisions', 'hourLocations'));
	}

	function delete($id = null) {
		// only super-admin can delete hour locations
		if($_SERVER['REMOTE_USER']!='hours') {
			$this->Session->setFlash("You do not have permission to delete hours locations.", 'flash_failure_short');
			$this->redirect(array('action'=>'index'));	
		}
	
		if (!$id) {
			$this->Session->setFlash('No hours location ID passed to delete.', 'flash_failure_short');
			$this->redirect(array('action'=>'index'));
		}
                $record = $this->HourLocation->read(null, $id);
                $location = $record['HourLocation']['name'];
		if ($this->HourLocation->delete($id)) {                        
			$this->Session->setFlash("Hours location deleted.", 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash("Hours location not deleted.", 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	
	function widget() {
		if (isset($this->data['HourLocation']['widget_note'])) {       
			if ($this->HourLocation->save($this->data)) {                                
				$this->Session->setFlash("Hours widget note updated.", 'flash_success');
			} else {
				$this->Session->setFlash("Hours widget note could not be updated.", 'flash_failure');
			}
		}
        $location_id = 0;
		if($_SERVER['REMOTE_USER']!='hours' && $_SERVER['REMOTE_USER']!='hours_admin') {
			$location_id = $this->__checkLogin();
		} else {
			if(!empty($this->data['HourLocation']['id'])) {
				$location_id = $this->data['HourLocation']['id'];
			}
		}
    	$hourLocation = $this->HourLocation->read(null,$location_id);
    	// check if this location has reference hours
    	$refresults = $this->HourLocation->HourGrouping->find("first",array('conditions'=>array('HourGrouping.hour_location_id'=>$location_id,'HourGrouping.hour_type_id'=>3),'recursive'=>-1));

    	$hasReferenceHours = 0;
    	if(!empty($refresults)) {
    		$hasReferenceHours = 1;
    	}
        $hourLocations = $this->HourLocation->find('list',array('order'=>array('HourLocation.display_position'=>'asc')));
        $this->data = $hourLocation;
    	$this->set(compact('hourLocation','hourLocations','hasReferenceHours'));
    	$this->set('title_for_layout','Hours Widget');
    	$this->__checkLogin();
    }	
    
    function download ($id) {
    	// for hours-portal-notes.doc in Add and Edit page instructions
	    $this->view = 'Media';
	    $idparts = explode(".", $id);
	    $params = array(
	          'id' => $id,
	          'name' => $idparts[0],
	          'extension' => $idparts[1],
	          'path' => APP . 'webroot' . DS,
	          'cache' => true  
		);
	    $this->set($params);
	}

	
	function __formatPhone($number) {
        $number = preg_replace('/[^\d]/', '', $number); //Remove anything that is not a number
        if(strlen($number) < 10) {
            return false;
        }
        if(strlen($number) == 10) {
        	return '('. substr($number, 0, 3) . ') ' . substr($number, 3, 3) . '-' . substr($number, 6);
        }
        if(strlen($number) == 20) {
        	return '('. substr($number, 0, 3) . ') ' . substr($number, 3, 3) . '-' . substr($number, 6, 4) . ', ' . '('. substr($number, 10, 3) . ') ' . substr($number, 13, 3) . '-' . substr($number, 16, 4);
        }	
     }
	function __checkLogin() {
		$login = "'".$_SERVER['REMOTE_USER']."'";
		$locations = '';
		$sql = "SELECT id, name FROM hour_locations WHERE login = $login";
		$result = $this->HourLocation->query($sql);	
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
			$result2 = $this->HourLocation->query($sql2);
			if(empty($result2)) {
				$this->set('reference_hours',0);
			}
		}
		return $locations;
	}
}
?>