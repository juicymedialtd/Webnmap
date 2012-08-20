<?
/**
* @package Framework Application
* @copyright (C) 2005 - 2006 Juicy Media Ltd.
* @license http://www.juicymedia.co.uk/
* @author Peter Davies <peter [DOT] davies [AT] juicymedia [DOT] co [DOT] uk>
* @version 1.0.1
*
**/

/** ensure this file is being included by a parent file */
defined( '_VALID_SEO' ) or die( 'Direct Access to this location is not allowed.' );

// ***************************************************************************
// database configuration class
// NB: for this to work you must call both:
//        $this->sql =& new SiteLoader_SQL;
//        $this->sql->setConnection();
//    or  $this->sql->setConnection($host,$user,$pass,$dbname);
// ***************************************************************************
class SiteLoader_SQL extends ADOConnection  {
	/** @var object Database object */
    var $db = null;
    /** @var string Database type (mysql|mysqlt|mysqli) */
    var $db_type = 'mysql';
    /** @var int Cache timeout value, 3600 = 60 mins */
    var $dbcache = SESS_DB_LIFETIME;
    /** @var string The base index loading SQL code */
    var $sql_index = null;
    /** @var string The base search loading SQL code */
    var $sql_search = null;

	/**
	 * Constructor
	 */
    function SiteLoader_SQL() {
		$this->db = &ADONewConnection($this->db_type);
		$this->db->cache_secs = $this->dbcache;
		$this->db->SetFetchMode(ADODB_FETCH_ASSOC);

		// output debug info if enabled
		if (DEBUG_DATABASE == 1) {
			// enable ADOdb debug on screen - actually useful, but annoying all the same
			$this->db->debug = true;
    	}
		// output debug info if enabled
		if (LOG_DATABASE == 1) {
    		// log all sql commands, call $this->db->LogSQL(false); to stop
			$this->db->LogSQL();
    	}
    }

    /**
	 * Setup database with strings from config.php but allow passing of
	 * secondary database connection settings
	 *
     * @param string MySQL Hostname
     * @param string MySQL Username
     * @param string MySQL Password
     * @param string MySQL Database name
	 */
    function setConnection($host=DB_HOST, $user=DB_USER, $pass=DB_PASS, $dbname=DB_NAME){
		// enable a basic persistant connection
		if(!@$this->db->PConnect($host, $user, $pass, $dbname)){
			echo "Error Connecting to DB: ".$this->db->ErrorMsg();
		}
    }

    /**
     * This function sets the SQL statements for each dataset
     *
     * @param string Index SQL
     * @param string Search SQL
     */
    function setSQL($index,$search){
    	$this->sql_index = $index;
    	$this->sql_search = $search;
    }

     /**
	 * output summary of SQL logging results only if debugging enabled
	 */
    function outputSQLtimings(){
    	if (DEBUG_DATABASE == 1) {
			$perf = NewPerfMonitor($conn);
			echo $perf->SuspiciousSQL();
			echo $perf->ExpensiveSQL();
    	}
    }
}


// ***************************************************************************
// smarty extension configuration
// ***************************************************************************
class SiteLoader_Template extends Smarty {
	/** @var string Template Name */
	var $tpl_name = TPL_NAME;
    /** @var string Template Dir */
	var $tpl_dir = TPL_DIR;
	/** @var Object Template Language Management */
	var $language = null;

	/**
	 * Constructor
	 */
    function SiteLoader_Template() {
    	$this->Smarty();
        $this->template_dir = DIR_SERVER_ROOT . 'templates/themes/default';
        $this->config_dir = DIR_SERVER_ROOT . 'configs';
		$this->plugin_dir = SMARTY_DIR;
        $this->debug_tpl = SMARTY_DIR . 'debug.tpl';
    }

