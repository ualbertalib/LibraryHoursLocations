<?php

include('config_other.inc.php');





ob_start();
session_start();

include('includes/helpers.inc.php');
if ( getLanguage()=='fr' ){
	setlocale(LC_ALL, 'fr_CA'); 	
}else{
	setlocale(LC_ALL, 'en_CA'); 
}
$_SESSION['language'] = getLanguage();

include('includes/head.inc.php');

include('includes/core.inc.php');

include('includes/footer.inc.php');

ob_end_flush();
?>