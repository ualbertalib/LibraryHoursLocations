<?php 
//header('Cache-Control: no-cache,must-revalidate');
//header('Pragma: no-cache');
//$pageloadstart=microtime(true);
$uhead = file_get_contents('http://www.library.ubc.ca/home/includefiles/header.html');

//repair unescaped &'s in links
$uhead = preg_replace('~&([a-z0-9_]*)=~ims','&amp;\1=',$uhead);

//fix invalid html (value attribute with image type?)
$uhead = str_replace('value="Search"','',$uhead);

//believe it or not, the template gives us an unclosed <head>
if(!strpos($uhead,'</head>')){
    $uhead=str_replace('<!-- BEGIN: UBC CLF HEADER -->','</head><body><!-- BEGIN: UBC CLF HEADER -->',$uhead);
}

//library's jQuery is too fusty
$uhead=str_replace('http://www.library.ubc.ca/_ubc_clf/js/jquery-min-latest.js',
    'http://search.library.ubc.ca/js/jquery-1.6.1.min.js',
        $uhead);
        
//        $uhead=str_replace('http://www.library.ubc.ca/_ubc_clf/js/jquery-min-latest.js','/js/jquery.js',$uhead);

//need this id to win specificity wars with the UBC CLF
//$uhead=str_replace('<body>','<body id="HNbody">',$uhead);

$insert='
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js"></script>
';

if (basename($_SERVER["SCRIPT_NAME"]) != "print.php") {
  $insert .= '<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>

'; }

$uhead = str_replace('</head>',$insert.'</head>', $uhead);

//Google stuff, not wanted
//$uhead = preg_replace('~<script[^>]*jsapi[^>]*></script>~','',$uhead);

header('Content-type: text/html; charset=UTF-8');
$uhead='<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Hours and Locations | UBC Library Hours and Locations</title>
<link rel="stylesheet" type="text/css" href="css/hours.css" />

'.$uhead;

if(!isset($noecho)){
    echo $uhead;
}