	/**
	 * use an overloaded Smarty fetch function to set file name of cache/compil objects
	 *
	 * @param string $_smarty_tpl_file
	 * @param string $_smarty_cache_id
	 * @param string $_smarty_compile_id
	 * @param string $_smarty_display
	 * @return function
	 */
    function fetch($_smarty_tpl_file, $_smarty_cache_id = null, $_smarty_compile_id = null, $_smarty_display = false) {
		// We need to set the cache id and the compile id so a new script will be
		// compiled for each language. This makes things really fast ;-)
		//$_smarty_compile_id = $_smarty_cache_id.'-'.$this->language->getCurrentLanguage().'-'.$_smarty_compile_id;
		$_smarty_cache_id = $_smarty_cache_id.'-'.$this->language->getCurrentLanguage().'-'.$_smarty_compile_id;
		$_smarty_compile_id = $_smarty_cache_id;

		// Now call parent method
		return parent::fetch( $_smarty_tpl_file, $_smarty_cache_id, $_smarty_compile_id, $_smarty_display );
    }

	/**
	* test to see if valid cache exists for this template
	*
	* @param string $tpl_file name of template file
	* @param string $cache_id
	* @param string $compile_id
	* @return string|false results of {@link _read_cache_file()}
	*/
	function is_cached($tpl_file, $cache_id = null, $compile_id = null)
	{
		if (!$this->caching)
		return false;


		if (!isset($compile_id)) {
			$compile_id = $this->language->getCurrentLanguage().'-'.$this->compile_id;
			$cache_id = $compile_id;
		}

		return parent::is_cached($tpl_file, $cache_id, $compile_id);
	}


    /**
     * Setup template with various caching options
     * @param boolean $compile_check
     * @param boolean $debug
     * @param boolean $cache
     */
    function initTemplate($compile_check = false, $debug = true, $cache = true, $locale = ""){
        // configure core smarty template system
        $this->compile_check = $compile_check;
		$this->debugging = DEBUG_SMARTY;

		// set the intial cache data
		$this->caching = $cache;
		$this->cache_lifetime = SESS_TPL_LIFETIME;

		// autoload the trimwhitespace filter for use on all files in site
		$this->autoload_filters = array('output' => array('trimwhitespace'));

		// multilanguage App Framework Support
		$this->language = new ngLanguage(); // create a new language object

		// load custom translation tables
		$locale = "en";
		$this->language->loadTranslationTable($locale,ROOT_DIR);
		$this->language->setCurrentLocale($locale);
		$GLOBALS['_NG_LANGUAGE_'] =& $this->language;
		$this->register_prefilter("smarty_prefilter_i18n");
		//$this->register_outputfilter("smarty_prefilter_i18n");
    }

    /**
	 * Show the template and pass a cache id which should be the domain id.
	 * This will enable multiple cache files specific to a sub-domain
     * @param int $cacheid
	 */
    function showTemplate($cacheid=null, $tmpl_nm=null){
    	$tmpl_name = (is_null($tmpl_nm))? $this->tpl_name : $tmpl_nm;
    	$this->display($tmpl_name,$cacheid);
    }

    /**
	 * set the template name and directory to load
	 * @param int $cacheid
	 */
    function setTemplate($tpl_name = TPL_NAME, $tpl_dir = TPL_DIR){
    	// determine if dir exists, load default otherwise
    	if (is_dir(DIR_SERVER_ROOT.'templates/themes/'.$tpl_dir)){
	    	$this->tpl_dir = $tpl_dir;
	        $this->tpl_name = $tpl_name;
	        $this->template_dir = DIR_SERVER_ROOT . 'templates/themes/'.$this->tpl_dir;
	        $this->compile_dir = DIR_SERVER_ROOT . 'templates/compiled/'.$this->tpl_dir;
	        $this->cache_dir = DIR_SERVER_ROOT . 'templates/cache/'.$this->tpl_dir;
    	} else {
	    	// ** These MUST exist for the site to load
    		$this->tpl_dir = TPL_DIR;
	        $this->tpl_name = TPL_NAME;
	        $this->template_dir = DIR_SERVER_ROOT . 'templates/themes/'.$this->tpl_dir;
	        $this->compile_dir = DIR_SERVER_ROOT . 'templates/compiled/'.$this->tpl_dir;
	        $this->cache_dir = DIR_SERVER_ROOT . 'templates/cache/'.$this->tpl_dir;
    	}
    }

}

