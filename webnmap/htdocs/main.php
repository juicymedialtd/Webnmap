<?
/**
* @package webnmap Application
* @copyright (C) 2005 - 2006 Juicy Media Ltd.
* @license http://www.juicymedia.co.uk/products/
* @author Peter Davies <peter [DOT] davies [AT] juicymedia [DOT] co [DOT] uk>
* @version 1.0.1
*
**/

// load the startup config file
require_once($_SERVER['DOCUMENT_ROOT'] . '/startup.php');

// ensure this file is being included by a parent file
defined( '_VALID_SEO' ) or die( 'Access to this location is not allowed.' );

if ($_SESSION['session_id'] != "") {
	$this_session = md5( $_SESSION['session_user_id']  . $_SESSION['session_username'] . $_SESSION['session_usertype'] . $_SESSION['session_logintime'] );
	if ($_SESSION['session_id'] == $this_session){

		// detect incomming functions e.g. windows, portal boxes
		$do_function = is_null($_REQUEST['process'])? "index" : cleanText($_REQUEST['process']);

		// load the specific template now
		switch ( $do_function ) {
			case 'window':
			case 'portal':
				$site->display($do_function,$_REQUEST['id'],$_REQUEST['cid']);
				break;
			case 'index':
			default:
				$site->display($do_function);
				break;
		}
	} else {
		// catches poeple trying to cheat by modifying the session data -
		sysErrorAlert("Incorrect Username, Password or Blocked Account.  Please try again", "document.location.href='index.php?msg=Incorrect Username, Password or Blocked Account. Please try again'");
	}
} else {
	// standard redirect if the session does not exist
	sysErrorAlert("Session expired, please login again.", "document.location.href='index.php?msg=Session expired, please login again.'");
}

// must shut all down
$site->shutdown();


?>