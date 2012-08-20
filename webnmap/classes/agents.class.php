<?
/**
* @package Framework Application - Agents
* @copyright (C) 2005 - 2006 Juicy Media Ltd.
* @license http://www.juicymedia.co.uk/
* @author Peter Davies <peter [DOT] davies [AT] juicymedia [DOT] co [DOT] uk>
* @version 1.0.1
*
**/

/** ensure this file is being included by a parent file */
defined( '_VALID_SEO' ) or die( 'Direct Access to this location is not allowed.' );


class DetectAgent {

	/**
	* AWSTATS BROWSERS DATABASE
	* If you want to add a Browser to extend AWStats database detection capabilities,
	* you must add an entry in BrowsersSearchIDOrder and in BrowsersHashIDLib.
	*
	*
	* BrowsersSearchIDOrder
	* This list is used to know in which order to search Browsers IDs (Most
	* frequent one are first in this list to increase detect speed).
	* It contains all matching criteria to search for in log fields.
	* Note: Browsers IDs are in lower case and ' ' and '+' are changed into '_'
	*/

	var $browserSearchOrder = array (
	// Most frequent standard web browsers are first in this list
	"icab",
	"go!zilla",
	"konqueror",
	"links",
	"lynx",
	"omniweb",
	"opera",
	"msie 6\.0",
	"apachebench",
	"wget",
	// Other standard web browsers
	"22acidownload",
	"aol\\-iweng",
	"amaya",
	"amigavoyager",
	"aweb",
	"bpftp",
	"chimera",
	"cyberdog",
	"dillo",
	"dreamcast",
	"downloadagent",
	"ecatch",
	"emailsiphon",
	"encompass",
	"friendlyspider",
	"fresco",
	"galeon",
	"getright",
	"headdump",
	"hotjava",
	"ibrowse",
	"intergo",
	"k-meleon",
	"linemodebrowser",
	"lotus-notes",
	"macweb",
	"multizilla",
	"ncsa_mosaic",
	"netpositive",
	"nutscrape",
	"msfrontpageexpress",
	"phoenix",
	"firebird",
	"firefox",
	"safari",
	"tzgeturl",
	"viking",
	"webfetcher",
	"webexplorer",
	"webmirror",
	"webvcr",
	// Site grabbers
	"teleport",
	"webcapture",
	"webcopier",
	// Music only browsers
	"real",
	"winamp",				// Works for winampmpeg and winamp3httprdr
	"windows-media-player",
	"audion",
	"freeamp",
	"itunes",
	"jetaudio",
	"mint_audio",
	"mpg123",
	"nsplayer",
	"sonique",
	"uplayer",
	"xmms",
	"xaudio",
	// PDA/Phonecell browsers
	"alcatel",				// Alcatel
	"mot-",					// Motorola
	"nokia",				// Nokia
	"panasonic",			// Panasonic
	"philips",				// Philips
	"sonyericsson",			// SonyEricsson
	"ericsson",				// Ericsson (must be after sonyericsson
	"mmef",
	"mspie",
	"wapalizer",
	"wapsilon",
	"webcollage",
	"up\.",					// Works for UP.Browser and UP.Link
	// PDA/Phonecell I-Mode browsers
	"docomo",
	"portalmmm",
	// Others (TV)
	"webtv",
	// Other kind of browsers
	"csscheck",
	"w3m",
	"w3c_css_validator",
	"w3c_validator",
	"wdg_validator",
	"webzip",
	"staroffice",
	"mozilla",				// Must be at end because a lot of browsers contains mozilla in string
	"libwww" 			// Must be at end because some browser have both "browser id" and "libwww"
	);