// ***************************************************************************
// main site/domain configuration
// ***************************************************************************
class SiteLoader {
	/** @var object Profiler object*/
    var $profiler = null;
	/** @var object Database object*/
    var $sql = null;
	/** @var object Smarty template object*/
    var $tpl = null;
    /** @var object client of the domain*/
    var $client = null;
	/** @var object phpGACL object */
    var $gacl = null;
	/** @var string error messages*/
    var $error = null;
	/** @var string subdomain		*/
    var $_subdomain = null;
	/** @var string the main domain of the site*/
    var $_domain = null;
	/** @var string the ending 'top level domain'*/
    var $_sitetld = null;
    /** @var string the full web address including http*/
    var $_website_address = null;
    /** @var object Dataset database array of objects*/
    var $dataset_db = null;
    /** @var int Site domain id*/
    var $site_domain_id = null;
    /** @var string the website title */
    var $site_title = null;
    /** @var string the website phone number */
    var $site_contact_phone = null;
    /** @var string meta subject*/
    var $site_client_meta_sub = null;
    /** @var string meta abstract*/
    var $site_client_meta_abs = null;
    /** @var string meta description*/
    var $site_client_meta_des = null;
    /** @var string meta keywords*/
    var $site_client_meta_key = null;
    /** @var string Template directory*/
    var $site_templatedir = null;
    /** @var int Client id*/
	var $site_client_id = null;
	/** @var string Client name*/
	var $site_client_name = null;
	/** @var int Statistics boolean*/
	var $site_client_stats = null;
	/** @var int Statistics boolean*/
	var $site_items_per_page = DEFAULT_RESULTS_PER_PAGE;


	/**
	 * Constructor
	 */
    function SiteLoader(){
		// define and setup the web application profiler this should be the first
		// class loaded as it determines execution times
		if (DEBUG_PHP == 1)	$this->profiler =& new Profiler(true,true);
		else $this->profiler =& new Profiler(false,false);

		// instantiate the sql object (with default connection strings)
		$this->profiler->startTimer("Initial Db Connection");
        $this->sql =& new SiteLoader_SQL;
        $this->sql->setConnection();
        $this->profiler->stopTimer("Initial Db Connection");


        $this->profiler->startTimer("ACL System");
		$this->gacl =& new gacl();
		//$this->sql->db->GetRow()
        $this->profiler->stopTimer("ACL System");

        // determine the seperate domain entities
        $this->detectDomain();
        // get the domain details from the database
        $this->getDomainDetails();
        // get the META data from the dbase
        $this->getMetaData();
        // record the hit on the sub-domain
        $this->recordSubDomainHit();

		// instantiate the template object given we now know the template to load
		$this->profiler->startTimer("Initialize Template Engine");
        $this->tpl =& new SiteLoader_Template;

        // detect if in debug mode and if so, add smarty 'compile_check=true' to compile template if its date changes
        (DEBUG_PHP == 1)? $this->tpl->initTemplate(true,DEBUG_SMARTY,true) : $this->tpl->initTemplate(false,false,true);

        // wipe all data if correct URL passed
        if (MAINTENANCE_MODE == 'on'){
			// clear out all cache files
			//if ($_GET['clear'] == 'true') {
				$this->profiler->startTimer("Flushing all cache data");
				$this->tpl->clear_all_cache();
				$this->sql->CacheFlush();
				$this->profiler->stopTimer("Flushing all cache data");
			//}
		}
        $this->profiler->stopTimer("Initialize Template Engine");
    }

    /**
     * Automatically add the domain id to the template cache check function
     * NB: required to check that the cache objects exists
     *
     * @param string $name
     * @return boolean
     */
    function isCachedTemplate($name,$incacheid=NULL){
    	$cacheid = (is_null($incacheid))? $this->site_domain_id : $incacheid;
    	return $this->tpl->is_cached($name,$cacheid);
    }

