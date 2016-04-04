<?php

include('config_other.inc.php');





ob_start();
session_start();

include('includes/helpers.inc.php');
$_SESSION['language'] = getLanguage();
if ( $_SESSION['language']=='fr' ){
	setlocale(LC_ALL, 'fr_CA'); 	
}else{
	setlocale(LC_ALL, 'en_CA'); 
}


include('includes/head.inc.php');

include('includes/core.inc.php');

include('includes/footer.inc.php');

ob_end_flush();
?>