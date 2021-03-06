<?php
/**
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/*
require DIRECTORY_SEPARATOR . 'home' . DIRECTORY_SEPARATOR . 'jdearles' . DIRECTORY_SEPARATOR . 'ltk' . DIRECTORY_SEPARATOR . 'trunk' . 
DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'staffdirectory' . DIRECTORY_SEPARATOR . 'hdadmin' . DIRECTORY_SEPARATOR . 'app' . 
DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'index.php'; */

//Jeremy's code


if(stristr(getenv('HTTP_HOST'), '.dev')){

require "/media/sf_virtualshare/hours.library.ualberta.ca/docroot/hours_admin/app/webroot/index.php";
}else{
require DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'sites' . DIRECTORY_SEPARATOR . 'hours.library.ualberta.ca' . 
DIRECTORY_SEPARATOR . 'docroot' . DIRECTORY_SEPARATOR .  'hours_admin' . DIRECTORY_SEPARATOR . 'app' . 
DIRECTORY_SEPARATOR . 'webroot' . DIRECTORY_SEPARATOR . 'index.php';
}