    /**
     * show the relevant template depending on module
     *
     * @param string Name of template to load, defaults to 'index'
     */
    function display($name='index',$item=null,$other_id=null){
    	// set the basic application variables {$app_info.backbase_version}
    	$this->tpl->assign("app_info", array('version'=>PACKAGE_VERSION,
    										 'backbase_version'=>BACKBASE_VERSION,
    										 'backbase_source'=>BACKBASE_SOURCE,
    										 'admin_name'=>ADMIN_NAME,
    										 'admin_email'=>ADMIN_EMAIL));

    	// setup all META info
    	$this->tpl->assign("domain_meta", array('subject'=>$this->site_client_meta_sub,
    											'abstract'=>$this->site_client_meta_abs,
    											'description'=>$this->site_client_meta_des,
    											'keywords'=>$this->site_client_meta_key));

    	// set the main domain and client info
    	$this->tpl->assign("domain_info", array('_subdomain'=>$this->_subdomain,
    											'_domain'=>$this->_domain,
    											'_sitetld'=>$this->_sitetld,
    											'_fqdn'=>$this->_subdomain.".".$this->_domain.".".$this->_sitetld));

    	$this->tpl->assign("domain_ext_info", array('_site_domain_id'=>$this->site_domain_id,
    												'_site_templatedir'=>$this->site_templatedir,
    												'_site_title'=>$this->site_title,
    												'_site_phone'=>$this->site_contact_phone));

    	$this->tpl->assign("domain_client_info", array('_site_client_id'=>$this->site_client_id,
    												   '_site_client_name'=>$this->site_client_name,
    												   '_site_client_stats'=>$this->site_client_stats));

    	$this->tpl->assign("page_data", array('item'=>$item, 'other'=>$other_id, 'load'=>server_load()));

		// clean session data just in case its been manipulated
		$username = cleanText(strtolower($_SESSION['session_username']));

		if ($username != ""){

			// locate user in the database, must be here as its pre-checked by the initial index.php login script
			$login_detail = $this->sql->db->GetRow("SELECT * FROM site_users WHERE username = '".$username."' AND block = 0");

			// only store data as a smarty var if its found
			// bo if/else required, as this will never load if the user hasn't been authorised
			if ($login_detail){
				$this->tpl->assign("user_info", array('id'=>$login_detail['id'],
													  'name'=>$login_detail['name'],
													  'email'=>$login_detail['email'],
				                                      'username'=>$login_detail['username'],
				                                      'password'=>$login_detail['password'],
				                                      'usertype'=>$login_detail['usertype'],
				                                      'sendemail'=>$login_detail['sendEmail'],
				                                      'gid'=>$login_detail['gid'],
				                                      'registerDate'=>date("d M Y", strtotime($login_detail['registerDate'])),
				                                      'lastvisitDate'=>date("d M Y @ H:i:s", strtotime($login_detail['lastvisitDate'])),
				                                      'credits'=>$login_detail['credits']));

				$stat_pending = $this->sql->db->GetRow("SELECT count(id) AS cnt FROM queue WHERE (queue.client_id = ".$login_detail['id'].") AND (queue.`status` = 1)");
				$stat_active = $this->sql->db->GetRow("SELECT count(id) AS cnt FROM queue WHERE (queue.client_id = ".$login_detail['id'].") AND (queue.`status` = 2)");
				$stat_completed = $this->sql->db->GetRow("SELECT count(id) AS cnt FROM queue WHERE (queue.client_id = ".$login_detail['id'].") AND (queue.`status` = 3)");
				$stat_recurring = $this->sql->db->GetRow("SELECT count(id) AS cnt FROM queue WHERE (queue.client_id = ".$login_detail['id'].") AND (queue.`status` = 4)");
				$stat_cancelled = $this->sql->db->GetRow("SELECT count(id) AS cnt FROM queue WHERE (queue.client_id = ".$login_detail['id'].") AND (queue.`status` = 5)");
				$stat_total = $stat_pending['cnt']+$stat_active['cnt']+$stat_completed['cnt']+$stat_recurring['cnt']+$stat_cancelled['cnt'];

				// set the smarty data
	    		$this->tpl->assign("command_statuses", array('pending'=>$stat_pending['cnt'],
	    													 'active'=>$stat_active['cnt'],
	    													 'completed'=>$stat_completed['cnt'],
	    												     'recurring'=>$stat_recurring['cnt'],
	    												     'cancelled'=>$stat_cancelled['cnt'],
	    												     'total'=>$stat_total));

				$pending_rs = $this->sql->db->GetAll("SELECT * FROM queue WHERE (client_id = ".$login_detail['id'].")");
	    		$this->tpl->assign("command_all_list",$pending_rs);

				//$children_rs = $this->sql->db->GetAll("SELECT queue_output.output_id, queue_output.time_stampt, queue.id, queue.command_id FROM queue_output INNER JOIN queue ON (queue_output.queue_id = queue.id) WHERE (queue.client_id = ".$login_detail['id'].")");
				//$this->tpl->assign("command_children_list",$children_rs);
			}
		}

		// remove the possibility of an injection...
		// do it here as it is the last area before being used and at this stage it is unmodifyable
		$item = cleanText($item);

        // load the actual template now
    	switch ( $name ) {
    		case 'window':
    		case 'portal':
				$this->displayItem($item);
				break;
    		case 'login':
				$this->displayLogin();
				break;
    		case 'register':
				$this->displayReg();
				break;
    		case 'index':
			default:
				$this->displaySite();
				break;
		}
    }

