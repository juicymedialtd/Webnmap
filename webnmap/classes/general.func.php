<?
/**
* @package Framework Application - General Functions
* @copyright (C) 2005 - 2006 Juicy Media Ltd.
* @license http://www.juicymedia.co.uk/
* @author Peter Davies <peter [DOT] davies [AT] juicymedia [DOT] co [DOT] uk>
* @version 1.0.1
*
**/

/** ensure this file is being included by a parent file */
defined( '_VALID_SEO' ) or die( 'Direct Access to this location is not allowed.' );

include('whois.class.php');
include('phpmailer/class.phpmailer.php');

/**
* Function to create a mail object for futher use (uses phpMailer)
* @param string From e-mail address
* @param string From name
* @param string E-mail subject
* @param string Message body
* @return object Mail object
*/
function gdCreateMail( $from='', $fromname='', $subject, $body ) {
	global $gd_docroot;

	$mail = new mosPHPMailer();

	$mail->PluginDir = 'phpmailer/';
	$mail->SetLanguage( 'en', 'phpmailer/language/' );
	$mail->CharSet 	= substr_replace(_ISO, '', 0, 8);
	$mail->IsMail();
	$mail->From 	= $from;
	$mail->FromName = $fromname;
	$mail->Mailer 	= 'mail';
	$mail->Subject 	= $subject;
	$mail->Body 	= $body;

	return $mail;
}

/**
* Mail function (uses phpMailer)
* @param string From e-mail address
* @param string From name
* @param string/array Recipient e-mail address(es)
* @param string E-mail subject
* @param string Message body
* @param boolean false = plain text, true = HTML
* @param string/array CC e-mail address(es)
* @param string/array BCC e-mail address(es)
* @param string/array Attachment file name(s)
*/
function gdMail($from, $fromname, $recipient, $subject, $body, $mode=0, $cc=NULL, $bcc=NULL, $attachment=NULL ) {
	global $gd_debug;
	$mail = gdCreateMail( $from, $fromname, $subject, $body );

	// activate HTML formatted emails
	if ( $mode ) {
		$mail->IsHTML(true);
	}

	if( is_array($recipient) ) {
		foreach ($recipient as $to) {
			$mail->AddAddress($to);
		}
	} else {
		$mail->AddAddress($recipient);
	}
	if (isset($cc)) {
	    if( is_array($cc) )
	        foreach ($cc as $to) $mail->AddCC($to);
	    else
	        $mail->AddCC($cc);
	}
	if (isset($bcc)) {
	    if( is_array($bcc) )
	        foreach ($bcc as $to) $mail->AddCC($to);
	    else
	        $mail->AddCC($bcc);
	}
    if ($attachment) {
        if ( is_array($attachment) )
            foreach ($attachment as $fname) $mail->AddAttachment($fname);
        else
            $mail->AddAttachment($attachment);
    } // if
	$mailssend = $mail->Send();

	return $mailssend;
}

function SnowCheckMail($Email,$Debug=false)
{
    global $HTTP_HOST;
    $Return =array();
    // Variable for return.
    // $Return[0] : [true|false]
    // $Return[1] : Processing result save.

    if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $Email)) {
        $Return[0]=false;
        $Return[1]="${Email} is E-Mail form that is not right.";
        if ($Debug) echo "Error : {$Email} is E-Mail form that is not right.<br>";
        return $Return;
    }
    else if ($Debug) echo "Confirmation : {$Email} is E-Mail form that is not right.<br>";

    // E-Mail @ by 2 by standard divide. if it is $Email this "lsm@ebeecomm.com"..
    // $Username : lsm
    // $Domain : ebeecomm.com
    // list function reference : http://www.php.net/manual/en/function.list.php
    // split function reference : http://www.php.net/manual/en/function.split.php
    list ( $Username, $Domain ) = split ("@",$Email);

    // That MX(mail exchanger) record exists in domain check .
    // checkdnsrr function reference : http://www.php.net/manual/en/function.checkdnsrr.php
    if ( checkdnsrr ( $Domain, "MX" ) )  {
        if($Debug) echo "Confirmation : MX record about {$Domain} exists.<br>";
        // If MX record exists, save MX record address.
        // getmxrr function reference : http://www.php.net/manual/en/function.getmxrr.php
        if ( getmxrr ($Domain, $MXHost))  {
      if($Debug) {
                echo "Confirmation : Is confirming address by MX LOOKUP.<br>";
              for ( $i = 0,$j = 1; $i < count ( $MXHost ); $i++,$j++ ) {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Result($j) - $MXHost[$i]<BR>";
        }
            }
        }
        // Getmxrr function does to store MX record address about $Domain in arrangement form to $MXHost.
        // $ConnectAddress socket connection address.
        $ConnectAddress = $MXHost[0];
    }
    else {
        // If there is no MX record simply @ to next time address socket connection do .
        $ConnectAddress = $Domain;
        if ($Debug) echo "Confirmation : MX record about {$Domain} does not exist.<br>";
    }

    // fsockopen function reference : http://www.php.net/manual/en/function.fsockopen.php
    $Connect = fsockopen ( $ConnectAddress, 25 );

    // Success in socket connection
    if ($Connect)
    {
        if ($Debug) echo "Connection succeeded to {$ConnectAddress} SMTP.<br>";
        // Judgment is that service is preparing though begin by 220 getting string after connection .
        // fgets function reference : http://www.php.net/manual/en/function.fgets.php
        if ( ereg ( "^220", $Out = fgets ( $Connect, 1024 ) ) ) {

            // Inform client's reaching to server who connect.
            fputs ( $Connect, "HELO $HTTP_HOST\r\n" );
                if ($Debug) echo "Run : HELO $HTTP_HOST<br>";
            $Out = fgets ( $Connect, 1024 ); // Receive server's answering cord.

            // Inform sender's address to server.
            fputs ( $Connect, "MAIL FROM: <{$Email}>\r\n" );
                if ($Debug) echo "Run : MAIL FROM: &lt;{$Email}&gt;<br>";
            $From = fgets ( $Connect, 1024 ); // Receive server's answering cord.

            // Inform listener's address to server.
            fputs ( $Connect, "RCPT TO: <{$Email}>\r\n" );
                if ($Debug) echo "Run : RCPT TO: &lt;{$Email}&gt;<br>";
            $To = fgets ( $Connect, 1024 ); // Receive server's answering cord.

            // Finish connection.
            fputs ( $Connect, "QUIT\r\n");
                if ($Debug) echo "Run : QUIT<br>";

            fclose($Connect);

                // Server's answering cord about MAIL and TO command checks.
                // Server about listener's address reacts to 550 codes if there does not exist
                // checking that mailbox is in own E-Mail account.
                if ( !ereg ( "^250", $From ) || !ereg ( "^250", $To )) {
                    $Return[0]=false;
                    $Return[1]="${Email} format is correct but does not exist on the server.";
                    $Return[2]=false;
                    if ($Debug) echo "{$Email} format is correct but does not exist on the server.<br>";
                    return $Return;
                }
        }
    }
    // Failure in socket connection
    else {
        $Return[0]=false;
        $Return[1]="Cannot connect e-mail server ({$ConnectAddress}).";
        $Return[2]=false;
        if ($Debug) echo "Can not connect e-mail server ({$ConnectAddress}).<br>";
        return $Return;
    }
    $Return[0]=true;
    $Return[1]="{$Email} e-mail address is correct and can be contacted.";
    $Return[2]=true;
    return $Return;
}

