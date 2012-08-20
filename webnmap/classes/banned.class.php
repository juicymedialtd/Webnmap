<?
/**
* @package Framework Application - Banned Users
* @copyright (C) 2005 - 2006 Juicy Media Ltd.
* @license http://www.juicymedia.co.uk/
* @author Peter Davies <peter [DOT] davies [AT] juicymedia [DOT] co [DOT] uk>
* @version 1.0.1
*
**/

/** ensure this file is being included by a parent file */
defined( '_VALID_SEO' ) or die( 'Direct Access to this location is not allowed.' );

class CheckVisitor {
	/** @var array All allowed IP addresses */
	var $_allow_ip = array( '*.*.*.*' );
	/** @var array All denied IP addresses */
	var	$_deny_ip = array( '192.168.1.1' );
	/** @var array all allowed IP addresses (Petes IP: 81.86.0.238, Ians  IP: 84.12.139.112) */
	var $_maintenance_allow_ip = array( '84.12.139.112', '81.86.0.238');
	//var $_maintenance_allow_ip = array( );

	/**
	 * Constructor
	 */
	function CheckVisitor(){
		// check banned IP addresses before serving webpage of any type
		if (BAN_IP_ADDRESSES == "on"){
			$this->check_IP_ban_list();
		}

		// maintenance mode detection
		$this_page_name = $_SERVER['PHP_SELF'];
		if (MAINTENANCE_MODE == "on" && $this_page_name != MAINTENANCE_MODE_PAGE)
		{
			$found = 0;
			while (list ($key, $val) = each ($this->_maintenance_allow_ip)) {
				if ($this->checkIPorRange($val)) {
					$found = 1;
				}
			}

			if (!$found){
				include(DIR_SERVER_ROOT."htdocs/".MAINTENANCE_MODE_PAGE);
				exit();
		  	}
		}
	}

	/**
	 * Check an IP address or range of IP's
	 * @param string An IP Address
	 */
	function checkIPorRange ($ip_address) {
	    if (ereg("-",$ip_address)) {
	        // Range
	        $ar = explode("-",$ip_address);
	        $your_long_ip = ip2long($_SERVER["REMOTE_ADDR"]);
	        if ( ($your_long_ip >= ip2long($ar[0])) && ($your_long_ip <= ip2long($ar[1])) ) {
	            return TRUE;
	        }
	    } else {
	        // Single IP
	        if ($_SERVER["REMOTE_ADDR"] == $ip_address) {
	            return TRUE;
	        }
	    }
	    return FALSE;
	}

	/**
	 * E-Mail admin if a banned users tries to access the site
	 */
	function ip_detect_mail(){
		$ip_detect = $this->_deny_ip;

		$punish = 0;
		while (list ($key, $val) = each ($ip_detect)) {
			if ($this->checkIPorRange($val)) {
				$punish = 1;
			}
		}
		if ($punish) {
			// Email the webmaster
			$msg .= "The following banned ip tried to access one of the sites:\n";
			$msg .= "Host: ".$_SERVER["REMOTE_ADDR"]."\n";
			$msg .= "Agent: ".$_SERVER["HTTP_USER_AGENT"]."\n";
			$msg .= "Referrer: ".$_SERVER["HTTP_REFERER"]."\n";
			$msg .= "Document: ".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]."\n";
			$headers .= "X-Priority: 1\n";
			$headers .= "From: ".ADMIN_NAME." <".ADMIN_EMAIL.">\n";
			$headers .= "X-Sender: <".ADMIN_EMAIL.">\n";

			mail (ADMIN_EMAIL, SESS_NAME." Banned User Access", $msg, $headers);
		}
	}

	/**
	 * get the banned IP from array
	 */
	function check_IP_ban_list(){

		#OPEN BAN IP LIST
		//$fp = fopen(DIR_SERVER_ROOT.'ilcfg/ban_list.inc.php', 'r');
		//$_deny_ip = explode("\n", fread($fp, filesize(DIR_SERVER_ROOT.'ban_list.inc.php')));

		$_ip = $_SERVER['REMOTE_ADDR'];
		$_allowed = false;
		foreach($this->_allow_ip as $_a_ip){
			$_a_ip = str_replace('.','\.',$_a_ip);
			$_a_ip = str_replace('*','[0-9]{1,3}',$_a_ip);
			$_a_ip = str_replace('?','[0-9]{1}',$_a_ip);
			if(ereg("^{$_a_ip}$", $_ip)) $_allowed = true;
		}
		if(!$_allowed) die($_error_message);
		$_allowed = true;
		foreach($this->_deny_ip as $_d_ip){
			$_d_ip = str_replace('.','\.',$_d_ip);
			$_d_ip = str_replace('*','[0-9]{1,3}',$_d_ip);
			$_d_ip = str_replace('?','[0-9]{1}',$_d_ip);
			if(ereg("^{$_d_ip}$", $_ip)) $_allowed = false;
		}
		if(!$_allowed)
		{
			$this->ip_detect_mail();
			include(DIR_SERVER_ROOT.BAN_IP_PAGE);
			exit();
		}
	}
}
?>