    /**
	 * provide external connectivity to the page loader
	 */
    function displayLogin(){
    	// pass the domain id as unique cache identifier
    	$this->profiler->startTimer("Display Template: Index-Login");

        // set the base template file name (it defaults to index.tpl.php anywho)
        $this->tpl->setTemplate('index.tpl.php',$this->site_templatedir);
		//$this->tpl->
		$this->getFrontPageContent();

		// show the
        $this->tpl->showTemplate($this->site_domain_id);
        $this->profiler->stopTimer("Display Template: Index-Login");
    }

    /**
	 * provide external connectivity to the page loader
	 */
    function displayReg(){
    	// pass the domain id as unique cache identifier
    	$this->profiler->startTimer("Display Template: Index-Reg");

        // set the base template file name (it defaults to index.tpl.php anywho)
        $this->tpl->setTemplate('register.tpl.php',$this->site_templatedir);

		// show the
        $this->tpl->showTemplate($this->site_domain_id);
        $this->profiler->stopTimer("Display Template: Index-Reg");
    }

    /**
	 * provide external connectivity to the page loader
	 */
    function displaySite(){
    	// pass the domain id as unique cache identifier
    	$this->profiler->startTimer("Display Template: Main");

        // set the base template file name (it defaults to index.tpl.php anywho)
        $this->tpl->setTemplate('main.tpl.php',$this->site_templatedir);

        if(!$this->isCachedTemplate('main.tpl.php')) {
        	// get initial alternating page content
			$this->getFrontPageContent();
        }

		// show the
        $this->tpl->showTemplate($this->site_domain_id);
        $this->profiler->stopTimer("Display Template: Main");
    }

     /**
	 * provide external connectivity to the window loader
	 */
    function displayItem($item){
    	// quit if nothing is passed, and if txt too long
    	if (is_null($item)) exit();
    	if (strlen($item) >= 50) exit();

    	// pass the domain id as unique cache identifier
    	$this->profiler->startTimer("Display Template: Item");


    	$this->tpl->caching = false;

        // set the base template file name (it defaults to index.tpl.php anywho)
        $this->tpl->setTemplate($item.".tpl.php",$this->site_templatedir);

		// show the
        $this->tpl->showTemplate($this->site_domain_id);
        $this->profiler->stopTimer("Display Template: Item");
    }


