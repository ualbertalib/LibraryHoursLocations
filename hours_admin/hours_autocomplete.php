<?php
/*
 * For autocompletion of times when entering hours
 */

// options for midnight and noon
$options['midnight'] = array('12:00 am');
$options['noon'] = array('12:00 pm');
// add options for each hour and hhalf hour from 1 to 12
$count = 1;
while($count < 13) {
		$options[$count] = array($count.':00 am',$count.':30 am',$count.':00 pm',$count.':30 pm');
		$options[$count.'am'] = array($count.':00 am');
		$options[$count.' am'] = array($count.':00 am');
		$options[$count.'pm'] = array($count.':00 pm');
		$options[$count.' pm'] = array($count.':00 pm');
		$options[$count.':00'] = array($count.':00 am',$count.':00 pm');
		$options[$count.':00am'] = array($count.':00 am');
		$options[$count.':00 am'] = array($count.':00 am');
		$options[$count.':00pm'] = array($count.':00 pm');
		$options[$count.':00 pm'] = array($count.':00 pm');
		$options[$count.':30'] = array($count.':30 am',$count.':30 pm');
		$options[$count.':30 am'] = array($count.':30 am');
		$options[$count.':30 pm'] = array($count.':30 pm');
		$options[$count.':30am'] = array($count.':30 am');
		$options[$count.':30pm'] = array($count.':30 pm');
	$count++;
}
// option to get aall possible values, which we decided not to use
$options[0] = array('12:00 am','12:30 am','1:00 am','1:30 am','2:00 am','2:30 am','3:00 am','3:30 am','4:00 am','4:30 am','5:00 am','5:30 am','6:00 am','6:30 am','7:00 am','7:30 am','8:00 am',
                    '8:30 am','9:00 am','9:30 am','10:00 am','10:30 am','11:00 am','11:30 am','12:00 pm','12:30 pm','1:00 pm','1:30 pm','2:00 pm','2:30 pm','3:00 pm','3:30 pm','4:00 pm','4:30 pm',
                    '5:00 pm','5:30 pm','6:00 pm','6:30 pm','7:00 pm','7:30 pm','8:00 pm','8:30 pm','9:00 pm','9:30 pm','10:00 pm','10:30 pm','11:00 pm','11:30 pm'
			  );

// return all possible hours values based on term passed
if(isset($_GET['callback'])) {
    if(isset($_GET['term'])) {
		$key = array_key_exists($_GET['term'], $options);
		if($key) {
			$hourslist = $options[$_GET['term']];
		}
        echo $_GET['callback'] . '(' . json_encode(array_values(array_unique($hourslist))) . ');';
    }
}
?>
