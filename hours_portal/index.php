<?php
error_reporting(E_ALL ^ E_NOTICE);
ob_start();
session_start();

include('includes/head.inc.php');

include('includes/core.inc.php');

include('includes/footer.inc.php');

ob_end_flush();
?>