// ***************************************************************************
// detect SQL injection techniques
// ***************************************************************************
function ToDBString($string, $link, $isNumber=false)
{
    //If $isNumber==true we are specting a number
    if($isNumber)
    {
        //A correct number must be composed of:
        // - Zero or more integers followed by a decimal point and one or more integers (i.e.: .9 (0.9) or 9.9)
        // - One or more integers followed by a decimal point. (i.e.: 9. (9.0))
        // - One or more integers (i.e.: 999)
        if(preg_match("/^\d*[\.,']\d+|\d+[\.,']|\d+$/A", $string))
        //If it's a correct number we change the colon, quote or point ("'", "," or ".") by a decimal piont.
            return preg_replace( array(
                                       "/^(\d+)[\.,']$/"     , //9.
                                       "/^(\d*)[\.,'](\d+)$/"  //.9 or 9.9
                                      ),
                                 array(
                                       "\\1."                ,
                                       "\\1.\\2"
                                      )
                                 , $string);
        else
        //If it's not a correct number we show ERROR
            die("ERROR: Not a number\"".$string."\"");
    }
    else
     //If $string is a string ($isNumber==false) we return "'$string'" correctly escaped (in this version I also strip HTML tags and modify some things in the string, change it if you wish).
     return "'".mysql_real_escape_string(htmlentities(strtoupper(trim(strip_tags($string)))), $link)."'";
}

// ***************************************************************************
// detect URL values
// ***************************************************************************
function injection_url_check(){

	$req = $_SERVER['REQUEST_URI'];
	$cadena = explode("?", $req);
	$mi_url = $cadena[0];
	$resto = $cadena[1];

	// here you can put your suspicions chains at your will. Just be careful of
	// possible coincidences with your URL's variables and parameters
	$inyecc='/script|http|<|>|%3c|%3e|SELECT|UNION|UPDATE|AND|exe|exec|INSERT|tmp/i';

	//  detecting
	if (preg_match($inyecc, $resto)) {

	   // make something, in example send an e-mail alert to administrator
	   $ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
	   $forwarded = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
	   $remoteaddress = $HTTP_SERVER_VARS["REMOTE_ADDR"];

	   $message = "attack injection in $mi_url \n\nchain: $resto \n\n
	   from: (ip-forw-RA):- $ip - $forwarded - $remoteaddress\n\n
	   --------- end --------------------";

	   mail("youremail@email.com", "Attack injection", $message,
	   "From: host@{$_SERVER['SERVER_NAME']}", "-fwebmaster@{$_SERVER['SERVER_NAME']}");

	   // message and kill execution
	   echo 'illegal url';
	   die();
	}
}

