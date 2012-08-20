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

if ($_SESSION['session_id']){
	header("Location: main.php");
	exit();
} else {

	if (!is_null($_POST['fullname'])){
		echo "Registrations are presently disabled. Check back in a couple of weeks or contact pete@juicymedia.co.uk";
	} else {
		$site->display("register");
	}
}

$site->shutdown();


?>