<?php
$ufoot = file_get_contents('http://www.library.ubc.ca/home/includefiles/footer.html');
$patterns = array(
    '~<!---*~', // not xml
    '~---*>~',
    '~<!-- Google Analytics.*?<!-- End UBC~mis' // just adds errors; reinserted below
);
$replacements = array(
    '<!--',
    '-->',
    '<!-- End UBC'
);
$ufoot = preg_replace($patterns, $replacements, $ufoot);
echo '
    '.$ufoot;
if(isset($admin)&&$admin){
?>

<script src="/js/jquery.tinymce.js"></script>
<script>
var mceData={
    script_url : '/js/tinymce/jscripts/tiny_mce/tiny_mce.js',
    skin : 'default',
    theme : "advanced",
    //theme_advanced_disable : "styleselect,code,visualaid,cleanup,help,justifyleft,justifyright,justifyfull,justifycenter,underline,strikethrough",
//    theme_advanced_buttons1: "bold,italic,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,|,insertimage,|,formatselect,hr,removeformat,|,sub,sup,|,charmap,|,search,replace|,pastetext,pasteword,|,spellchecker",
//    theme_advanced_buttons2: "",
//    theme_advanced_buttons3: "",
    theme_advanced_toolbar_location : "top",
    mode: 'textareas',
    plugins : "searchreplace,paste,spellchecker",
//        formats:{imagecaption:{block:'p',classes:'imageCaption'}},
    theme_advanced_blockformats:"h3,h4,p,imagecaption",
    theme_advanced_statusbar_location: "bottom",
    theme_advanced_resizing: true,
    spellchecker_languages : "+English=en-CA,Français=fr-CA",
    spellchecker_rpc_url:"/js/tinymce/jscripts/tiny_mce/plugins/spellchecker/rpc.php",
    content_css:
        'http://www.library.ubc.ca/_ubc_clf/css/clf-required-fixedwidth.css,'
        +'http://www.library.ubc.ca/_ubc_clf/css/typography.css,'
        +'http://www.library.ubc.ca/_ubc_clf/css/clf-library-addon.css,'
        +'http://www.library.ubc.ca/_ubc_clf/css/clf-optional.css,'
        +'/css/hn.css',
    body_id: "HNContent"
};
(function(selector,initdata){
    var $textarea=$(selector);
    if($textarea.size()===0){
        return false;
    }
    $textarea.each(
        function(){
            $t=$(this);
            if($t.data('mceinit')==='1'){
                return;
            }
            $t.tinymce(initdata);
            $t.data('mceinit','1');
        });
})('.editor',mceData);
</script>

<?php
}
?>

<?php
if (basename($_SERVER["SCRIPT_NAME"]) != "print.php") {
?>

<script type="text/javascript" src="js/hours.js"></script>
<script type="text/javascript" src="js/jquery.hoverIntent.min.js"></script>
<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="js/jquery.jscrollpane.min.js"></script>

<?php } ?>

<?php
if ($_SERVER['SERVER_NAME'] != "kemano.library.ubc.ca" && $_SERVER['SERVER_NAME'] != "hours-dev.library.ubc.ca") {
?>

<!-- begin Google Analytics -->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-231366-45']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
  
<?php
}
?>

</body>
</html>
