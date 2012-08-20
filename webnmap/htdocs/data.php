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

// include the multilanguage code for Smarty
require_once(CLASSES_DIR.'multilang.class.php');

// define and setup the MAIN web application
// within this class should be the setup of all classes
require_once(CLASSES_DIR .'siteloader.class.php');

// only perform data functions if the user is logged-in, then validate session just in case
if ($_SESSION['session_id'] != "") {
	$this_session = md5( $_SESSION['session_user_id']  . $_SESSION['session_username'] . $_SESSION['session_usertype'] . $_SESSION['session_logintime'] );
	if ($_SESSION['session_id'] == $this_session){

		// detect incomming functions e.g. windows, portal boxes
		$do_function = cleanText($_REQUEST['process']);
		$itemid = intval($_REQUEST['id']);
		$site_user_id = intval($_SESSION['session_user_id']);


		if (is_null($do_function)) {
			exit();
		} else {

			$data =& new SiteLoader_SQL;
	        $data->setConnection();

	        if ($do_function == "addcommand"){

	        	$pre_hours = date("H");
	        	$pre_mins = date("i");
	        	$pre_seconds = date("s");
	        	$pre_day = date("d");
	        	$pre_month = date("m");
	        	$pre_year = date("Y");
	        	$curdatetime = date("Y-m-d H:i:s");

	        	// sanitize data
	        	$date_from_form = explode(" ",cleanText($_REQUEST['date_exec']));

	        	// detect if the date added is older, if so set to now + 10 mins
	        	if (intval(cleanText($_REQUEST['hour_exec'])) >= $pre_hours ){
	        		if (intval(cleanText($_REQUEST['min_exec'])) >= $pre_mins ){
	        			$act_mins = sprintf("%02d",intval(cleanText($_REQUEST['min_exec'])));
	        			$act_hrs = sprintf("%02d",intval(cleanText($_REQUEST['hour_exec'])));
	        		} else {
	        			$act_mins = sprintf("%02d",intval(cleanText($_REQUEST['min_exec'])));
	        			$act_hrs = sprintf("%02d",intval(cleanText($_REQUEST['hour_exec']))+1);
	        		}
	        	} else {
	        		$act_mins = sprintf("%02d",intval(cleanText($_REQUEST['min_exec'])));
	        		$act_hrs = sprintf("%02d",intval(cleanText($_REQUEST['hour_exec']))+1);
	        	}
	        	// fix 11 O'Clock issue, 23+1
	        	if ($act_hrs == 24) { $act_hrs = sprintf("%02d","0"); }

	        	$mins 	= ($_REQUEST['rec_minute'] == "1")? "*" : $act_mins;
	        	$hours 	= ($_REQUEST['rec_hour'] == "1")? "*" : $act_hrs;
	        	$day 	= ($_REQUEST['rec_day'] == "1")? "*" : sprintf("%02d",$date_from_form[0]);
	        	$month 	= ($_REQUEST['rec_month'] == "1")? "*" : sprintf("%02d",$date_from_form[1]);
	        	$year 	= ($_REQUEST['rec_year'] == "1")? "*" : str_replace("20","",$date_from_form[2]);

				// ********************************************
				// DEBUG REMOVE
				// ********************************************
	        	$day 	= "*";
	        	$month 	= "*";
	        	$year 	= "*";
	        	// ********************************************

	        	// sanitize the rest of the data, remove user entered data such as http
	        	$host_name 	= str_replace("http://","",cleanText($_REQUEST['host_name']));
	        	$user_id	= intval($_REQUEST['user_id']);
	        	$permission = $_REQUEST['permission'];
	        	$command 	= intval($_REQUEST['command_id']);
	        	$group_id 	= intval($_REQUEST['group_id']);

	        	// only continue if they have not cheated, cannot rely on JS frontend validation
				if ($host_name == "localhost" || $host_name == "") {
	    			header("Content-Type: text/xml; charset=UTF-8");
					echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
					echo "<div xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:b=\"http://www.backbase.com/b\" xmlns:s=\"http://www.backbase.com/s\">";
					echo "<div>You cannot perform a localhost scan.</div>";
					echo '<b:button b:action="trigger" b:event="close" b:target="id(\'window_schedule\')">Close Window</b:button>';
					echo "</div>";
					exit();
				}

				// only continue if they have granted permission, cannot rely on JS frontend validation or URL injection/replay
	        	if ($permission == '1'){
		        	$command_item = $data->db->CacheGetRow("SELECT * FROM command_list WHERE command_id='$command'");
		        	$command_value = mysql_real_escape_string(str_replace("%%host%%", $host_name, $command_item['command']));
		        	$host_name = mysql_real_escape_string($host_name);
					$client = $user_id;

					// update the credit system on the site
					$cmdcost = (is_null($command_item['credit_cost']))? 0 : $command_item['credit_cost'];
					$data->db->Execute("UPDATE site_users SET credits=credits-".$cmdcost." WHERE id=".$user_id);

					// create the command insert statement - NB: Data is already cleaned so no mysql_real_escape_string required at this stage
		        	$sql = "INSERT INTO queue (command_id,command,client_id,hour,min,day,month,year,timestamp_added,host_name) VALUES ('$command','$command_value','$client','$hours','$mins','$day','$month','$year','$curdatetime','$host_name')";
					if ($data->db->Execute($sql) === false) {
		    			header("Content-Type: text/xml; charset=UTF-8");
						echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
						echo "<div xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:b=\"http://www.backbase.com/b\" xmlns:s=\"http://www.backbase.com/s\">";
						echo "<div>Insert Erro - Check SQL:<br /><pre>".$sql."</pre></div>";
						echo '<b:button b:action="trigger" b:event="close" b:target="id(\'window_schedule\')">Close Window</b:button>';
						echo "</div>";
						exit();

					} else {
						header("Content-Type: text/xml; charset=UTF-8");
						echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
						echo "<div xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:b=\"http://www.backbase.com/b\" xmlns:s=\"http://www.backbase.com/s\">";
						echo "<br /><br /><b:box style='width:100%'><br />";
						echo "<strong>Command submitted successfully.</strong><br />";
						echo "You can now close this window and your main screen will be updated within 60 seconds.";
						echo "<br /></b:box><br /><br /><br /><br />";
						echo '<div align="right"><b:button b:action="trigger" b:event="close" b:target="id(\'window_schedule\')">Close Window</b:button></div>';
						echo "</div>";
						exit();
					}
	        	} else {
	    			header("Content-Type: text/xml; charset=UTF-8");
					echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
					echo "<div xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:b=\"http://www.backbase.com/b\" xmlns:s=\"http://www.backbase.com/s\">";
					echo "<div>You have not ticked the permission box to execute this command.</div>";
					echo '<b:button b:action="trigger" b:event="close" b:target="id(\'window_schedule\')">Close Window</b:button>';
					echo "</div>";
					exit();
	        	}
	        }

	        if ($do_function == "showpost"){
    			header("Content-Type: text/xml; charset=UTF-8");
				echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
				echo "<div><pre>";
				print_r($_POST);
				echo "</pre></div>";
	        }

	        if ($do_function == "msg"){
    			header("Content-Type: text/xml; charset=UTF-8");
				echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
				echo "<div>";
				echo htmlspecialchars($_REQUEST['text']);
				echo "</div>";
	        }

	        if ($do_function == "cancelcmd_".$itemid){
    			header("Content-Type: text/xml; charset=UTF-8");
				echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
				echo "<div>";
				$cancel = $data->db->Execute("UPDATE queue SET status='5' WHERE id=".$itemid);
				//echo '<form action="data.php" method="POST" id="startform" name="startform" b:destination="." b:mode="replace"><input type="hidden" name="process" value="startcmd" /><input type="hidden" name="id" value="'.$itemid.'" /><div align="center"><b:button b:onclick="start_cmd">##WINDOW_COMMAND_START##</b:button></div></form>';
				echo "</div>";
	        }

	        if ($do_function == "startcmd_".$itemid){
    			header("Content-Type: text/xml; charset=UTF-8");
				echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
				echo "<div>";
				$cancel = $data->db->Execute("UPDATE queue SET status='1' WHERE id=".$itemid);
				echo '<form action="data.php" method="POST" id="cancelform_'.$itemid.'" name="cancelform_'.$itemid.'" b:destination="." b:mode="replace"><input type="hidden" name="process" value="cancelcmd_'.$itemid.'" /><input type="hidden" name="id" value="'.$itemid.'" /><div align="center"><b:button b:onclick="cancel_cmd_'.$itemid.'">##WINDOW_COMMAND_CANCEL##</b:button></div></form>';
				echo "</div>";
	        }

	        if ($do_function == "schedule"){
		        header("Content-Type: text/xml; charset=UTF-8");
				echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
	        	echo "<tasks>\n";

	        	$tasks = $data->db->GetAll("SELECT * FROM queue WHERE (client_id = ".$_SESSION['session_user_id'].")");
				$total = count($tasks);
				if ($total >= 1){
					foreach($tasks AS $i => $task){
						echo "<task id=\"".$task['id']."\">\n";
						echo "<command>".$task['command']."</command>\n";
						echo "<command_id>".$task['command_id']."</command_id>\n";
						echo "<time>".$task['hour'].":".$task['min']."</time>\n";
						echo "<date>".$task['year']."-".$task['month']."-".$task['day']."</date>\n";
						echo "</task>\n";
					}
				}
	        	echo "</tasks>";
	        }

	        if ($do_function == "cmdlist"){
	        	$groups = $data->db->GetAll("SELECT * FROM command_groups");
				$groups_total = count($groups);
				if ($groups_total >= 1){
					foreach($groups AS $i => $group){
						$cmds = $data->db->GetAll("SELECT * FROM command_list WHERE (command_list.group_id = ".$group['group_id'].")");
						$cmds_total = count($cmds);

						if ($cmds_total > 0){
							foreach($cmds AS $i => $cmd){
								$output_tasks .= "<s:tasklist b:name=\"treeTaskList".$cmd['command_id']."\">\n";
								$output_tasks .= "  <s:task b:action=\"set\" b:target=\"id('idtree')/@value\" b:value=\"".$cmd['command_id']."\" />\n";
								$output_tasks .= "  <s:task b:action=\"submit\" b:target=\"..\" />\n";
								$output_tasks .= "</s:tasklist>\n";
							}
						}

						$output .= "<b:tree b:folder=\"true\" b:label=\"".$group['group_name']."\" b:open=\"false\">\n";
						$cmds_total = count($cmds);
						if ($cmds_total >= 1){
							foreach($cmds AS $i => $cmd){
								$output .= "<b:tree id=\"".$cmd['command_id']."\" b:label=\"".$cmd['command_desc']."\"  b:onclick=\"treeTaskList".$cmd['command_id']."\" />\n";
							}
						}
						$output .= "</b:tree>\n";
					}

					header("Content-Type: text/xml; charset=UTF-8");
					echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
					echo "<div xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:b=\"http://www.backbase.com/b\" xmlns:s=\"http://www.backbase.com/s\">";
					echo $output_tasks;
					echo "<b:tree b:label=\"Available Commands\" b:multiroot=\"true\" b:open=\"true\" style=\"width: 190px;\">";
					echo $output;
					echo "</b:tree>\n";
					echo "</div>\n";
				}
	        }

	        if ($do_function == "cmddetail"){
	        	$cmds = $data->db->GetAll("SELECT * FROM command_list, command_groups WHERE (command_id = ".$itemid.") AND (command_list.group_id = command_groups.group_id)");
				$cmds_total = count($cmds);
				if ($cmds_total >= 1){
					header("Content-Type: text/xml; charset=UTF-8");
					echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
					echo "<div xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:b=\"http://www.backbase.com/b\" xmlns:s=\"http://www.backbase.com/s\">";
					foreach($cmds AS $i => $cmd){
						// remove visual
						$cmdshow = str_replace("%%host%%","<i>host</i>",$cmd['command']);

						echo '<b:detailviewer>';
						echo '	<b:property b:label="Group">'.$cmd['group_name'].'</b:property>';
						echo '	<b:property b:label="Command">'.$cmdshow.'</b:property>';
						echo '	<b:property b:label="Description">'.$cmd['command_desc'].'</b:property>';
						echo '	<b:property b:label="Cost">'.$cmd['credit_cost'].' credits</b:property>';
						echo '	<b:property b:label="Cmd ID">'.md5($cmd['command_id']).'</b:property>';
						echo '</b:detailviewer>';
						echo '<input type="hidden" id="command_id" name="command_id" value="'.$cmd['command_id'].'" />';
						echo '<input type="hidden" id="command" name="command" value="'.$cmd['command'].'" />';
						echo '<input type="hidden" id="group_id" name="group_id" value="'.$cmd['group_id'].'" />';
					}
					echo "</div>\n";
				}
	        }

	        if ($do_function == "cmd_output_list"){
	        	$complex_sql = "SELECT queue.command, queue.status, queue.timestamp_added, queue.timestamp_done, command_list.command_desc, command_groups.group_name FROM queue INNER JOIN command_list ON (queue.command_id = command_list.command_id) INNER JOIN queue_output ON (queue.id = queue_output.queue_id) INNER JOIN command_groups ON (command_list.group_id = command_groups.group_id) WHERE (queue.id = ".$itemid.")  AND (queue.client_id = ".$site_user_id.") LIMIT 1";

	        	$cmds = $data->db->GetAll($complex_sql);
				$cmds_total = count($cmds);
				if ($cmds_total >= 1){
					header("Content-Type: text/xml; charset=UTF-8");
					echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
					echo "<div xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:b=\"http://www.backbase.com/b\" xmlns:s=\"http://www.backbase.com/s\">";
					foreach($cmds AS $i => $cmd){
						echo '<p><strong>'.$cmd['group_name'].':</strong><br />';
						echo '<span>'.$cmd['command_desc'].'<s:tooltip><strong>'.$cmd['group_name'].':</strong><br />'.$cmd['command_desc'].'</s:tooltip></span></p>';
						echo '<p><strong>Command:</strong><br />';
						echo '<span>'.$cmd['command'].'<s:tooltip><strong>Command:</strong><br />'.$cmd['commandexec'].'</s:tooltip></span></p>';
						echo '<p><strong>First Added:</strong><br />';
						echo '<span>'.date("d M Y @ H:i:s",strtotime($cmd['timestamp_added'])).'<s:tooltip><strong>First Added:</strong><br />'.date("d M Y @ H:i:s",strtotime($cmd['timestamp_added'])).'</s:tooltip></span></p>';
						echo '<p><strong>Last Execution:</strong><br />';
						echo '<span>'.date("d M Y @ H:i:s",strtotime($cmd['timestamp_done'])).'<s:tooltip><strong>Last Execution:</strong><br />'.date("d M Y @ H:i:s",strtotime($cmd['timestamp_done'])).'</s:tooltip></span></p>';
						echo '<p><strong>Status:</strong><br />';

						switch ($cmd['status']) {
						case 1:
						   echo '<span>Pending<s:tooltip><strong>Status:</strong><br />Pending</s:tooltip></span></p>';
						   break;
						case 2:
						   echo '<span>Active<s:tooltip><strong>Status:</strong><br />Active</s:tooltip></span></p>';
						   break;
						case 3:
						   echo '<span>Completed<s:tooltip><strong>Status:</strong><br />Completed</s:tooltip></span></p>';
						   break;
						case 4:
						   echo '<span>Recurring<s:tooltip><strong>Status:</strong><br />Recurring</s:tooltip></span></p>';
						   break;
						case 5:
						   echo '<span>Cancelled<s:tooltip><strong>Status:</strong><br />Cancelled</s:tooltip></span></p>';
						   break;
						}
					}
					echo "</div>\n";
				}
	        }

	        if ($do_function == "cmd_output_detail"){
	        	$complex_sql = "SELECT * FROM queue INNER JOIN command_list ON (queue.command_id = command_list.command_id) INNER JOIN queue_output ON (queue.id = queue_output.queue_id) INNER JOIN command_groups ON (command_list.group_id = command_groups.group_id) WHERE (queue.id = ".$itemid.")  AND (queue.client_id = ".$site_user_id.")";

	        	$cmds = $data->db->GetAll($complex_sql);
				$cmds_total = count($cmds);
				if ($cmds_total >= 1){
					header("Content-Type: text/xml; charset=UTF-8");
					echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
					echo "<div xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:b=\"http://www.backbase.com/b\" xmlns:s=\"http://www.backbase.com/s\">";
					echo '<b:navpanel style="width: 100%; height: 100%">';
					foreach($cmds AS $i => $cmd){
						// remove visual
						$cmdshow = str_replace("%%host%%","<i>host</i>",$cmd['command']);

						echo '<b:navpanelhead>'.htmlspecialchars($cmd['group_name']).' - '.htmlspecialchars($cmd['command_desc']).' @ '.date("d M Y @ H:i:s",strtotime($cmd['time_stampt'])).'</b:navpanelhead>';
						echo '<b:navpanelbody><pre>'.htmlspecialchars($cmd['output']).'</pre>';
						echo '</b:navpanelbody>';
					}
					echo '</b:navpanel>';
					echo "</div>\n";
				}
	        }


	        if ($do_function == "tasklist"){
				header("Content-Type: text/xml; charset=UTF-8");
				echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
				echo "<div xmlns=\"http://www.w3.org/1999/xhtml\" xmlns:b=\"http://www.backbase.com/b\" xmlns:s=\"http://www.backbase.com/s\">\n";

				echo '<b:treelist style="width:100%;" b:textselect="false">
					<b:treelistrow>
						<b:treelistcell b:type="head" class="tasklist-head">Command</b:treelistcell>
						<b:treelistcell b:type="head" class="tasklist-head">Cnt</b:treelistcell>
						<b:treelistcell b:type="head" class="tasklist-head">Timestamp</b:treelistcell>
					</b:treelistrow>';

				$sql_qry = "SELECT command_groups.group_name, queue.status, queue.host_name, queue.command, queue.recurring_cnt, queue.timestamp_done, queue.id FROM queue INNER JOIN command_list ON (queue.command_id = command_list.command_id) INNER JOIN command_groups ON (command_list.group_id = command_groups.group_id) WHERE (queue.client_id = ".$itemid.")";

				$active_cmds = $data->db->GetAll($sql_qry);
				$active_cmds_total = count($active_cmds);
				if ($active_cmds_total >= 1){
					foreach($active_cmds AS $i => $cmd){

						if ($cmd['status'] == "2") {
							$date_ts = "Presently executing...";
						} elseif ($cmd['timestamp_done'] == "0000-00-00 00:00:00"){
							$date_ts = "Waiting execution...";
						} else {
							$date_ts = date("d M Y @ H:i:s", strtotime($cmd['timestamp_done']));
						}
/*
						echo '<b:treelistrow b:behavior="fx-main-color">
							  <s:event b:on="dblclick">
									<s:task b:action="load" b:url="main.php?process=window&amp;id=window_loader&amp;cid='.$cmd['id'].'" b:test="not(id(\'window_loader_'.$cmd['id'].'\'))" b:destination="id(\'windowarea\')" b:mode="aslastchild" />
									<s:task b:action="trigger" b:event="open" b:target="id(\'window_loader_'.$cmd['id'].'\')" />
									<s:task b:action="focus" b:event="open" b:target="id(\'window_loader_'.$cmd['id'].'\')" />
							  </s:event>
						      <b:treelistcell>'.$cmd['group_name'].' on '.$cmd['host_name'].'</b:treelistcell>
						      <b:treelistcell>'.$cmd['recurring_cnt'].'</b:treelistcell>
						      <b:treelistcell>'.$date_ts.'</b:treelistcell>';
*/
						echo '<b:treelistrow b:behavior="fx-main-color">
							  <s:event b:on="dblclick">
									<s:task b:action="load" b:url="main.php?process=window&amp;id=window_cmd_detail&amp;cid='.$cmd['id'].'" b:test="not(id(\'window_cmddetail_'.$cmd['id'].'\'))" b:destination="id(\'windowarea\')" b:mode="aslastchild" />
									<s:task b:action="trigger" b:event="open" b:target="id(\'window_cmddetail_'.$cmd['id'].'\')" />
									<s:task b:action="focus" b:event="open" b:target="id(\'window_cmddetail_'.$cmd['id'].'\')" />
							  </s:event>
						      <b:treelistcell>'.$cmd['group_name'].' on '.$cmd['host_name'].'</b:treelistcell>
						      <b:treelistcell>'.$cmd['recurring_cnt'].'</b:treelistcell>
						      <b:treelistcell>'.$date_ts.'</b:treelistcell>';


						$sub_cmds = $data->db->GetAll("SELECT queue_output.output_id, queue_output.time_stampt, queue.id, queue.command_id FROM queue_output INNER JOIN queue ON (queue_output.queue_id = queue.id) WHERE (queue.client_id = ".$itemid." AND queue.id = ".$cmd['id'].")");
						$sub_cmds_total = count($sub_cmds);
						if ($sub_cmds_total >= 1){

							echo "<b:treelistchildren>\n";
							foreach($sub_cmds AS $i => $subcmd){
						         echo "<b:treelistrow b:behavior=\"fx-color\">\n";
						         echo "   <b:treelistcell>".$cmd['command']."</b:treelistcell>\n";
						         echo "   <b:treelistcell>".($i+1)."</b:treelistcell>";
						         echo "   <b:treelistcell>".date("d M Y @ H:i:s", strtotime($subcmd['time_stampt']))."</b:treelistcell>\n";
						         echo "</b:treelistrow>\n";
							}
							echo "</b:treelistchildren>\n";
						}
						echo '</b:treelistrow>';
					}
				}
				echo "</b:treelist>\n</div>\n";
	        }



	        /* *********************************************************** */
	        if ($do_function == "domain_whois"){
				$domain = trim($_REQUEST['domain']);

				$dot = strpos($domain, '.');
				$sld = substr($domain, 0, $dot);
				$tld = substr($domain, $dot+1);

				if (!is_null($dot) && !is_null($sld) && !is_null($tld)){
					$whois = getwhois($sld, $tld);

					echo "<pre>";
					echo $whois;
					echo "</pre>";
				}
	        }
		}
	} else {
		// catches poeple trying to cheat by modifying the session data -
		sysErrorAlert("Incorrect Username, Password or Blocked Account.  Please try again", "document.location.href='index.php?msg=Incorrect Username, Password or Blocked Account. Please try again'");
	}
} else {
	// standard redirect if the session does not exist
	sysErrorAlert("Session expired, please login again.", "document.location.href='index.php?msg=Session expired, please login again.'");
}

?>