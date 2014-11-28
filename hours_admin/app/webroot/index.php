<?php
/**
 * Index
 *
 * The Front Controller for handling every request
 *
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
 * @subpackage    cake.app.webroot
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Use the DS to separate the directories in other defines
 */

	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}
/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */




/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 *
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

	
	if (!defined('ROOT')) {

		//define('ROOT', DS . 'hours_locations');
		if(stristr(getenv('HTTP_HOST'), '.dev')){

            define('ROOT', DS . '/media/sf_virtualshare/hours.library.ualberta.ca/docroot/hours_admin');
         }else{
         	
			define('ROOT', DS . 'var/www/sites/hours.library.ualberta.ca/docroot/hours_admin');         	
         }

	}


       
/**
 * The actual directory name for the "app".
 *
 */
	if (!defined('APP_DIR')) {
		define('APP_DIR', 'app');
	}

/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 */
	if (!defined('CAKE_CORE_INCLUDE_PATH')) {
        //define('CAKE_CORE_INCLUDE_PATH', DS.'usr'.DS.'local'.DS.'lib');
        if(stristr(getenv('HTTP_HOST'), '.dev')){
            define('CAKE_CORE_INCLUDE_PATH', DS.'var'.DS.'www'.DS.'html/myshare'.DS.'hours.library.ualberta.ca'.DS.'cakephp-1.3.20');
        }else{
			define('CAKE_CORE_INCLUDE_PATH', DS.'var'.DS.'www'.DS.'sites'.DS.'hours.library.ualberta.ca'.DS.'cakephp-1.3.20');        	
        }    
	}


/**
 * URL of public hours portal 
 *
 */

    if (!defined('PUBLIC_URL')) {
		
		if(stristr(getenv('HTTP_HOST'), '.dev')){
			define('PUBLIC_URL', 'http://hours.dev');
		}else{		
			define('PUBLIC_URL', 'http://hours.library.ualberta.ca');
		}	

	}
	

/**
 * URL of hours admin
 *
 */

    if (!defined('ADMIN_URL')) {
    	if(stristr(getenv('HTTP_HOST'), '.dev')){
			define('ADMIN_URL', 'http://hoursadmin.dev');
		}else{	
			define('ADMIN_URL', 'http://hoursadmin.library.ualberta.ca');
		}
	}	
	
/**
 * URL of lookup scripts
 *
 */

    if (!defined('SCRIPT_URL')) {
    	if(stristr(getenv('HTTP_HOST'), '.dev')){
    		define('SCRIPT_URL', 'http://hoursadmin.dev');
    	}else{
			define('SCRIPT_URL', 'http://hours.library.ualberta.ca');
		}
	}		


/**
 * Editing below this line should NOT be necessary.
 * Change at your own risk.
 *
 */
	if (!defined('WEBROOT_DIR')) {
		define('WEBROOT_DIR', basename(dirname(__FILE__)));                
	}
	if (!defined('WWW_ROOT')) {
		define('WWW_ROOT', dirname(__FILE__) . DS);
              
	}
	if (!defined('CORE_PATH')) {
		if (function_exists('ini_set') && ini_set('include_path', CAKE_CORE_INCLUDE_PATH . PATH_SEPARATOR . ROOT . DS . APP_DIR . DS . PATH_SEPARATOR . ini_get('include_path'))) {
			define('APP_PATH', null);
			define('CORE_PATH', null);
		} else {
			define('APP_PATH', ROOT . DS . APP_DIR . DS);
			define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
                        
		}
	        
        }
        
      
        if (!include(CORE_PATH . 'cake' . DS . 'bootstrap.php')) {
		trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
	}
	if (isset($_GET['url']) && $_GET['url'] === 'favicon.ico') {
           
		return;
                
	} else {
            
		$Dispatcher = new Dispatcher();
                  
		$Dispatcher->dispatch();
	}
        
        