	var $browsersAlias = array (
	// Common web browsers text (IE and Netscape must not be in this list)
	"icab"=>"iCab",
	"go!zilla"=>"Go!Zilla",
	"konqueror"=>"Konqueror",
	"links"=>"Links",
	"lynx"=>"Lynx",
	"omniweb"=>"OmniWeb",
	"opera"=>"Opera",
	"msie 6\.0"=>"Microsoft Internet Explorer 6.0",
	"apachebench"=>"ApacheBench",
	"wget"=>"Wget",
	"22acidownload"=>"22AciDownload",
	"aol\\-iweng"=>"AOL-Iweng",
	"amaya"=>"Amaya",
	"amigavoyager"=>"AmigaVoyager",
	"aweb"=>"AWeb",
	"bpftp"=>"BPFTP",
	"chimera"=>"Chimera",
	"cyberdog"=>"Cyberdog",
	"dillo"=>"Dillo",
	"dreamcast"=>"Dreamcast",
	"downloadagent"=>"DownloadAgent",
	"ecatch", "eCatch",
	"emailsiphon"=>"EmailSiphon",
	"encompass"=>"Encompass",
	"friendlyspider"=>"FriendlySpider",
	"fresco"=>"ANT Fresco",
	"galeon"=>"Galeon",
	"getright"=>"GetRight",
	"headdump"=>"HeadDump",
	"hotjava"=>"Sun HotJava",
	"ibrowse"=>"IBrowse",
	"intergo"=>"InterGO",
	"k-meleon"=>"K-Meleon",
	"linemodebrowser"=>"W3C Line Mode Browser",
	"lotus-notes"=>"Lotus Notes web client",
	"macweb"=>"MacWeb",
	"multizilla"=>"MultiZilla",
	"ncsa_mosaic"=>"NCSA Mosaic",
	"netpositive"=>"NetPositive",
	"nutscrape", "Nutscrape",
	"msfrontpageexpress"=>"MS FrontPage Express",
	"phoenix"=>"Phoenix",
	"firebird"=>"Mozilla Firebird",
	"firefox"=>"Mozilla Firefox",
	"safari"=>"Safari",
	"tzgeturl"=>"TzGetURL",
	"viking"=>"Viking",
	"webfetcher"=>"WebFetcher",
	"webexplorer"=>"IBM-WebExplorer",
	"webmirror"=>"WebMirror",
	"webvcr"=>"WebVCR",
	// Site grabbers
	"teleport"=>"TelePort Pro",
	"webcapture"=>"Acrobat",
	"webcopier", "WebCopier",
	// Music only browsers
	"real"=>"RealAudio or compatible (media player)",
	"winamp"=>"WinAmp (media player)",				// Works for winampmpeg and winamp3httprdr
	"windows-media-player"=>"Windows Media Player (media player)",
	"audion"=>"Audion (media player)",
	"freeamp"=>"FreeAmp (media player)",
	"itunes"=>"Apple iTunes (media player)",
	"jetaudio"=>"JetAudio (media player)",
	"mint_audio"=>"Mint Audio (media player)",
	"mpg123"=>"mpg123 (media player)",
	"nsplayer"=>"NetShow Player (media player)",
	"sonique"=>"Sonique (media player)",
	"uplayer"=>"Ultra Player (media player)",
	"xmms"=>"XMMS (media player)",
	"xaudio"=>"Some XAudio Engine based MPEG player (media player)",
	// PDA/Phonecell browsers
	"alcatel"=>"Alcatel Browser (PDA/Phone browser)",
	"ericsson"=>"Ericsson Browser (PDA/Phone browser)",
	"mot-"=>"Motorola Browser (PDA/Phone browser)",
	"nokia"=>"Nokia Browser (PDA/Phone browser)",
	"panasonic"=>"Panasonic Browser (PDA/Phone browser)",
	"philips"=>"Philips Browser (PDA/Phone browser)",
	"sonyericsson"=>"Sony/Ericsson Browser (PDA/Phone browser)",
	"mmef"=>"Microsoft Mobile Explorer (PDA/Phone browser)",
	"mspie"=>"MS Pocket Internet Explorer (PDA/Phone browser)",
	"wapalizer"=>"WAPalizer (PDA/Phone browser)",
	"wapsilon"=>"WAPsilon (PDA/Phone browser)",
	"webcollage"=>"WebCollage (PDA/Phone browser)",
	"up\."=>"UP.Browser (PDA/Phone browser)",					// Works for UP.Browser and UP.Link
	// PDA/Phonecell I-Mode browsers
	"docomo"=>"I-Mode phone (PDA/Phone browser)",
	"portalmmm"=>"I-Mode phone (PDA/Phone browser)",
	// Others (TV)
	"webtv"=>"WebTV browser",
	// Other kind of browsers
	"csscheck"=>"WDG CSS Validator",
	"w3m"=>"w3m",
	"w3c_css_validator"=>"W3C CSS Validator",
	"w3c_validator"=>"W3C HTML Validator",
	"wdg_validator"=>"WDG HTML Validator",
	"webzip"=>"WebZIP",
	"staroffice"=>"StarOffice",
	"mozilla"=>"Mozilla",
	"libwww"=>"LibWWW",
	);


	// BrowsersHashAreGrabber
	// Put here an entry for each browser in BrowsersSearchIDOrder that are grabber
	// browsers.
	//---------------------------------------------------------------------------
	var $BrowsersHereAreGrabbers = array (
	"teleport"=>"1",
	"webcapture"=>"1",
	"webcopier"=>"1",
	);


