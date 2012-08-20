<?
/**
* @package SEO-CMS Application
* @copyright (C) 2005 - 2006 Juicy Media Ltd.
* @license http://www.juicymedia.co.uk/products/
* @author Peter Davies <peter [DOT] davies [AT] juicymedia [DOT] co [DOT] uk>
* @version 3.0.1
*
**/

// set a flag that this is a parent file
define( '_VALID_SEO', 1 );
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//error_reporting(E_ALL - E_NOTICE);

// checks for configuration file, if none found loads installation page
if ( !file_exists( 'config.php' ) || filesize( 'config.php' ) < 10 ) {
	header( 'Location: errors/offline.php' );
	exit();
}
// include the base config file for the configuration of the script
require_once('config.php');

// configure session data & storage locations loaded from config.php
// this is required for running any apps on ClickWT servers
// WARNING: make sure the SESS_DIR actually exists (see checks in place)
ini_set('session.name', md5(SESS_NAME));
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_lifetime',  SESS_COOKIE_LIFETIME);
ini_set('session.gc_maxlifetime', SESS_MAX_LIFETIME);
ini_set('session.save_path', SESS_DIR);
//is_dir(SESS_DIR)? ini_set('session.save_path', SESS_DIR) : ini_set('session.save_path', '/tmp');
// avoid starting multiple sessions
if (!isset ($_SESSION)) session_start();

// detect if to show the rest of the site
// no need to process anything below if not allowed to see site
require_once (CLASSES_DIR.'banned.class.php');
$continue =& new CheckVisitor;

// get all arrays and process
$protects = array('_REQUEST', '_GET', '_POST', '_COOKIE', '_FILES', '_SERVER', '_ENV', 'GLOBALS', '_SESSION');
foreach ($protects as $protect) {
    if ( in_array($protect , array_keys($_REQUEST)) ||
    in_array($protect , array_keys($_GET)) ||
    in_array($protect , array_keys($_POST)) ||
    in_array($protect , array_keys($_COOKIE)) ||
    in_array($protect , array_keys($_FILES))) {
        die("Invalid Request.");
    }
}

// define the database connectivity code, also define the cache directory for queries
// already performed, makes the complicated queries appear to execute quicker
require_once(ADODB_DIR .'adodb.inc.php');
$ADODB_CACHE_DIR = SESS_DB_DIR;
//is_dir(SESS_DB_DIR)? $ADODB_CACHE_DIR = SESS_DB_DIR : $ADODB_CACHE_DIR = '/tmp';

// load the generic functions (includes Smarty functions)
require_once(CLASSES_DIR.'general.func.php');

// load the template engine ready for the next class
//require_once (SMARTY_DIR.'Smarty.class.php');
require_once (SMARTY_DIR.'Smarty.class.php');

// define the web application profiler this should be the first
// class loaded as it determines execution times
require_once(CLASSES_DIR.'profiler.class.php');

// include the basic ACL api class - USED in the siteloader class
require_once(CLASSES_DIR.'phpgacl/gacl.class.php');

// include the multilanguage code for Smarty
require_once(CLASSES_DIR.'multilang.class.php');

// define and setup the MAIN web application
// within this class should be the setup of all classes
require_once(CLASSES_DIR .'siteloader.class.php');
$site =& new SiteLoader();

?>