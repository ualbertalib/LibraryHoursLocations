<?php
class HourDateRangesController extends AppController {

	var $name = 'HourDateRanges';
	
	function index() {
		// filter by keywords
		if(isset($this->passedArgs['Search.keywords'])) {
			$keywords = $this->passedArgs['Search.keywords'];
			$keywordParts = explode(" ", $keywords);
			//if(array_search(, $keywordParts))
			foreach($keywordParts as $keyword) {
				$this->paginate['conditions'][] = array(
					'OR' => array(
						'HourDateRange.description LIKE' => "%$keyword%",
	          			'HourDateRange.begin_date LIKE' => "%$keyword%",
	          			'HourDateRange.end_date LIKE' => "%$keyword%",
	          			'DATE_FORMAT(HourDateRange.begin_date,"%M %e, %Y") LIKE' => "%$keyword%",
	          			'DATE_FORMAT(HourDateRange.end_date,"%M %e, %Y") LIKE' => "%$keyword%",	
					)
				);
			}	
			$this->data['Search']['keywords'] = $keywords;
		}
		$this->paginate['fields'] = array('DISTINCT begin_date','end_date', 'description', 'id');
		$this->paginate['limit'] = 40;
		$this->paginate['order'] = 'HourDateRange.end_date DESC, HourDateRange.begin_date DESC';
		$this->paginate['show'] = "all";
		$this->HourDateRange->recursive = -1;
    	$hour_date_ranges = $this->paginate();  
		$this->set(compact('hour_date_ranges'));
		$this->__checkLogin();
		
	}
	
