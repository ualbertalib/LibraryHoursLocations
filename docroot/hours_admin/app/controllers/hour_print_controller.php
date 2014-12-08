<?php 
class HourPrintController extends AppController {

	var $name = 'HourPrint';
    var $uses = array('HourLocation');

	function index() {
		$this->set('title_for_layout','Print Hours');
		$this->__checkLogin();
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