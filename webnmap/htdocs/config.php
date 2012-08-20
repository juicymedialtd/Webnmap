<?
/**
* @package webnmap Application
* @copyright (C) 2005 - 2006 Juicy Media Ltd.
* @license http://www.juicymedia.co.uk/products/
* @author Peter Davies <peter [DOT] davies [AT] juicymedia [DOT] co [DOT] uk>
* @version 1.0.1
*
**/

/** ensure this file is being included by a parent file */
defined( '_VALID_SEO' ) or die( 'Direct Access to this location is not allowed.' );

// debug info
define('DEBUG_DATABASE',0);
define('LOG_DATABASE',0);
define('DEBUG_PHP',0);
define('DEBUG_PROFILER',0);
define('DEBUG_SMARTY',0);

// define admin contact details
define('ADMIN_NAME', 'Juicy Media Admin');
define('ADMIN_EMAIL', 'admin@juicymedia.co.uk');

// define the project name version
define('PACKAGE_NAME', 'JM-WEBNMAP');
define('PACKAGE_VERSION', PACKAGE_NAME.' v1.0');
define('BACKBASE_VERSION', '3_1_7');
define('BACKBASE_SOURCE', 'Development');
//define('BACKBASE_SOURCE', 'Production');

// define director constants, but for security
// the classes should be outside of the public_html area
// or at least not accessible to the public
define('ROOT_DIR', '/home/webnmap/public_html/webnmap/');
define('CLASSES_DIR', ROOT_DIR.'classes/');
define('SMARTY_DIR', CLASSES_DIR.'smarty/');
define('ADODB_DIR', CLASSES_DIR.'adodb/');

// base directory structures
define('DIR_SERVER_ROOT', ROOT_DIR);
define('DIR_SERVER_ROOT_ADMIN', '/home/webnmap/public_html/webnmap/htdocs/');
define('DIR_SERVER_ROOT_PRIV', ROOT_DIR.'cache');

// define session info
define('SESS_NAME', PACKAGE_NAME);
define('SESS_COOKIE_LIFETIME', 129600);
define('SESS_MAX_LIFETIME', 3600);
define('SESS_DB_LIFETIME', 3600);
define('SESS_TPL_LIFETIME', 3600);
define('SESS_DIR', DIR_SERVER_ROOT_PRIV.'/apache_sessions');
define('SESS_DB_DIR', DIR_SERVER_ROOT_PRIV.'/adodb_cache');

// base urls
define('HTTP_SERVER', 'http://www.webnmap.co.uk/');
define('HTTPS_SERVER', 'https://www.webnmap.co.uk/');
define('HOST_NAME', 'www.webnmap.co.uk');

// define mailer
define('SMTP_HOST', 'mail.webnmap.co.uk');
define('SMTP_PORT', '25');

// maintenance mode configuration
define('MAINTENANCE_MODE','off');
define('MAINTENANCE_MODE_MSG','Website in Maintenance Mode - Please try again later.');
define('MAINTENANCE_MODE_PAGE','errors/offline.php');

// ip address banning
define('BAN_IP_ADDRESSES','on');
define('BAN_IP_MSG','Your IP address has been banned.');
define('BAN_IP_PAGE','errors/banned.php');

// make sure this user is readonly for security on frontend
// the admin area is the only area required with write access
// ****************************************************************
// **** this may need to be 'table' specific security
// ****************************************************************
define('DB_HOST','[edit]');
define('DB_NAME','[edit]');
define('DB_USER','[edit]');
define('DB_PASS','[edit]');

// the smarty basic template configuration:
// use a php extension on the template file so that dreamweaver can open it
define('TPL_NAME','index.tpl.php');
define('TPL_DIR','default');

// set any defaults here
define ('DEFAULT_RESULTS_PER_PAGE',10);

?>
