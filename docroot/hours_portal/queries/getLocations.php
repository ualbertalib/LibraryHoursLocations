<?php 
/**
 * Get location data
 *
 **/

require_once('dbConnect.php');

$sql = "SELECT * FROM hour_locations";

$sth = $dbh->prepare($sql);
$status = $sth->execute();
$results = $sth->fetchAll();
$record_count = count($results);

?>