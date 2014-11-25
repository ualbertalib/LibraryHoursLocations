<?php
function findDir($URI,$findme){
	$parts=explode('/',$URI);	

	return in_array($findme,$parts);
}

function getLanguage(){

	if(findDir($_SERVER['REQUEST_URI'],'fr') || findDir($_SERVER['REQUEST_URI'],'francais')   ){
		$LANGUAGE="fr";

	}else{
		$LANGUAGE="en";				
	}	

	return $LANGUAGE;
}
