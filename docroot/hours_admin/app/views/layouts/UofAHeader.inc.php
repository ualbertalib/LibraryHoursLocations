<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class=" js flexbox canvas canvastext webgl no-touch geolocation postmessage no-websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients no-cssreflections csstransforms csstransforms3d csstransitions fontface video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths" lang="en">
<!--<![endif]-->
<meta charset="UTF-8">
<head>
<meta name="viewport" content="width=device-width">
<link rel="shortcut icon" href="https://www.library.ualberta.ca/favicon.ico">

	
		<title>University of Alberta Libraries</title>
	
<!-- CLF Stylesheets 
  <link href="/css/ubc-clf-full.min.css.css" rel="stylesheet">  -->
  <!-- <link href="https://clf.library.ubc.ca/7.0.2/colorbox/colorbox.css" type="text/css" media="screen" rel="stylesheet" /> -->
  <link href="/assets/colorbox/colorbox.css" type="text/css" media="screen" rel="stylesheet" />
  
  <!-- //hours.library.ualberta.ca/assets/css/jquery-ui.css
  <link href="https://clf.library.ubc.ca/7.0.2/css/unit.css" rel="stylesheet" /> -->
  
	<link rel="stylesheet" type="text/css" href="//www.library.ualberta.ca/2012assets/normalize.css" media="all"/>  
	<link rel="stylesheet" type="text/css" href="//www.library.ualberta.ca/2012assets/foundation.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="//www.library.ualberta.ca/2012assets/app.css" media="all"/>  
        <link rel="stylesheet" type="text/css" href="//www.library.ualberta.ca/jquery/ui/css/smoothness/jquery-ui-1.7.2.custom.css"/>
        <!-- override otherwise circle bullets appear in footer-->
        <style> .content ul li {list-style-type: none;} </style>
        <style>
            th  {font-weight: bold; font-size: 14px; text-align: left;}
               select { background-color: #FFFFFF; border: 1px solid #CCCCCC; width: 220px; vertical-align: middle; padding: 4px 6px; color: #555555; font-weight: normal;}
               select#FilterHourCategoryId { background-color: #FFFFFF; border: 1px solid #CCCCCC; width: 120px; vertical-align: middle; padding: 4px 6px; color: #555555; font-weight: normal;}
               select#FilterHourTypeId { width: 120px; }
               /* select, input[type="file"] {  height: 30px;  line-height: 30px;} */
               /*Save time and just overide medium button rather then change the code*/
               .medium.button {  font-size: 11px;  padding: 8px 20px 10px; }
               input.medium{width: auto; margin: 2px 2px 2px 2px;}
               a.red {color: #FFFFFF;}
               .row .three.columns{width: auto;}
               div dl dt {font-weight: bold; margin: 0 6px 6px 0; font-size: 16px;}
               div dl dd {margin-left: 20px; font-size: 14px;} 
               .row .seven.columns{ width: auto; };
        </style>
	

<!-- 
<script src="http://www.library.ualberta.ca/jquery/jquery-1.7.1.min.js" type="text/javascript"></script>
-->

 <!-- JavaScript -->
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script> -->
 <!-- <script src="http://clf.library.ubc.ca/7.0.2/js/jquery-1.7.2.min.js"></script> -->
 
<script src="//www.library.ualberta.ca/jquery/jquery-1.7.1.min.js" type="text/javascript"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript"></script>  
  <script src="//hours.library.ualberta.ca/assets/colorbox/jquery.colorbox_1.3.7.js" type="text/javascript"></script>
  
  
  <!-- JH
  <script src="https://clf.library.ubc.ca/7.0.2/modernizr/modernizr.js" type="text/javascript"></script> 
  <script src="//hours.library.ualberta.ca/assets/modernizr.js" type="text/javascript"></script> -->
  
  <!-- JH -->
  <!--  
  <script src="https://cdn.ubc.ca/clf/7.0.2/js/ubc-clf.min.js"></script>
  <script src="https://clf.library.ubc.ca/7.0.2/js/library-ui.js" type="text/javascript"></script>  
  <script src="https://www.google.com/jsapi?key=ABQIAAAAoRs91XgpKw60K4liNrOHoBStNMhZCa0lqZKLUDgzjZGRsKl38xSnSmVmaulnWVdBLItzW4KsddHCzA" type="text/javascript"></script>
 -->



<script>
if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement("style");
    msViewportStyle.appendChild(
        document.createTextNode(
            "@-ms-viewport{width:auto!important}"
        )
    );
    document.getElementsByTagName("head")[0].
        appendChild(msViewportStyle);
}
</script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
 <script src="js/markerwithlabel_packed.js" type="text/javascript"></script>

<!-- <script src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerwithlabel/src/markerwithlabel_packed.js"></script> -->

<script src="js/jquery.hashchange.min.js" type="text/javascript"></script>
<title>Hours and Locations | University of Alberta</title>

</head>
<body>
  <div class="container">
	<div class="row show-on-phones">
       <ul class="phone-nav twelve columns">
         <li><a href="http://www.library.ualberta.ca//databases">Databases</a></li>
         <li><a href="http://www.library.ualberta.ca/ejournals">Journals</a></li>
         <li><a href="http://www.library.ualberta.ca/browse">Subjects</a></li>
         <li><a href="http://www.library.ualberta.ca/aboutus">Libraries</a></li>	
         <li><a href="https://www.library.ualberta.ca/myaccount/">My Account</a></li>
         <li><a href="http://www.library.ualberta.ca/services">Services</a></li>
         <li><a href="http://www.library.ualberta.ca/hours" class="last">Hours</a></li>
       </ul>	
     </div>
    <div class="row">
      <div class="two columns">
        <a href="http://www.ualberta.ca"  class="ualogo"><img src="http://www.library.ualberta.ca/2012assets/ua.png"/></a>
      </div>
    <div class="two columns">
         <h2 class="show-on-phones"><a href="http://www.library.ualberta.ca/">Libraries</a> - <a href="/askus" class="askmobile">Ask Us</a> - <a  href="/francais" class="askmobile">fran&ccedil;aise</a> </h2>
      </div>
      <div class="eight columns">
        <ul class="top-nav hide-on-phones">
	  <li><a href="http://webapps.srv.ualberta.ca/search/">Find a Person</a></li>
	  <li><a href="https://www.myonecard.ualberta.ca/">ONEcard</a></li>
	  <li><a href="https://www.beartracks.ualberta.ca/">Bear Tracks</a></li>
	  <li><a href="http://www.campusmap.ualberta.ca/">Maps</a></li>
	  <li><a href="http://apps.ualberta.ca/">Email &amp; Apps</a></li>
	  <li><a href="https://eclass.srv.ualberta.ca/portal/">eClass</a></li>
	   <li><a href="http://www.ualberta.ca" id="last">UofA</a></li>
        </ul>
      </div>
    </div>
    <div class="row green hide-on-phones">
      <div class="twelve columns ">
        <div class="four columns">
	  <h1><a href="/">Libraries</a></h1>
	</div>
	<div class="eight columns">
	  <ul class="home-nav">
                    <li class="ask"><a href="/askus">Ask Us</a>
                        <p class="sub-ask-menu"><a href="/askus/chatref.html">chat</a> - <a href="http://www.library.ualberta.ca/askus/">text</a> - <a href="http://www.library.ualberta.ca/ereference/email/">email</a> - <a href="http://www.library.ualberta.ca/askus/">phone</a></p>
                    </li>
                    <li>
                    <li><a  href="francais"  class="french">Version fran&ccedil;aise</a></li>
		    </li>
               </ul>
             </div>
           </div>
         </div>
     <div class="row">
       <ul class="main-nav twelve columns hide-on-phones">
         <li><a href="http://www.library.ualberta.ca/databases" id="first">Databases</a></li>
         <li><a href="http://www.library.ualberta.ca/ejournals">Journals</a></li>
         <li><a href="http://www.library.ualberta.ca/browse">Subjects</a></li>
         <li><a href="http://www.library.ualberta.ca/aboutus">Libraries</a></li>	
         <li><a href="https://www.library.ualberta.ca/myaccount/">My Account</a></li>
         <li><a href="http://www.library.ualberta.ca/services">Services</a></li>
         <li><a href="http://www.library.ualberta.ca/hours" id="last">Hours</a></li>
       </ul>	
     </div>
       <div class="row top-margin">
             <!-- <div class="twelve columns">
             <div class="panel"> -->
             <div class="content expand">
                 <div class="row-fluid expand">
			<!-- UofA Header END -->