	/* search code from http://mrphp.com.au/code/search-forms-cakephp */
	function search() {
		$url['action'] = 'index';
		if(isset($this->params['form']['ClearFilter'])) {
			foreach ($this->data as $k=>$v){
				foreach ($v as $kk=>$vv){
					$url[$k.'.'.$kk]='';
				}
			}
		} else {
			foreach ($this->data as $k=>$v){
				foreach ($v as $kk=>$vv){
					$url[$k.'.'.$kk]=$vv;
				}
			}
		}
		$this->redirect($url, null, true);
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash('No hour date range ID passed to view.', 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		$this->HourDateRange->recursive = -1;
		$hourDateRange = $this->HourDateRange->read(null, $id);
		$this->set(compact('hourDateRange'));
		$this->set('title_for_layout','Edit Date Ranges');
		$this->__checkLogin();
	}

	function add() {
		// only admin and super-admin can add hour date ranges
		if($_SERVER['REMOTE_USER']!='hours' && $_SERVER['REMOTE_USER']!='hours_admin') {
			$this->Session->setFlash("You do not have permission to add date ranges.", 'flash_failure_short');
			$this->redirect(array('action'=>'index'));	
		}
	
		if (!empty($this->data)) {
			if (array_key_exists('cancel', $this->params['form'])) { 
				$this->redirect(array('action'=>'index'));
			}
			// format dates so that database will accept
			$this->data['HourDateRange']['begin_date'] = date("Y-m-d",strtotime($this->data['HourDateRange']['begin_date']));
			$this->data['HourDateRange']['end_date'] = date("Y-m-d",strtotime($this->data['HourDateRange']['end_date']));
			if ($this->HourDateRange->save($this->data)) { 
				$this->Session->setFlash("Date range added.", 'flash_success');
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash('The date range could not be added.', 'flash_failure');
			}
		}
		$this->__checkLogin();
	}
	
	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('No hour date range id passed to edit', 'flash_failure_short');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if (array_key_exists('cancel', $this->params['form'])) { 
				$this->redirect(array('action'=>'index'));
			}	
			// format dates so that database will accept
			$this->data['HourDateRange']['begin_date'] = date("Y-m-d",strtotime($this->data['HourDateRange']['begin_date']));
			$this->data['HourDateRange']['end_date'] = date("Y-m-d",strtotime($this->data['HourDateRange']['end_date']));		
			if ($this->HourDateRange->save($this->data) && $this->__updateHours($this->data)) {
				$this->Session->setFlash("Hour date range was updated.  Please check all hours using this date range, since they may need to be updated.", 'flash_success');
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash("Hour date range could not be updated.", 'flash_failure');
			}
		} else {
			$this->data = $this->HourDateRange->find('first',array('conditions' => array('HourDateRange.id' => $id)));
		}
		$this->HourDateRange->recursive=-1;
		$origValues = $this->HourDateRange->find('first',array('conditions' => array('HourDateRange.id' => $id)));
		$this->set(compact('origValues'));
		$this->__checkLogin();
    }

    function __updateHours($dateRange) {
    	/***********
    	Get hour groupings that use this date range
    	For each hour grouping, get hour_days data
    	Add or delete days of week records to or from hour_days based on changed date range
    	Use open and close times from existing values
    	Use date instead of day of week when category is 5 or 7 (holiday or exception)
    	
    	***********/
    	$modified_by = "'".$_SERVER['REMOTE_USER']."'";
    	$modified_timestamp = date('Y-m-d H:i:s');
    	$hour_date_range_id = $dateRange['HourDateRange']['id'];
    	//set date variables
        $end_date_str = $dateRange['HourDateRange']['end_date'];
        $begin_date_str = $dateRange['HourDateRange']['begin_date'];
        $date = new DateTime($begin_date_str);
        $end_date = new DateTime($end_date_str);
        $orig_begin_date = $dateRange['HourDateRange']['orig_begin_date'];
        $orig_end_date = $dateRange['HourDateRange']['orig_end_date'];
        // determine if date range is more than one week
        $days_in_range = (strtotime($end_date_str) - strtotime($begin_date_str))/86400 + 1; // subtract datetimes in seconds, divide by 86400 to convert to days, add one to be inclusive
        // determine days of week in range
    	$days_of_week = array();
    	$first = new DateTime($begin_date_str);
    	$last = new DateTime($end_date_str);
    	while($first <= $last) {
    		$day = date('l', $first->format('U'));
    		$days_of_week[] = $day;
    		$first->modify('+1 day');
    	}
    	// get hour_groupings data
    	$hgcondition = array('HourGrouping.hour_date_range_id'=>$hour_date_range_id);
    	$hour_groupings = $this->HourDateRange->HourGrouping->find('all', array('conditions'=>$hgcondition));
    	
    	foreach($hour_groupings as $group) {
    		$hour_grouping_id = $group['HourGrouping']['id'];
    		// get hour_days data for this hour_grouping
    		$hdcondition = array('HourDay.hour_grouping_id'=>$hour_grouping_id);
    		$this->HourDateRange->HourGrouping->HourDay->recursive = -1;
    		$hour_days = $this->HourDateRange->HourGrouping->HourDay->find('all', array('conditions'=>$hdcondition));
			if(!count($hour_days)) {
				return true;
			} else {
	    		$days_in_db = array();
	    		foreach($hour_days as $hour_day) {
	    			$days_in_db[] = $hour_day['HourDay']['day_of_week'];
	    		}
	    		    			        
		        if($begin_date_str !== $orig_begin_date) {    
		            //compare dates
		            if($begin_date_str < $orig_begin_date) { 
		                //insert records between dates 
		                $start = new DateTime($begin_date_str);
		                $end = new DateTime($orig_begin_date);
		                $day_count = 1;
		                $hourDay = $hour_days[0]['HourDay'];
			            $open_time = "'".$hourDay['open_time']."'";
			            $close_time = "'".$hourDay['close_time']."'";
			            $is_closed = $hourDay['is_closed'];
			            $is_tbd = $hourDay['is_tbd'];                 
		                $day_count = 1;
		                while($start < $end) {
		                    $year_month_day = "'".$start->format('Y-m-d')."'";
	                        if($group['HourGrouping']['hour_category_id'] == 5 || $group['HourGrouping']['hour_category_id'] == 7) {
	            	            $day_of_week = $year_month_day;
	            	            $this_day_of_week = $start->format('Y-m-d');
	            	            $sql = "INSERT INTO hour_days
			                            SET 
			                            `hour_grouping_id` = $hour_grouping_id,
			                            `open_time` = $open_time,
			                            `close_time` = $close_time,
			                            `is_closed` = $is_closed,
			                            `is_tbd` = $is_tbd,
			                            `day_of_week` = $day_of_week,
			                            `modified_by` = $modified_by
			                            ";
			                    	$this->HourDateRange->query($sql);       
	
							} else {
								// find out which day of the week this date falls
	                        	$this_day_of_week = date('l', $start->format('U'));	
	                        	$day_of_week = "'".$this_day_of_week."'";	
		                    	if($day_count < 8) {
		                    		// check if this day of week occurs in the updated date range and if it's already in the hour_days table
		                    		if(in_array($this_day_of_week,$days_of_week) && array_search($this_day_of_week,$days_in_db)===false) {   
				                    	$sql = "INSERT INTO hour_days
				                            SET 
				                            `hour_grouping_id` = $hour_grouping_id,
				                            `open_time` = $open_time,
				                            `close_time` = $close_time,
				                            `is_closed` = $is_closed,
				                            `is_tbd` = $is_tbd,
				                            `day_of_week` = $day_of_week,
				                            `modified_by` = $modified_by
				                            ";
				                    	$this->HourDateRange->query($sql);       
			                    	}
			                    }
			                }    
		                   $start->modify('+1 day');  
		                   $day_count++;      
		                }       
		            }
		            
		            if($begin_date_str > $orig_begin_date) { 
		                //delete records between dates 
		                $start = new DateTime($orig_begin_date);
		                $end = new DateTime($begin_date_str);
		                while($start < $end) {
		                        if($group['HourGrouping']['hour_category_id'] == 5 || $group['HourGrouping']['hour_category_id'] == 7) {
		                        	$year_month_day = "'".$start->format('Y-m-d')."'";
		                        	$sql = "DELETE FROM hour_days
		                                WHERE
		                                `day_of_week` = $year_month_day
		                                AND
		                                `hour_grouping_id` = $hour_grouping_id";
		                            $this->HourDateRange->query($sql);   
		                        } else {
		                        	if($days_in_range < 8) {
		                        		// check if this day of week occurs in the updated date range and delete if not
		                        		if(array_search(date('l', $start->format('U')),$days_of_week)===false) {	    
		                        			$day_of_week = "'".date('l', $start->format('U'))."'";                    		
			                        		$sql = "DELETE FROM hour_days
				                                WHERE
				                                `day_of_week` = $day_of_week  
				                                AND
				                                `hour_grouping_id` = $hour_grouping_id";
			                                $this->HourDateRange->query($sql);
			                            }    
		                            }     		
		                        }  
		                   $start->modify('+1 day');      
		                }
		            }        
		        } // end if $begin_date_str !== $orig_begin_date
		        
		        		        
		        if($end_date_str !== $orig_end_date) {
		            //compare dates
		            if($end_date_str < $orig_end_date) { 
		                //delete records between dates 
		                $start = new DateTime($end_date_str);
		                $start->modify('+1 day');
		                $end = new DateTime($orig_end_date);
		                while($start <= $end) {
							if($group['HourGrouping']['hour_category_id'] == 5 || $group['HourGrouping']['hour_category_id'] == 7) {
								$year_month_day = "'".$start->format('Y-m-d')."'";
	                        	$sql = "DELETE FROM hour_days
	                                WHERE
	                                `day_of_week` = $year_month_day
	                                AND
	                                `hour_grouping_id` = $hour_grouping_id";
	                             $this->HourDateRange->query($sql); 
	                        } else {
	                        	if($days_in_range < 8) {
	                        		// check if this day of week occurs in the updated date range and delete if not
	                        		if(array_search(date('l', $start->format('U')),$days_of_week)===false) {	    
	                        			$day_of_week = "'".date('l', $start->format('U'))."'";                    		
		                        		$sql = "DELETE FROM hour_days
			                                WHERE
			                                `day_of_week` = $day_of_week  
			                                AND
			                                `hour_grouping_id` = $hour_grouping_id";
		                                $this->HourDateRange->query($sql);
		                            } 
	                            }     		
	                        }      
	                        $start->modify('+1 day');        
		                }
		            }
		            
		            if($end_date_str > $orig_end_date) { 
		                //insert records between dates 
		                // get hour days records again since some may have been inserted above, but don't overwrite since all could have been deleted above
		                $updated_hour_days = $this->HourDateRange->HourGrouping->HourDay->find('all', array('conditions'=>$hdcondition));
		                if(!count($hour_days) && !count($updated_hour_days)) {
							return true;
						} else {
							if(count($updated_hour_days)) {
								$hour_days = $updated_hour_days;
							}
				    		$days_in_db = array();
				    		foreach($hour_days as $hour_day) {
				    			$days_in_db[] = $hour_day['HourDay']['day_of_week'];
				    		}
				    		if($orig_end_date > $begin_date_str) {
			                	$start = new DateTime($orig_end_date);
			                	$start->modify('+1 day');
			                } else {
			                	$start = new DateTime($begin_date_str);
			                }
			                 
			                $end = new DateTime($end_date_str);	 
			                $hourDay = $hour_days[0]['HourDay'];
				            $open_time = "'".$hourDay['open_time']."'";
				            $close_time = "'".$hourDay['close_time']."'";
				            $is_closed = $hourDay['is_closed'];
				            $is_tbd = $hourDay['is_tbd'];                 
			                $day_count = 1;
			                while($start <= $end) {
			                    $year_month_day = "'".$start->format('Y-m-d')."'";
		                        if($group['HourGrouping']['hour_category_id'] == 5 || $group['HourGrouping']['hour_category_id'] == 7) {
		            	            $day_of_week = $year_month_day;
		            	            $this_day_of_week = $start->format('Y-m-d');
		            	            $sql = "INSERT INTO hour_days
				                            SET 
				                            `hour_grouping_id` = $hour_grouping_id,
				                            `open_time` = $open_time,
				                            `close_time` = $close_time,
				                            `is_closed` = $is_closed,
				                            `is_tbd` = $is_tbd,
				                            `day_of_week` = $day_of_week,
				                            `modified_by` = $modified_by
				                            ";
				                    	$this->HourDateRange->query($sql);       
		
								} else {
									// find out which day of the week this date falls
		                        	$this_day_of_week = date('l', $start->format('U'));	
		                        	$day_of_week = "'".$this_day_of_week."'";	
			                    	if($day_count < 8) {
			                    		// check if this day of week occurs in the updated date range and if it's already in the hour_days table
			                    		if(in_array($this_day_of_week,$days_of_week) && array_search($this_day_of_week,$days_in_db)===false) {   
					                    	$sql = "INSERT INTO hour_days
					                            SET 
					                            `hour_grouping_id` = $hour_grouping_id,
					                            `open_time` = $open_time,
					                            `close_time` = $close_time,
					                            `is_closed` = $is_closed,
					                            `is_tbd` = $is_tbd,
					                            `day_of_week` = $day_of_week,
					                            `modified_by` = $modified_by
					                            ";
					                    	$this->HourDateRange->query($sql);       
				                    	}
				                    }
				                }    
			                   $start->modify('+1 day');  
			                   $day_count++;    
			                } 
			            }  // end if !count($hour_days) && !count($updated_hour_days)    
		            } // end if  $end_date_str > $orig_end_date     
		        } // end if $end_date_str !== $orig_end_date	
		    } // end if !count($hour_days)    	      
	    }  // end foreach hour_grouping 
	    return true;   
    } // end __updateHours function

	function delete($id = null) {
		// only admin and super-admin can delete hour date ranges
		if($_SERVER['REMOTE_USER']!='hours' && $_SERVER['REMOTE_USER']!='hours_admin') {
			$this->Session->setFlash("You do not have permission to delete hour date ranges.", 'flash_failure_short');
			$this->redirect(array('action'=>'index'));	
		}
	
		if (!$id) {
			$this->Session->setFlash('No hours ID passed to delete.', 'flash_failure_short');
			$this->redirect(array('action'=>'index'));
		}     

		if ($this->HourDateRange->delete($id)) {   			$this->Session->setFlash("Hours deleted.", 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash("Hours not deleted.", 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	
	    
    function __deleteDailyHours($location,$type,$category,$begin_date,$end_date) {
        $sql = "DELETE FROM `hours` WHERE hour_location_id = $location AND hour_type_id = $type AND hour_category_id = $category AND `year_month_day` BETWEEN $begin_date AND $end_date";
        $this->HourDateRange->query($sql);
        return true;
    }
	
	function __checkLogin() {
		$login = "'".$_SERVER['REMOTE_USER']."'";
		$locations = '';
		$sql = "SELECT id, name FROM hour_locations WHERE login = $login";
		$result = $this->HourDateRange->query($sql);	
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
		// for deciding whether or not to display dashboard link based on whether or not the branch has reference hours
		$this->set('reference_hours',1);
		if(isset($result[0]['hour_locations']['id']) && !empty($result[0]['hour_locations']['id'])) {
			$location_id = $result[0]['hour_locations']['id'];
			$sql2 = "SELECT id FROM hour_groupings WHERE hour_location_id = $location_id AND hour_type_id = 3";
			$result2 = $this->HourDateRange->query($sql2);
			if(empty($result2)) {
				$this->set('reference_hours',0);
			}
		}
		return $locations;
	}
}
?>
