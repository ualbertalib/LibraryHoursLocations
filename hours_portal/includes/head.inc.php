<?php 
$clfheader = file_get_contents('http://clf.library.ubc.ca/7.0.2/library-header.html');

$start = strpos($clfheader, '<!-- JavaScript -->');
$end = strpos($clfheader, '</script>', $start);
$length = $end - $start;

// we need older jQuery for the portal to work properly with jScrollPane
$clfheader = substr_replace($clfheader, '<!-- JavaScript --> <script src="http://clf.library.ubc.ca/7.0.2/js/jquery-1.7.2.min.js">', $start, $length);

$insert = '';

if (basename($_SERVER["SCRIPT_NAME"]) != "print.php") {
  $insert .= '
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
<script src="js/jquery.hashchange.min.js" type="text/javascript"></script>';
}

$insert .= '
<title>Hours and Locations | UBC Library Hours and Locations</title>
<link rel="stylesheet" type="text/css" href="css/hours.css" />
';

$clfheader = str_replace('</head>', $insert.'</head>', $clfheader);

echo $clfheader;
?>