	// BrowsersHashIcon
	// Each Browsers Search ID is associated to a string that is the name of icon
	// file for this browser.
	//---------------------------------------------------------------------------
	var $BrowsersHashIcon = array (
	// Standard web browsers
	"msie"=>"msie",
	"netscape"=>"netscape",

	"icab"=>"icab",
	"go!zilla"=>"gozilla",
	"konqueror"=>"konqueror",
	"links"=>"notavailable",
	"lynx"=>"lynx",
	"omniweb"=>"omniweb",
	"opera"=>"opera",
	"wget"=>"notavailable",
	"22acidownload"=>"notavailable",
	"aol\\-iweng"=>"notavailable",
	"amaya"=>"amaya",
	"amigavoyager"=>"notavailable",
	"aweb"=>"notavailable",
	"bpftp"=>"notavailable",
	"chimera"=>"chimera",
	"cyberdog"=>"notavailable",
	"dillo"=>"notavailable",
	"dreamcast"=>"dreamcast",
	"downloadagent"=>"notavailable",
	"ecatch"=>"notavailable",
	"emailsiphon"=>"notavailable",
	"encompass"=>"notavailable",
	"friendlyspider"=>"notavailable",
	"fresco"=>"notavailable",
	"galeon"=>"galeon",
	"getright"=>"getright",
	"headdump"=>"notavailable",
	"hotjava"=>"notavailable",
	"ibrowse"=>"ibrowse",
	"intergo"=>"notavailable",
	"k-meleon"=>"kmeleon",
	"linemodebrowser"=>"notavailable",
	"lotus-notes"=>"notavailable",
	"macweb"=>"notavailable",
	"multizilla"=>"multizilla",
	"ncsa_mosaic"=>"notavailable",
	"netpositive"=>"netpositive",
	"nutscrape"=>"notavailable",
	"msfrontpageexpress"=>"notavailable",
	"phoenix"=>"phoenix",
	"firebird"=>"firebird",
	"safari"=>"safari",
	"tzgeturl"=>"notavailable",
	"viking"=>"notavailable",
	"webfetcher"=>"notavailable",
	"webexplorer"=>"notavailable",
	"webmirror"=>"notavailable",
	"webvcr"=>"notavailable",
	// Site grabbers
	"teleport"=>"teleport",
	"webcapture"=>"adobe",
	"webcopier"=>"webcopier",
	// Music only browsers
	"real"=>"mediaplayer",
	"winamp"=>"mediaplayer",				// Works for winampmpeg and winamp3httprdr
	"windows-media-player"=>"mediaplayer",
	"audion"=>"mediaplayer",
	"freeamp"=>"mediaplayer",
	"itunes"=>"mediaplayer",
	"jetaudio"=>"mediaplayer",
	"mint_audio"=>"mediaplayer",
	"mpg123"=>"mediaplayer",
	"nsplayer"=>"mediaplayer",
	"sonique"=>"mediaplayer",
	"uplayer"=>"mediaplayer",
	"xmms"=>"mediaplayer",
	"xaudio"=>"mediaplayer",
	// PDA/Phonecell browsers
	"alcatel"=>"pdaphone",				// Alcatel
	"ericsson"=>"pdaphone",				// Ericsson
	"mot-"=>"pdaphone",					// Motorola
	"nokia"=>"pdaphone",					// Nokia
	"panasonic"=>"pdaphone",				// Panasonic
	"philips"=>"pdaphone",				// Philips
	"sonyericsson"=>"pdaphone",			// Sony/Ericsson
	"mmef"=>"pdaphone",
	"mspie"=>"pdaphone",
	"wapalizer"=>"pdaphone",
	"wapsilon"=>"pdaphone",
	"webcollage"=>"pdaphone",
	"up\."=>"pdaphone",					// Works for UP.Browser and UP.Link
	// PDA/Phonecell I-Mode browsers
	"docomo"=>"pdaphone",
	"portalmmm"=>"pdaphone",
	// Others (TV)
	"webtv"=>"webtv",
	// Other kind of browsers
	"csscheck"=>"notavailable",
	"w3m"=>"notavailable",
	"w3c_css_validator"=>"notavailable",
	"w3c_validator"=>"notavailable",
	"wdg_validator"=>"notavailable",
	"webzip"=>"webzip",
	"staroffice"=>"staroffice",
	"mozilla"=>"mozilla",
	"libwww"=>"notavailable"
	);

	// TODO
	// Add Gecko category -> IE / Netscape / Gecko(except Netscape) / Other
	// IE (based on Mosaic)
	// Netscape family
	// Gecko except Netscape (Mozilla, Firebird (was Phoenix), Galeon, AmiZilla, Dino, and few others)
	// Opera (Opera 6/7)
	// KHTML (Konqueror, Safari)


	var $osSearchOrder = array (
	"windows nt 6\.0",
	"windows nt 5\.2",
	"windows nt 5\.1",
	"windows nt 5\.0",
	"winnt4\.0",
	"winnt",
	"windows 98",
	"windows 95",
	"win98",
	"win95",
	"mac os x",
	"debian",
	"freebsd",
	"linux",
	"ppc",
	"beos",
	"sunos",
	"apachebench",
	"aix",
	"irix",
	"osf",
	"hp-ux",
	"netbsd",
	"bsdi",
	"openbsd",
	"gnu",
	"unix"
	);

