<?
/**
* @package Framework Application - Authentication
* @copyright (C) 2005 - 2006 Juicy Media Ltd.
* @license http://www.juicymedia.co.uk/
* @author Peter Davies <peter [DOT] davies [AT] juicymedia [DOT] co [DOT] uk>
* @version 1.0.1
*
**/

/** ensure this file is being included by a parent file */
defined( '_VALID_SEO' ) or die( 'Direct Access to this location is not allowed.' );

// include basic ACL api
include('phpgacl/gacl.class.php');

// ***************************************************************************
// smarty extension configuration
// ***************************************************************************
class framework_ACL extends gacl {

	function framework_ACL(){

		$gacl = new gacl();
		$username = $db->quote($_POST['username']);
		$password = $db->quote(md5($_POST['password']));
		$sql = 'SELECT name FROM users WHERE name=';
		$sql .= $username.' AND password='.$password;
		$row = $db->GetRow($sql);
		if($gacl->acl_check('system','login','user',$row['name'])){
			$_SESSION['username'] = $row['name'];
			return true;
		}
		else
		return false;


	}





}








?>