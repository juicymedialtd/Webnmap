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

// deal with error messages
$msg = cleanText($_REQUEST['msg']);

// Browser Check
$browserCheck = 0;
if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && isset( $_SERVER['HTTP_REFERER'] ) ) {
	$browserCheck = 1;
}

if ($msg && $browserCheck ) {
	// limit msg to 200 characters
	if ( strlen( $msg ) > 200 ) {
		$msg = substr( $msg, 0, 200 );
	}
}

if (isset($_REQUEST['logout'])){
	unset($_SESSION['session_id']);
	unset($_SESSION['session_user_id']);
	unset($_SESSION['session_username']);
	unset($_SESSION['session_usertype']);
	unset($_SESSION['session_gid']);
	unset($_SESSION['session_logintime']);

	$_SESSION = array();
	session_destroy();
}

// take submitted data and construct session data
if (isset( $_POST['submit'] )) {
	/** escape and trim to minimise injection of malicious sql */
	$username 	= cleanText(strtolower($_POST['usrname']));
	$password 	= cleanText($_POST['pass']);

	if($password == "") {
		echo "<script>alert('Please enter a password'); document.location.href='index.php?msg=Please enter a password'</script>\n";
		exit();
	} else {
		$password = md5( $password );
	}

	// get the user
	$login_detail = $site->sql->db->GetRow("SELECT * FROM site_users WHERE password = '".$password."' AND username = '".$username."'");

	if ($login_detail){
		if ( $login_detail['block'] == '0'){
			// construct Session ID
			$logintime 	= time();
			$currentDate = date("Y-m-d\TH:i:s");
			$session_id = md5( $login_detail['id'] . $login_detail['username'] . $login_detail['usertype'] . $logintime );

			// store access details for log
			$ok = $site->sql->db->Execute("INSERT INTO session_log SET time = '".$logintime."', session_id = '".$session_id."', userid = ".$login_detail['id'].", usertype = '".$login_detail['usertype']."', gid = '".$login_detail['gid']."', username = '".$login_detail['username']."'");

			// set the last visit time of the user
			$site->sql->db->Execute("UPDATE site_users SET lastvisitDate='".date('YmdHis',$logintime)."' WHERE id=".$login_detail['id']);

			// store the data in the session
			$_SESSION['session_id'] 			= $session_id;
			$_SESSION['session_user_id'] 		= $login_detail['id'];
			$_SESSION['session_username'] 		= $login_detail['username'];
			$_SESSION['session_usertype'] 		= $login_detail['usertype'];
			$_SESSION['session_gid'] 			= $login_detail['gid'];
			$_SESSION['session_logintime'] 		= $logintime;

			session_write_close();

			header("Location: main.php");
			exit();

		} else {
			sysErrorAlert("Your account has been locked.", "document.location.href='index.php?msg=Your account has been locked.'");
		}
	} else {
		sysErrorAlert("Incorrect Username, Password.  Please try again", "document.location.href='index.php?msg=Incorrect Username, Password. Please try again'");
	}
} else {
	if ($_SESSION['session_id']){
		header("Location: main.php");
		exit();
	} else {
		$site->display("login");
	}
}

$site->shutdown();


?>