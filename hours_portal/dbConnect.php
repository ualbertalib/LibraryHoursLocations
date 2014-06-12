<?php

// read database connection info from config file
$config_file = dirname(__FILE__).'/conf/db.conf';
$comment = "#";

if(file_exists($config_file)) {
	$fp = fopen($config_file, "r");
	while (!feof($fp)) {
		$line = trim(fgets($fp));
		
		if($line && !preg_match("/^$comment/", $line)) {
			$pieces = explode("=", $line);
			$option = $pieces[0];
			$value = $pieces[1];
			$config_values[$option] = $value;
		}
	}
  

  fclose($fp);

  // set values to local variables, getting hostname from dbfinder
  $dsn = 'mysql:host='.$config_values['DBHOST'].';dbname='.$config_values['DBNAME'];

  // connect to database for selecting
  try {
    
    $dbh = new PDO($dsn, $config_values['USER'], $config_values['PASS'],array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    
    //add attribute to convert empty strings to null
    $dbh->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
    
    /*** echo a message saying we have connected ***/
    //echo 'Connected to database';
    
  } catch(PDOException $e) {
    
    echo $e->getMessage();
    
  }//closes try
  
  } else {
  	echo "Config file not found";
  }

?>