// ***************************************************************************
// this page will redirect a user, used if subdomain
// ***************************************************************************
function redirect( $page_path , $type=0, $pass_val=0 )
{

	if ($type == 0)// simple redirect
	{

		header("Location: " . $page_path);
	}
	if ($type == 1)// this redirects and sends the session info
	{

		header("Location: ". $page_path ."?" . session_name() . "=" .session_id() );

	}
	if ($type == 2)	// this redirects and sends possible url vars
	{				// pass_val in the format 'VAR=Val'


		if ($pass_val[0] == "") return -1;
		else
		{	$index = 0;

			while($pass_val[$index] != "")
			{
				if ($index == 0)
					$values = $pass_val[$index];
				else
					$values = $values . "&" . $pass_val[$index];

				$index++;

			}
		}
		header("Location: ". $page_path ."?" . $values );
	}
	if ($type == 3)	// this redirects and sends session and possible url vars
	{				// pass_val in the format 'VAR=Val'

		if ($pass_val[0] == "") return -1;
		else
		{	$index = 0;
			while($pass_val[$index] != "")
			{


				if ($index == 0)
					$values = $pass_val[$index];
				else
					$values = $values . "&" . $pass_val[$index];

				$index++;
			}
		}
		header("Location: " . $page_path . "?" . session_name() . "=" . session_id() . "&" . $values );
	}
}


	/*
	Script Name: Full Featured PHP Browser/OS detection
	Author: Harald Hope, Website: http://techpatterns.com/
	Script Source URI: http://techpatterns.com/downloads/php_browser_detection.php
	Version 4.9.9
	Copyright (C) 12 October 2005

	Special thanks to alanjstr for cleaning up the code, especially on function browser_version(), which he improved
	greatly. Also to Tapio Markula, for his initial inspiration of creating a useable php browser detector.

	This library is free software; you can redistribute it and/or
	modify it under the terms of the GNU Lesser General Public
	License as published by the Free Software Foundation; either
	version 2.1 of the License, or (at your option) any later version.

	This library is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	Lesser General Public License for more details.

	Lesser GPL license text:
	http://www.gnu.org/licenses/lgpl.txt

	Coding conventions:
	http://cvs.sourceforge.net/viewcvs.py/phpbb/phpBB2/docs/codingstandards.htm?rev=1.3
	*/

	/******************************************
	this is currently set to accept 11 parameters, although you can add as many as you want:
	1. safe - returns true/false, you can determine what makes the browser be safe lower down,
		currently it's set for ns4 and pre version 1 mozillas not being safe, plus all older browsers
	2. ie_version - tests to see what general IE it is, ie5x-6, ie4, or ieMac, returns these values.
	3. moz_version - returns array of moz version, version number (includes full version, + etc), rv number (for math
		comparison), rv number (for full rv, including alpha and beta versions), and release date
	4. dom - returns true/false if it is a basic dom browser, ie >= 5, opera >= 5, all new mozillas, safaris, konquerors
	5. os - returns which os is being used
	6. os_number - returns windows versions, 95, 98, me, nt 4, nt 5 [windows 2000], nt 5.1 [windows xp],
		Just added: os x detection[crude] otherwise returns false
	7. browser - returns the browser name, in shorthand: ie, ie4, ie5x, op, moz, konq, saf, ns4
	8. number - returns the browser version number, if available, otherwise returns '' [not available]
	9. full - returns this array: $browser_name, $version_number, $ie_version, $dom_browser,
		$safe_browser, $os, $os_number, $s_browser [the browser search string from the browser array], $type
	10. type - returns whether it's a bot or a browser
	11. math_number - returns basic version number, for math comparison, ie. 1.2rel2a becomes 1.2
	*******************************************/

	// main script, uses two other functions, which_os() and browser_version() as needed
	function browser_detection( $which_test ) {
		/*
		uncomment the global variable declaration if you want the variables to be available on a global level
		throughout your php page, make sure that php is configured to support the use of globals first!
		Use of globals should be avoided however, and they are not necessary with this script
		*/

		/*global $dom_browser, $safe_browser, $browser_user_agent, $os, $browser_name, $s_browser, $ie_version,
		$version_number, $os_number, $b_repeat, $moz_version, $moz_version_number, $moz_rv, $moz_rv_full, $moz_release;*/

		static $dom_browser, $safe_browser, $browser_user_agent, $os, $browser_name, $s_browser, $ie_version,
		$version_number, $os_number, $b_repeat, $moz_version, $moz_version_number, $moz_rv, $moz_rv_full, $moz_release,
		$type, $math_version_number;

		/*
		this makes the test only run once no matter how many times you call it
		since all the variables are filled on the first run through, it's only a matter of returning the
		the right ones
		*/
		if ( !$b_repeat )
		{
			//initialize all variables with default values to prevent error
			$dom_browser = false;
			$type = 'bot';// default to bot since you never know with bots
			$safe_browser = false;
			$os = '';
			$os_number = '';
			$a_os_data = '';
			$browser_name = '';
			$version_number = '';
			$math_version_number = '';
			$ie_version = '';
			$moz_version = '';
			$moz_version_number = '';
			$moz_rv = '';
			$moz_rv_full = '';
			$moz_release = '';
			$b_success = false;// boolean for if browser found in main test

			//make navigator user agent string lower case to make sure all versions get caught
			// isset protects against blank user agent failure
			$browser_user_agent = ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : '';

			/*
			pack the browser type array, in this order
			the order is important, because opera must be tested first, then omniweb [which has safari data in string],
			same for konqueror, then safari, then gecko, since safari navigator user agent id's with 'gecko' in string.
			note that $dom_browser is set for all  modern dom browsers, this gives you a default to use.

			array[0] = id string for useragent, array[1] is if dom capable, array[2] is working name for browser,
			array[3] identifies navigator useragent type

			Note: all browser strings are in lower case to match the strtolower output, this avoids possible detection
			errors

			Note: There are currently 5 navigator user agent types:
			bro - modern, css supporting browser.
			bbro - basic browser, text only, table only, defective css implementation
			bot - search type spider
			dow - known download agent
			lib - standard http libraries
			*/
			// known browsers, list will be updated routinely, check back now and then
			$a_browser_types[] = array( 'opera', true, 'op', 'bro' );
			$a_browser_types[] = array( 'omniweb', true, 'omni', 'bro' );// mac osx browser, now uses khtml engine:
			$a_browser_types[] = array( 'msie', true, 'ie', 'bro' );
			$a_browser_types[] = array( 'konqueror', true, 'konq', 'bro' );
			$a_browser_types[] = array( 'safari', true, 'saf', 'bro' );
			// covers Netscape 6-7, K-Meleon, Most linux versions, uses moz array below
			$a_browser_types[] = array( 'gecko', true, 'moz', 'bro' );
			$a_browser_types[] = array( 'netpositive', false, 'netp', 'bbro' );// beos browser
			$a_browser_types[] = array( 'lynx', false, 'lynx', 'bbro' ); // command line browser
			$a_browser_types[] = array( 'elinks ', false, 'elinks', 'bbro' ); // new version of links
			$a_browser_types[] = array( 'elinks', false, 'elinks', 'bbro' ); // alternate id for it
			$a_browser_types[] = array( 'links ', false, 'links', 'bbro' ); // old name for links
			$a_browser_types[] = array( 'links', false, 'links', 'bbro' ); // alternate id for it
			$a_browser_types[] = array( 'w3m', false, 'w3m', 'bbro' ); // open source browser, more features than lynx/links
			$a_browser_types[] = array( 'webtv', false, 'webtv', 'bbro' );// junk ms webtv
			$a_browser_types[] = array( 'amaya', false, 'amaya', 'bbro' );// w3c browser
			$a_browser_types[] = array( 'dillo', false, 'dillo', 'bbro' );// linux browser, basic table support
			$a_browser_types[] = array( 'ibrowse', false, 'ibrowse', 'bbro' );// amiga browser
			$a_browser_types[] = array( 'icab', false, 'icab', 'bro' );// mac browser
			$a_browser_types[] = array( 'crazy browser', true, 'ie', 'bro' );// uses ie rendering engine
			$a_browser_types[] = array( 'sonyericssonp800', false, 'sonyericssonp800', 'bbro' );// sony ericsson handheld

			// search engine spider bots:
			$a_browser_types[] = array( 'googlebot', false, 'google', 'bot' );// google
			$a_browser_types[] = array( 'mediapartners-google', false, 'adsense', 'bot' );// google adsense
			$a_browser_types[] = array( 'yahoo-verticalcrawler', false, 'yahoo', 'bot' );// old yahoo bot
			$a_browser_types[] = array( 'yahoo! slurp', false, 'yahoo', 'bot' ); // new yahoo bot
			$a_browser_types[] = array( 'yahoo-mm', false, 'yahoomm', 'bot' ); // gets Yahoo-MMCrawler and Yahoo-MMAudVid bots
			$a_browser_types[] = array( 'inktomi', false, 'inktomi', 'bot' ); // inktomi bot
			$a_browser_types[] = array( 'slurp', false, 'inktomi', 'bot' ); // inktomi bot
			$a_browser_types[] = array( 'fast-webcrawler', false, 'fast', 'bot' );// Fast AllTheWeb
			$a_browser_types[] = array( 'msnbot', false, 'msn', 'bot' );// msn search
			$a_browser_types[] = array( 'ask jeeves', false, 'ask', 'bot' ); //jeeves/teoma
			$a_browser_types[] = array( 'teoma', false, 'ask', 'bot' );//jeeves teoma
			$a_browser_types[] = array( 'scooter', false, 'scooter', 'bot' );// altavista
			$a_browser_types[] = array( 'openbot', false, 'openbot', 'bot' );// openbot, from taiwan
			$a_browser_types[] = array( 'ia_archiver', false, 'ia_archiver', 'bot' );// ia archiver
			$a_browser_types[] = array( 'zyborg', false, 'looksmart', 'bot' );// looksmart
			$a_browser_types[] = array( 'almaden', false, 'ibm', 'bot' );// ibm almaden web crawler
			$a_browser_types[] = array( 'baiduspider', false, 'baidu', 'bot' );// Baiduspider asian search spider
			$a_browser_types[] = array( 'psbot', false, 'psbot', 'bot' );// psbot image crawler
			$a_browser_types[] = array( 'gigabot', false, 'gigabot', 'bot' );// gigabot crawler
			$a_browser_types[] = array( 'naverbot', false, 'naverbot', 'bot' );// naverbot crawler, bad bot, block
			$a_browser_types[] = array( 'surveybot', false, 'surveybot', 'bot' );//
			$a_browser_types[] = array( 'boitho.com-dc', false, 'boitho', 'bot' );//norwegian search engine
			$a_browser_types[] = array( 'objectssearch', false, 'objectsearch', 'bot' );// open source search engine
			$a_browser_types[] = array( 'answerbus', false, 'answerbus', 'bot' );// http://www.answerbus.com/, web questions
			$a_browser_types[] = array( 'sohu-search', false, 'sohu', 'bot' );// chinese media company, search component
			$a_browser_types[] = array( 'iltrovatore-setaccio', false, 'il-set', 'bot' );

			// various http utility libaries
			$a_browser_types[] = array( 'w3c_validator', false, 'w3c', 'lib' ); // uses libperl, make first
			$a_browser_types[] = array( 'wdg_validator', false, 'wdg', 'lib' ); //
			$a_browser_types[] = array( 'libwww-perl', false, 'libwww-perl', 'lib' );
			$a_browser_types[] = array( 'jakarta commons-httpclient', false, 'jakarta', 'lib' );
			$a_browser_types[] = array( 'python-urllib', false, 'python-urllib', 'lib' );

			// download apps
			$a_browser_types[] = array( 'getright', false, 'getright', 'dow' );
			$a_browser_types[] = array( 'wget', false, 'wget', 'dow' );// open source downloader, obeys robots.txt

			// netscape 4 and earlier tests, put last so spiders don't get caught
			$a_browser_types[] = array( 'mozilla/4.', false, 'ns', 'bbro' );
			$a_browser_types[] = array( 'mozilla/3.', false, 'ns', 'bbro' );
			$a_browser_types[] = array( 'mozilla/2.', false, 'ns', 'bbro' );

			//$a_browser_types[] = array( '', false ); // browser array template

			/*
			moz types array
			note the order, netscape6 must come before netscape, which  is how netscape 7 id's itself.
			rv comes last in case it is plain old mozilla
			*/
			$moz_types = array( 'firebird', 'phoenix', 'firefox', 'galeon', 'k-meleon', 'camino', 'epiphany',
			'netscape6', 'netscape', 'multizilla', 'rv' );

			/*
			run through the browser_types array, break if you hit a match, if no match, assume old browser
			or non dom browser, assigns false value to $b_success.
			*/
			for ($i = 0; $i < count($a_browser_types); $i++)
			{
				//unpacks browser array, assigns to variables
				$s_browser = $a_browser_types[$i][0];// text string to id browser from array

				if (stristr($browser_user_agent, $s_browser))
				{
					// it defaults to true, will become false below if needed
					// this keeps it easier to keep track of what is safe, only
					//explicit false assignment will make it false.
					$safe_browser = true;

					// assign values based on match of user agent string
					$dom_browser = $a_browser_types[$i][1];// hardcoded dom support from array
					$browser_name = $a_browser_types[$i][2];// working name for browser
					$type = $a_browser_types[$i][3];// sets whether bot or browser

					switch ( $browser_name )
					{
						// this is modified quite a bit, now will return proper netscape version number
						// check your implementation to make sure it works
						case 'ns':
							$safe_browser = false;
							$version_number = browser_version( $browser_user_agent, 'mozilla' );
							break;
						case 'moz':
							/*
							note: The 'rv' test is not absolute since the rv number is very different on
							different versions, for example Galean doesn't use the same rv version as Mozilla,
							neither do later Netscapes, like 7.x. For more on this, read the full mozilla numbering
							conventions here:
							http://www.mozilla.org/releases/cvstags.html
							*/

							// this will return alpha and beta version numbers, if present
							$moz_rv_full = browser_version( $browser_user_agent, 'rv' );
							// this slices them back off for math comparisons
							$moz_rv = substr( $moz_rv_full, 0, 3 );

							// this is to pull out specific mozilla versions, firebird, netscape etc..
							for ( $i = 0; $i < count( $moz_types ); $i++ )
							{
								if ( stristr( $browser_user_agent, $moz_types[$i] ) )
								{
									$moz_version = $moz_types[$i];
									$moz_version_number = browser_version( $browser_user_agent, $moz_version );
									break;
								}
							}
							// this is necesary to protect against false id'ed moz'es and new moz'es.
							// this corrects for galeon, or any other moz browser without an rv number
							if ( !$moz_rv )
							{
								$moz_rv = substr( $moz_version_number, 0, 3 );
								$moz_rv_full = $moz_version_number;
								/*
								// you can use this instead if you are running php >= 4.2
								$moz_rv = floatval( $moz_version_number );
								$moz_rv_full = $moz_version_number;
								*/
							}
							// this corrects the version name in case it went to the default 'rv' for the test
							if ( $moz_version == 'rv' )
							{
								$moz_version = 'mozilla';
							}

							//the moz version will be taken from the rv number, see notes above for rv problems
							$version_number = $moz_rv;
							// gets the actual release date, necessary if you need to do functionality tests
							$moz_release = browser_version( $browser_user_agent, 'gecko/' );
							/*
							Test for mozilla 0.9.x / netscape 6.x
							test your javascript/CSS to see if it works in these mozilla releases, if it does, just default it to:
							$safe_browser = true;
							*/
							if ( ( $moz_release < 20020400 ) || ( $moz_rv < 1 ) )
							{
								$safe_browser = false;
							}
							break;
						case 'ie':
							$version_number = browser_version( $browser_user_agent, $s_browser );
							// first test for IE 5x mac, that's the most problematic IE out there
							if ( stristr( $browser_user_agent, 'mac') )
							{
								$ie_version = 'ieMac';
							}
							// this assigns a general ie id to the $ie_version variable
							elseif ( $version_number >= 5 )
							{
								$ie_version = 'ie5x';
							}
							elseif ( ( $version_number > 3 ) && ( $version_number < 5 ) )
							{
								$dom_browser = false;
								$ie_version = 'ie4';
								// this depends on what you're using the script for, make sure this fits your needs
								$safe_browser = true;
							}
							else
							{
								$ie_version = 'old';
								$dom_browser = false;
								$safe_browser = false;
							}
							break;
						case 'op':
							$version_number = browser_version( $browser_user_agent, $s_browser );
							if ( $version_number < 5 )// opera 4 wasn't very useable.
							{
								$safe_browser = false;
							}
							break;
						case 'saf':
							$version_number = browser_version( $browser_user_agent, $s_browser );
							break;
						/*
							Uncomment this section if you want omniweb to return the safari value
							Omniweb uses khtml/safari rendering engine, so you can treat it like
							safari if you want.
						*/
						/*
						case 'omni':
							$s_browser = 'safari';
							$browser_name = 'saf';
							$version_number = browser_version( $browser_user_agent, 'applewebkit' );
							break;
						*/
						default:
							$version_number = browser_version( $browser_user_agent, $s_browser );
							break;
					}
					// the browser was id'ed
					$b_success = true;
					break;
				}
			}

			//assigns defaults if the browser was not found in the loop test
			if ( !$b_success )
			{
				/*
					this will return the first part of the browser string if the above id's failed
					usually the first part of the browser string has the navigator useragent name/version in it.
					This will usually correctly id the browser and the browser number if it didn't get
					caught by the above routine.
					If you want a '' to do a if browser == '' type test, just comment out all lines below
					except for the last line, and uncomment the last line. If you want undefined values,
					the browser_name is '', you can always test for that
				*/
				// delete this part if you want an unknown browser returned
				$s_browser = substr( $browser_user_agent, 0, strcspn( $browser_user_agent , '();') );
				// this extracts just the browser name from the string
				ereg('[^0-9][a-z]*-*\ *[a-z]*\ *[a-z]*', $s_browser, $r );
				$s_browser = $r[0];
				$version_number = browser_version( $browser_user_agent, $s_browser );

				// then uncomment this part
				//$s_browser = '';//deletes the last array item in case the browser was not a match
			}
			// get os data, mac os x test requires browser/version information, this is a change from older scripts
			$a_os_data = which_os( $browser_user_agent, $browser_name, $version_number );
			$os = $a_os_data[0];// os name, abbreviated
			$os_number = $a_os_data[1];// os number or version if available

			// this ends the run through once if clause, set the boolean
			//to true so the function won't retest everything
			$b_repeat = true;

			// pulls out primary version number from more complex string, like 7.5a,
			// use this for numeric version comparison
			$m = array();
			if ( ereg('[0-9]*\.*[0-9]*', $version_number, $m ) )
			{
				$math_version_number = $m[0];
				//print_r($m);
			}

		}
		//$version_number = $_SERVER["REMOTE_ADDR"];
		/*
		This is where you return values based on what parameter you used to call the function
		$which_test is the passed parameter in the initial browser_detection('os') for example call
		*/
		switch ( $which_test )
		{
			case 'safe':// returns true/false if your tests determine it's a safe browser
				// you can change the tests to determine what is a safeBrowser for your scripts
				// in this case sub rv 1 Mozillas and Netscape 4x's trigger the unsafe condition
				return $safe_browser;
				break;
			case 'ie_version': // returns ieMac or ie5x
				return $ie_version;
				break;
			case 'moz_version':// returns array of all relevant moz information
				$moz_array = array( $moz_version, $moz_version_number, $moz_rv, $moz_rv_full, $moz_release );
				return $moz_array;
				break;
			case 'dom':// returns true/fale if a DOM capable browser
				return $dom_browser;
				break;
			case 'os':// returns os name
				return $os;
				break;
			case 'os_number':// returns os number if windows
				return $os_number;
				break;
			case 'browser':// returns browser name
				return $browser_name;
				break;
			case 'number':// returns browser number
				return $version_number;
				break;
			case 'full':// returns all relevant browser information in an array
				$full_array = array( $browser_name, $version_number, $ie_version, $dom_browser, $safe_browser,
					$os, $os_number, $s_browser, $type, $math_version_number );
				return $full_array;
				break;
			case 'type':// returns what type, bot, browser, maybe downloader in future
				return $type;
				break;
			case 'math_number':// returns numerical version number, for number comparisons
				return $math_version_number;
				break;
			default:
				break;
		}
	}

	// gets which os from the browser string
	function which_os ( $browser_string, $browser_name, $version_number  )
	{
		// initialize variables
		$os = '';
		$os_version = '';
		/*
		packs the os array
		use this order since some navigator user agents will put 'macintosh' in the navigator user agent string
		which would make the nt test register true
		*/
		$a_mac = array( 'mac68k', 'macppc' );// this is not used currently
		// same logic, check in order to catch the os's in order, last is always default item
		$a_unix = array( 'unixware', 'solaris', 'sunos', 'sun4', 'sun5', 'suni86', 'sun',
			'freebsd', 'openbsd', 'bsd' , 'irix5', 'irix6', 'irix', 'hpux9', 'hpux10', 'hpux11', 'hpux', 'hp-ux',
			'aix1', 'aix2', 'aix3', 'aix4', 'aix5', 'aix', 'sco', 'unixware', 'mpras', 'reliant',
			'dec', 'sinix', 'unix' );
		// only sometimes will you get a linux distro to id itself...
		$a_linux = array( 'kanotix', 'ubuntu', 'mepis', 'debian', 'suse', 'redhat', 'slackware', 'mandrake', 'gentoo', 'linux' );
		$a_linux_process = array ( 'i386', 'i586', 'i686' );// not use currently
		// note, order of os very important in os array, you will get failed ids if changed
		$a_os = array( 'beos', 'os2', 'amiga', 'webtv', 'mac', 'nt', 'win', $a_unix, $a_linux );

		//os tester
		for ( $i = 0; $i < count( $a_os ); $i++ )
		{
			//unpacks os array, assigns to variable
			$s_os = $a_os[$i];

			//assign os to global os variable, os flag true on success
			//!stristr($browser_string, "linux" ) corrects a linux detection bug
			if ( !is_array( $s_os ) && stristr( $browser_string, $s_os ) && !stristr( $browser_string, "linux" ) )
			{
				$os = $s_os;

				switch ( $os )
				{
					case 'win':
						if ( strstr( $browser_string, '95' ) )
						{
							$os_version = '95';
						}
						elseif ( ( strstr( $browser_string, '9x 4.9' ) ) || ( strstr( $browser_string, 'me' ) ) )
						{
							$os_version = 'me';
						}
						elseif ( strstr( $browser_string, '98' ) )
						{
							$os_version = '98';
						}
						elseif ( strstr( $browser_string, '2000' ) )// windows 2000, for opera ID
						{
							$os_version = 5.0;
							$os = 'nt';
						}
						elseif ( strstr( $browser_string, 'xp' ) )// windows 2000, for opera ID
						{
							$os_version = 5.1;
							$os = 'nt';
						}
						elseif ( strstr( $browser_string, '2003' ) )// windows server 2003, for opera ID
						{
							$os_version = 5.2;
							$os = 'nt';
						}
						elseif ( strstr( $browser_string, 'ce' ) )// windows CE
						{
							$os_version = 'ce';
						}
						break;
					case 'nt':
						if ( strstr( $browser_string, 'nt 5.2' ) )// windows server 2003
						{
							$os_version = 5.2;
							$os = 'nt';
						}
						elseif ( strstr( $browser_string, 'nt 5.1' ) || strstr( $browser_string, 'xp' ) )// windows xp
						{
							$os_version = 5.1;//
						}
						elseif ( strstr( $browser_string, 'nt 5' ) || strstr( $browser_string, '2000' ) )// windows 2000
						{
							$os_version = 5.0;
						}
						elseif ( strstr( $browser_string, 'nt 4' ) )// nt 4
						{
							$os_version = 4;
						}
						elseif ( strstr( $browser_string, 'nt 3' ) )// nt 4
						{
							$os_version = 3;
						}
						break;
					case 'mac':
						if ( strstr( $browser_string, 'os x' ) )
						{
							$os_version = 10;
						}
						//this is a crude test for os x, since safari, camino, ie 5.2, & moz >= rv 1.3
						//are only made for os x
						elseif ( ( $browser_name == 'saf' ) || ( $browser_name == 'cam' ) ||
							( ( $browser_name == 'moz' ) && ( $version_number >= 1.3 ) ) ||
							( ( $browser_name == 'ie' ) && ( $version_number >= 5.2 ) ) )
						{
							$os_version = 10;
						}
						break;
					default:
						break;
				}
				break;
			}
			// check that it's an array, check it's the second to last item
			//in the main os array, the unix one that is
			elseif ( is_array( $s_os ) && ( $i == ( count( $a_os ) - 2 ) ) )
			{
				for ($j = 0; $j < count($s_os); $j++)
				{
					if ( stristr( $browser_string, $s_os[$j] ) )
					{
						$os = 'unix'; //if the os is in the unix array, it's unix, obviously...
						$os_version = ( $s_os[$j] != 'unix' ) ? $s_os[$j] : '';// assign sub unix version from the unix array
						break;
					}
				}
			}
			// check that it's an array, check it's the last item
			//in the main os array, the linux one that is
			elseif ( is_array( $s_os ) && ( $i == ( count( $a_os ) - 1 ) ) )
			{
				for ($j = 0; $j < count($s_os); $j++)
				{
					if ( stristr( $browser_string, $s_os[$j] ) )
					{
						$os = 'lin';
						// assign linux distro from the linux array, there's a default
						//search for 'lin', if it's that, set version to ''
						$os_version = ( $s_os[$j] != 'linux' ) ? $s_os[$j] : '';
						break;
					}
				}
			}
		}

		// pack the os data array for return to main function
		$os_data = array( $os, $os_version );
		return $os_data;
	}

	// function returns browser number, gecko rv number, or gecko release date
	//function browser_version( $browser_user_agent, $search_string, $substring_length )
	function browser_version( $browser_user_agent, $search_string )
	{
		// 12 is the longest that will be required, handles release dates: 20020323; 0.8.0+
		$substring_length = 12;
		//initialize browser number, will return '' if not found
		$browser_number = '';

		// use the passed parameter for $search_string
		// start the substring slice right after these moz search strings
		// there are some cases of double msie id's, first in string and then with then number
		$start_pos = 0;
		/* this test covers you for multiple occurrences of string, only with ie though
		 with for example google bot you want the first occurance returned, since that's where the
		numbering happens */

		for ( $i = 0; $i < 4; $i++ )
		{
			//start the search after the first string occurrence
			if ( strpos( $browser_user_agent, $search_string, $start_pos ) !== false )
			{
				//update start position if position found
				$start_pos = strpos( $browser_user_agent, $search_string, $start_pos ) + strlen( $search_string );
				if ( $search_string != 'msie' )
				{
					break;
				}
			}
			else
			{
				break;
			}
		}

		// this is just to get the release date, not other moz information
		// also corrects for the omniweb 'v'
		if ( $search_string != 'gecko/' )
		{
			if ( $search_string == 'omniweb' )
			{
				$start_pos += 2;// handles the v in 'omniweb/v532.xx
			}
			else
			{
				$start_pos++;
			}
		}

		// Initial trimming
		$browser_number = substr( $browser_user_agent, $start_pos, $substring_length );

		// Find the space, ;, or parentheses that ends the number
		$browser_number = substr( $browser_number, 0, strcspn($browser_number, ' );') );

		//make sure the returned value is actually the id number and not a string
		// otherwise return ''
		if ( !is_numeric( substr( $browser_number, 0, 1 ) ) )
		{
			$browser_number = '';
		}
		//$browser_number = strrpos( $browser_user_agent, $search_string );
		return $browser_number;
	}

	/*
	Here are some typical navigator.userAgent strings so you can see where the data comes from
	Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.5) Gecko/20031007 Firebird/0.7
	Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:0.9.4) Gecko/20011128 Netscape6/6.2.1
	*/


	function browser_detect_mail(){
		$browser = array ("Wget", "EmailSiphon", "WebZIP","MSProxy/2.0","EmailWolf","webbandit","MS FrontPage");

		$punish = 0;
		while (list ($key, $val) = each ($browser)) {
			if (strstr ($HTTP_USER_AGENT, $val)) {
				$punish = 1;
			}
		}
		//Be sure to edit the e-mail address and custom page info below

		if ($punish) {
			// Email the webmaster
			$msg .= "The following session generated banned browser agent errors:\n";
			$msg .= "Host: $REMOTE_ADDR\n";
			$msg .= "Agent: $HTTP_USER_AGENT\n";
			$msg .= "Referrer: $HTTP_REFERER\n";
			$msg .= "Document: $SERVER_NAME" . $REQUEST_URI . "\n";
			$headers .= "X-Priority: 1\n";
			$headers .= "From: Ban_Bot <bot@yourdomain.com>\n";
			$headers .= "X-Sender: <bot@yourdomain.com>\n";

			mail ("webmaster@yourdomain.com", "BANNED USER ATTEMPT", $msg, $headers);

			// REDIRECT CODE
			exit;
		}
	}


	/**
	* Cleans text of all formating and scripting code
	*/
	function cleanText ( &$text ) {
		$text = preg_replace( "'<script[^>]*>.*?</script>'si", '', $text );
		$text = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text );
		$text = preg_replace( '/<!--.+?-->/', '', $text );
		$text = preg_replace( '/{.+?}/', '', $text );
		$text = preg_replace( '/&nbsp;/', ' ', $text );
		$text = preg_replace( '/&amp;/', ' ', $text );
		$text = preg_replace( '/&quot;/', ' ', $text );
		$text = strip_tags( $text );
		$text = htmlspecialchars( $text );
		return $text;
	}

	function sysErrorAlert( $text, $action='window.history.go(-1);', $mode=1 ) {
		$text = nl2br( $text );
		$text = addslashes( $text );
		$text = strip_tags( $text );

		switch ( $mode ) {
			case 2:
				echo "<script>$action</script> \n";
				break;

			case 1:
			default:
				echo "<script>alert('$text'); $action</script> \n";
				break;
		}

		exit;
	}

	/**
	* simple Javascript Cloaking
	* email cloacking
 	* by default replaces an email with a mailto link with email cloacked
	*/
	function emailCloaking( $mail, $mailto=1, $text='', $email=1 ) {
		// convert text
		$mail 		= mosHTML::encoding_converter( $mail );
		// split email by @ symbol
		$mail		= explode( '@', $mail );
		$mail_parts	= explode( '.', $mail[1] );
		// random number
		$rand	= rand( 1, 100000 );

		$replacement 	= "\n<script language='JavaScript' type='text/javascript'> \n";
		$replacement 	.= "<!-- \n";
		$replacement 	.= "var prefix = '&#109;a' + 'i&#108;' + '&#116;o'; \n";
		$replacement 	.= "var path = 'hr' + 'ef' + '='; \n";
		$replacement 	.= "var addy". $rand ." = '". @$mail[0] ."' + '&#64;' + '". implode( "' + '&#46;' + '", $mail_parts ) ."'; \n";
		if ( $mailto ) {
			// special handling when mail text is different from mail addy
			if ( $text ) {
				if ( $email ) {
					// convert text
					$text 	= mosHTML::encoding_converter( $text );
					// split email by @ symbol
					$text 	= explode( '@', $text );
					$text_parts	= explode( '.', $text[1] );
					$replacement 	.= "var addy_text". $rand ." = '". @$text[0] ."' + '&#64;' + '". implode( "' + '&#46;' + '", @$text_parts ) ."'; \n";
				} else {
					$text 	= mosHTML::encoding_converter( $text );
					$replacement 	.= "var addy_text". $rand ." = '". $text ."';\n";
				}
				$replacement 	.= "document.write( '<a ' + path + '\'' + prefix + ':' + addy". $rand ." + '\'>' ); \n";
				$replacement 	.= "document.write( addy_text". $rand ." ); \n";
				$replacement 	.= "document.write( '<\/a>' ); \n";
			} else {
				$replacement 	.= "document.write( '<a ' + path + '\'' + prefix + ':' + addy". $rand ." + '\'>' ); \n";
				$replacement 	.= "document.write( addy". $rand ." ); \n";
				$replacement 	.= "document.write( '<\/a>' ); \n";
			}
		} else {
			$replacement 	.= "document.write( addy". $rand ." ); \n";
		}
		$replacement 	.= "//--> \n";
		$replacement 	.= "</script> \n";
		$replacement 	.= "<noscript> \n";
		$replacement 	.= _CLOAKING;
		$replacement 	.= "\n</noscript> \n";

		return $replacement;
	}

	/**
	* Replaces &amp; with & for xhtml compliance
	*
	* Needed to handle unicode conflicts due to unicode conflicts
	* Deprecated - simply code the line below
	*/
	function ampReplace( $text ) {
		return preg_replace('/(&)([^#]|$)/','&amp;$2', $text);
	}

	/**
	* Makes a variable safe to display in forms
	*
	* Object parameters that are non-string, array, object or start with underscore
	* will be converted
	* @param object An object to be parsed
	* @param int The optional quote style for the htmlspecialchars function
	* @param string|array An optional single field name or array of field names not
	*                     to be parsed (eg, for a textarea)
	*/
	function MakeHtmlSafe( &$mixed, $quote_style=ENT_QUOTES, $exclude_keys='' ) {
		if (is_object( $mixed )) {
			foreach (get_object_vars( $mixed ) as $k => $v) {
				if (is_array( $v ) || is_object( $v ) || $v == NULL || substr( $k, 1, 1 ) == '_' ) {
					continue;
				}
				if (is_string( $exclude_keys ) && $k == $exclude_keys) {
					continue;
				} else if (is_array( $exclude_keys ) && in_array( $k, $exclude_keys )) {
					continue;
				}
				$mixed->$k = htmlspecialchars( $v, $quote_style );
			}
		}
	}


	/**
	* Random password generator
	* @return password
	*/
	function makePassword($length=8) {
		$salt 		= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$len 		= strlen($salt);
		$makepass	= '';
		mt_srand(10000000*(double)microtime());
		for ($i = 0; $i < $length; $i++)
			$makepass .= $salt[mt_rand(0,$len - 1)];
		return $makepass;
	}

	/**
	* smarty_prefilter_i18n()
	* This function takes the language file, and rips it into the template
	* $GLOBALS['_NG_LANGUAGE_'] is not unset anymore
	*
	* @param $tpl_source
	* @return
	**/
	function smarty_prefilter_i18n($tpl_source, &$smarty) {
		if (!is_object($GLOBALS['_NG_LANGUAGE_'])) {
			die("Error loading Multilanguage Support");
		}
		// load translations (if needed)
		$GLOBALS['_NG_LANGUAGE_']->loadCurrentTranslationTable();

		// Now replace the matched language strings with the entry in the file
		return preg_replace_callback('/##(.+?)##/', '_compile_lang', $tpl_source);
	}


	/**
	* _compile_lang
	* Called by smarty_prefilter_i18n function it processes every language
	* identifier, and inserts the language string in its place.
	*
	*/
	function _compile_lang($key) {
		return $GLOBALS['_NG_LANGUAGE_']->getTranslation($key[1]);
	}

	function server_load(){
		if ( @file_exists('/proc/loadavg') ){
		    if ( $fh = @fopen( '/proc/loadavg', 'r' ) ){
		        $data = @fread( $fh, 6 );
		        @fclose( $fh );

		        $load_avg = explode( " ", $data );

		        $load = trim($load_avg[0]);

		        //echo "proc";
		    }
		} else {
		    if ( $stats = @exec("uptime") ){
		        preg_match( "/(?:averages)?\: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/", $stats, $load );

		        $load = $load[1];
		    }
		}

		if (!$load){
		    //$load = 'Unable to determine server load';
		    $load = -1;
		}
		return $load;
	}

   function getwhois($domain, $tld)
    {
        require_once("whois.class.php");

        $whois = new Whois();

            if( !$whois->ValidDomain($domain.'.'.$tld) ){
                    return 'Sorry, the domain is not valid or not supported.';
            }

        if( $whois->Lookup($domain.'.'.$tld) )
        {
            return $whois->GetData(1);
        }else{
            return 'Sorry, an error occurred.';
        }
    }


?>