	/**
	 * get the hostname now we know its not running on the local machine
	 */
    function detectDomain() {
    	$this->profiler->startTimer("Detect Domain");
    	// pick up the domain name, and rearrange to fit out needs
    	// NB:// check for URL injection, should be ok thanks to HTTP v1
		$complete_url = cleanText($_SERVER["HTTP_HOST"]);
		$exploded_url = explode(".", $complete_url);
		if (sizeof($exploded_url) == 5){
			// ignore superflous www = $exploded_url[0]
			$this->_subdomain = $exploded_url[1];
			$this->_domain = $exploded_url[2];
			$this->_sitetld = $exploded_url[3].".".$exploded_url[4];
		}elseif (sizeof($exploded_url) == 4){
			if ($exploded_url[3] == 'co') {
				$this->_subdomain = $exploded_url[1];
				$this->_domain = $exploded_url[2];
				$this->_sitetld = $exploded_url[3].".".$exploded_url[4];
			} else {
				$this->_subdomain = $exploded_url[0];
				$this->_domain = $exploded_url[1];
				$this->_sitetld = $exploded_url[2].".".$exploded_url[3];
			}
		} elseif (sizeof($exploded_url) == 3){
			$this->_subdomain = $exploded_url[0];
			if ($exploded_url[1] == 'co') {
				$this->_subdomain = '';
				$this->_domain = $exploded_url[0];
				$this->_sitetld = $exploded_url[1].".".$exploded_url[2];
			} else {
				$this->_domain = $exploded_url[1];
				$this->_sitetld = $exploded_url[2];
			}
		} elseif (sizeof($exploded_url) == 2){
			$this->_domain = $exploded_url[0];
			$this->_sitetld = $exploded_url[1];
		} else {
			// ok, all possibilities exhausted, resort to using value in config.php
			$complete_url = HOST_NAME;
			$exploded_url = explode(".", $complete_url);
			$this->_subdomain = $exploded_url[0];
			$this->_domain    = $exploded_url[1];
			$this->_sitetld   = $exploded_url[2].".".$exploded_url[3];
		}
		$this->_website_address = "http://".$this->_subdomain.".".$this->_domain.".".$this->_sitetld;
		$this->profiler->stopTimer("Detect Domain");
    }

	/**
	 * Pickup the domain from the URL, determine template etc
	 */
    function getDomainDetails(){
		$this->profiler->startTimer("Get domain from DB");

    	// select client data based on subdomain details + also published status
		$domain_base_detail = $this->sql->db->CacheGetRow("SELECT * FROM client_domains WHERE subdomain = '".$this->_subdomain."' AND domain = '".$this->_domain.".".$this->_sitetld."' AND published = 1 LIMIT 1");

		// did the above query retuen any results?
		if ($domain_base_detail) {
    		$this->site_domain_id = $domain_base_detail['domain_id'];
    		$this->site_templatedir = $domain_base_detail['template_dir'];
    		$this->site_title = $domain_base_detail['page_title'];
    		$this->site_contact_phone = $domain_base_detail['contact_num'];
    		$this->site_items_per_page = ($domain_base_detail['results_per_page'] != 0)? $domain_base_detail['results_per_page'] : DEFAULT_RESULTS_PER_PAGE;

    		// use the domain ID above to get the client name
    		$domain_client_detail = $this->sql->db->CacheGetRow("SELECT client_sites.id,name FROM client_sites INNER JOIN client_details ON (client_sites.client_id = client_details.client_id) WHERE (client_sites.domain_id = ".$this->site_domain_id." AND client_details.active = 1)");
    		if ($domain_client_detail) {
    			$this->site_client_id = $domain_client_detail['id'];
    			$this->site_client_name = $domain_client_detail['name'];
			} else {
				echo "Error performing query: ".$this->sql->db->ErrorMsg();
				exit();
			}
		} else {
			// no results returned so redirect user to default site
			$this->_subdomain = 'www';
			$this->_website_address = "http://".$this->_subdomain.".".$this->_domain.".".$this->_sitetld;

	        if (headers_sent()) echo "<script>document.location.href='$this->_website_address';</script>\n";
	        else {
	            @ob_end_clean(); // clear output buffer
	            header( "Location: $this->_website_address" );
	        }
		}
		$this->profiler->stopTimer("Get domain from DB");
    }

    /**
     * Get the META data for each site
     *
     */
    function getMetaData(){
    	$this->profiler->startTimer("Get META data from DB");
    	// use LEFT OUTER JOIN to get none existent content, should always return a row
    	$sql = "SELECT
				  domain_meta_content.text_data AS MSubject,
				  domain_meta_content1.text_data AS MAbstract,
				  domain_meta_content2.text_data AS MDescription,
				  domain_meta_content3.text_data AS MKeyword
				FROM
				  client_domains
				  LEFT OUTER JOIN domain_meta_content ON (client_domains.meta_subject_id = domain_meta_content.id)
				  LEFT OUTER JOIN domain_meta_content domain_meta_content1 ON (client_domains.meta_abstract_id = domain_meta_content1.id)
				  LEFT OUTER JOIN domain_meta_content domain_meta_content2 ON (client_domains.meta_description_id = domain_meta_content2.id)
				  LEFT OUTER JOIN domain_meta_content domain_meta_content3 ON (client_domains.meta_keywords_id = domain_meta_content3.id)
				WHERE
				  (client_domains.domain_id = ".$this->site_domain_id.")";

		// use the domain ID above to get the client name
		$domain_meta = $this->sql->db->CacheGetRow($sql);
		if ($domain_meta) {
			$this->site_client_meta_sub = $domain_meta['MSubject'];
			$this->site_client_meta_abs = $domain_meta['MAbstract'];
			$this->site_client_meta_des = $domain_meta['MDescription'];
			$this->site_client_meta_key = $domain_meta['MKeyword'];
		} else {
			//echo "Error performing query: ".$this->sql->db->ErrorMsg();
			//exit();
		}
    	$this->profiler->stopTimer("Get META data from DB");
    }