	var $osAlias = array (
	"windows nt 6\.0"=>"Windows Longhorn",
	"windows nt 5\.2"=>"Windows 2003",
	"windows nt 5\.0"=>"Windows 2000",
	"windows nt 5\.1"=>"Windows XP",
	"winnt"=>"Windows NT",
	"winnt 4\.0"=>"Windows NT",
	"windows 98"=>"Windows 98",
	"win98"=>"Windows 98",
	"windows 95"=>"Windows 95",
	"win95"=>"Windows 95",
	"sunos"=>"Sun Solaris",
	"freebsd"=>"FreeBSD",
	"ppc"=>"Macintosh",
	"mac os x"=>"Mac OS X",
	"linux"=>"Linux",
	"debian"=>"Debian",
	"beos"=>"BeOS",
	"winnt4\.0"=>"Windows NT 4.0",
	"apachebench"=>"ApacheBench",
	"aix"=>"AIX",
	"irix"=>"Irix",
	"osf"=>"DEC OSF",
	"hp-ux"=>"HP-UX",
	"netbsd"=>"NetBSD",
	"bsdi"=>"BSDi",
	"openbsd"=>"OpenBSD",
	"gnu"=>"GNU/Linux",
	"unix"=>"Unknown Unix system"
	);



	function DetectAgent(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = $this->GetBrowser( $agent );
        $os = $this->GetOS( $agent );
        $domain = gethostbyaddr( $_SERVER['REMOTE_ADDR'] );
        // tease out the last element of the domain
        $tldomain = split( "\.", $domain );
        $tldomain = $tldomain[count( $tldomain )-1];
        if (is_numeric( $tldomain )) {
            $tldomain = "Unknown";
        }

        $this->_db->setQuery( "SELECT count(*), type FROM #__stats_agents WHERE (agent='$browser' AND type=0) OR (agent='$os' AND type=1) OR (agent='$tldomain' AND type=2) GROUP BY type");

        $stats = $this->_db->loadObjectList();
        $sql['browser'] = "INSERT INTO #__stats_agents (agent,type) VALUES ('$browser',0)";
        $sql['os'] = "INSERT INTO #__stats_agents (agent,type) VALUES ('$os',1)";
        $sql['domain'] = "INSERT INTO #__stats_agents (agent,type) VALUES ('$tldomain',2)";
        if ($stats) foreach ($stats as $stat) {
            if ($stat->type == 0) $sql['agents'] = "UPDATE #__stats_agents SET hits=(hits+1) WHERE agent='$browser' AND type=0";
            if ($stat->type == 1) $sql['os'] = "UPDATE #__stats_agents SET hits=(hits+1) WHERE agent='$os' AND type=1";
            if ($stat->type == 2) $sql['domain'] = "UPDATE #__stats_agents SET hits=(hits+1) WHERE agent='$tldomain' AND type=2";
        }
        $this->_db->setQuery(implode('; ',$sql));
        $this->_db->query_batch();
	}


	/**
	* Checks the user agent string against known browsers
	*/
	function GetBrowser( $agent ) {

		if (preg_match( "/msie[\/\sa-z]*([\d\.]*)/i", $agent, $m )
		&& !preg_match( "/webtv/i", $agent )
		&& !preg_match( "/omniweb/i", $agent )
		&& !preg_match( "/opera/i", $agent )) {
			// IE
			return "MS Internet Explorer $m[1]";
		} else if (preg_match( "/netscape.?\/([\d\.]*)/i", $agent, $m )) {
			// Netscape 6.x, 7.x ...
			return "Netscape $m[1]";
		} else if ( preg_match( "/mozilla[\/\sa-z]*([\d\.]*)/i", $agent, $m )
		&& !preg_match( "/gecko/i", $agent )
		&& !preg_match( "/compatible/i", $agent )
		&& !preg_match( "/opera/i", $agent )
		&& !preg_match( "/galeon/i", $agent )
		&& !preg_match( "/safari/i", $agent )) {
			// Netscape 3.x, 4.x ...
			return "Netscape $m[2]";
		} else {
			// Other
			$found = false;
			foreach ($browserSearchOrder as $key) {
				if (preg_match( "/$key.?\/([\d\.]*)/i", $agent, $m )) {
					$name = "$browsersAlias[$key] $m[1]";
					return $name;
					break;
				}
			}
		}

		return 'Unknown';
	}


	/**
	* Checks the user agent string against known operating systems
	*/
	function GetOS( $agent ) {
		foreach ($osSearchOrder as $key) {
			if (preg_match( "/$key/i", $agent )) {
				return $osAlias[$key];
				break;
			}
		}

		return 'Unknown';
	}

	}



?>