    /**
     * Get the alternating front paage content
     *
     */
    function getFrontPageContent(){
		$this->profiler->startTimer("Load subdomain content");
		// even day sql + published content and frontpage assigned
		$even_sql = "SELECT
				  domain_content.title AS title,
				  domain_content.bodytext_a AS content
				FROM
				  client_content
				  INNER JOIN domain_content ON (client_content.content_id = domain_content.id)
				WHERE
				  (client_content.client_id = ".$this->site_client_id.") AND
				  (domain_content.published = 1)";
		// odd day sql + published content and frontpage assigned
		$odd_sql = "SELECT
				  domain_content.title AS title,
				  domain_content.bodytext_b AS content
				FROM
				  client_content
				  INNER JOIN domain_content ON (client_content.content_id = domain_content.id)
				WHERE
				  (client_content.client_id = ".$this->site_client_id.") AND
				  (domain_content.published = 1)";
		// determine the day of the month in numeric format
		$day = date("j");
		if ($day % 2 == 0) {
    		// use even day sql code
    		$domain_site_content = $this->sql->db->CacheGetRow($even_sql);
		} else {
    		// use the odd day sql code 1st to determine if their content for it
    		$domain_site_content = $this->sql->db->CacheGetRow($odd_sql);
    		// if the secondary content is blank, use the first - minimise queries
    		if ($domain_site_content == '') $domain_site_content = $this->sql->db->CacheGetRow($even_sql);
		}
		// assign the content to the subdomain template
		$this->tpl->assign("domain_site_content",$domain_site_content);
    	$this->profiler->stopTimer("Load subdomain content");
    }

    function getTemplateContents(){
        return $this->tpl->fetch($this->tpl_name);
    }

    function getTemplate(){
    	return $tpl;
    }

	/**
	 * record only for web based input do we need to count hits to the clients website
	 * -> RECORD all hits on all pages, as we don't know where/how they will enter the site
	 */
    function recordSubDomainHit(){
    	$this->profiler->startTimer("Record Hit");
		// if database connected then its ok to update the domains website hits
		$ok = $this->sql->db->Execute("UPDATE client_domains SET hits = hits + 1 WHERE domain_id = ". $this->site_domain_id ."");
		$this->profiler->stopTimer("Record Hit");
    }

    /**
     * ***********************************************************************************************
	 * close all objects
	 */
    function shutdown(){
    	$this->profiler->startTimer("Shutdown");

		// output debug info if enabled
		if (DEBUG_DATABASE == 1) {
//	    	$perf = NewPerfMonitor($this->sql);
//			echo $perf->SuspiciousSQL();
//			echo $perf->ExpensiveSQL();
//	    	$perf = NULL;
//	    	unset ($perf);
		}

    	// clear all stored values
		$this->sql = NULL;
		$this->tpl = NULL;
		$this->dataset_db = NULL;
		$this->error = NULL;
		$this->gacl = NULL;

		unset ($this->sql);
		unset ($this->tpl);
		unset ($this->dataset_db);
		unset ($this->error);
		unset ($this->gacl);

		$this->profiler->stopTimer("Shutdown");

    	// output the timer info to the screen
    	// assign to smarty section?
    	(DEBUG_PROFILER == 1)? $this->profiler->printTimers() : $this->profiler = NULL;
    	//(DEBUG_PROFILER == 1)? $this->profiler->printTrace() : $this->profiler = NULL;
    }
}


?>