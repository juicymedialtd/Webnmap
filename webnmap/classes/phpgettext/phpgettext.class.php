<?php
/**
 * @version		0.9
 * @author      Carlos Souza
 * @copyright   Copyright (c) 2005 Carlos Souza <csouza@web-sense.net>
 * @package     PHPGettext
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link		http://phpgettext.web-sense.net
 * 
 * 
 */
class PHPGettext
{
    var $has_gettext;

    /**
     * The current locale. eg: en-GB
     */
    var $lang;
    /**
     * The current locale. eg: en-GB
     */
    var $locale;

    /**
     * The current domain
     */
    var $domain;

    /**
     * The current character set
     */
    var $charset;

    /**
     * Container for the loaded domains
     */
    var $text_domains = array();

    /**
     * The asssociative array of headers for the current domain
     */
    var $headers = array();
    /**
     * The asssociative array of messages for the current domain
     */
    var $messages = array();

    /**
     * The debugging flag
     */
    var $debug;

    function PHPGettext() {}



    /**
     * 
     * Set and lookup the locale from the environment variables.
     * Priority order for gettext is:
     * 1. LANGUAGE
     * 2. LC_ALL
     * 3. LC_MESSAGE
     * 4. LANG      
     *
     * @return unknown
     */

    function setlocale($lang, $locale) {
        #dump(setlocale(LC_ALL, 0));
        putenv("LANGUAGE=$lang");
        putenv("LC_ALL=$lang");
        putenv("LC_MESSAGE=$lang");
        putenv("LANG=$lang");
        /*$_ENV['LANGUAGE'] = $lang;
        $_ENV['LC_ALL'] = $lang;
        $_ENV['LC_MESSAGE'] = $lang;
        $_ENV['LANG'] = $lang;*/
        $this->lang =  $lang;
        $this->locale =  setlocale(LC_ALL, $locale);
        setlocale(LC_ALL, $locale);
    }

    function getlocale() {
        if (empty($this->locale)) {
            $langs = array( getenv('LANGUAGE'),
            getenv('LC_ALL'),
            getenv('LC_MESSAGE'),
            getenv('LANG')
            );
            foreach ($langs as $lang)
            if ($lang){
                $this->locale = $lang;
                break;
            }
        }
        return $this->locale;
    }

    /**
     * debugging function
     * 
     */
    function output($message, $untranslated = false){
        switch ($this->debug)
        {
            case 2:
            $trace = debug_backtrace();
            $html = '<span style="border-bottom: thin solid %s" title="%s(%d)">T_(%s)</span>';
            $str = sprintf($html, ($untranslated ? 'red' : 'green'), str_replace('\\', '/', $trace[2]['file']), $trace[2]['line'], $message);
            break;
            case 1:
            $str    = sprintf('%sT_(%s)',$untranslated ? '!' : '', $message);
            break;
            case 0:
            default:
            $str    = $message;
            break;
        }
        return $str;
    }

    /**
     * Alias for gettext
     * will also output the result if $output = true
     */
    function _($message, $output = false){
        $return = $this->gettext($message);
        if ($output) {
            echo $return;
            return true;
        }
        return $return;
    }

    /**
     * Lookup a message in the current domain
     * returns translation if it exists or original message
     */
    function gettext($message){
        $translation = $message;
        if ($this->has_gettext){
            $translation = gettext($message);
        }
        elseif (isset($this->messages[$this->domain][$message])) {
            $translation = $this->messages[$this->domain][$message];
        }
        $untranslated = (strcmp($translation, $message) === 0) ? true : false;
        return $this->output($translation, $untranslated);
    }

    /**
     * Override the current domain
     * The dgettext() function allows you to override the current domain for a single message lookup.
     */
    function dgettext($domain, $message){
        $translation = $message;
        if (array_key_exists($domain, $this->messages)){
            if (isset($this->messages[$domain][$message]))
                $translation = $this->messages[$domain][$message];
        }
        $untranslated = (strcmp($translation, $message) === 0) ? true : false;
        return $this->output($translation, $untranslated);
    }

    /**
     * Plural version of gettext      
     */
    function ngettext($msgid, $msgid_plural, $count){
        if ($this->has_gettext){
            $translation = ngettext($msgid, $msgid_plural, $count);
        }
        $plural = $this->getplural($count, $this->domain);
        if (isset($this->messages[$this->domain][$msgid][$plural])) {
            $translation  = $this->messages[$this->domain][$msgid][$plural];
        } else {
            $original   = array($msgid, $msgid_plural);
            $translation  = $original[$plural];
        }
        $untranslated = (strcmp($translation, $original[$plural]) === 0) ? true : false;
        return $this->output($translation, $untranslated);
    }
    /**
     * Plural version of dgettext 
     */
    function dngettext($domain, $msgid, $msgid_plural, $count){
        $original   = array($msgid, $msgid_plural);
        $plural = $this->getplural($count, $domain);
        if ($this->has_gettext){
            $translation = dngettext($domain, $msgid, $msgid_plural, $count);
        } else {
            if (isset($this->messages[$domain][$msgid][$plural])) {
                $translation  = $this->messages[$domain][$msgid][$plural];
            } else {
                $translation  = $original[$plural];
            }
        }
        $untranslated = (strcmp($translation, $original[$plural]) === 0) ? true : false;
        return $this->output($translation, $untranslated);
    }

    /**
     * Specify the character encoding in which the messages 
     * from the DOMAIN message catalog will be returned 
     *
     */
    function bind_textdomain_codeset($domain, $charset){
        if ($this->has_gettext){
            bind_textdomain_codeset($domain, $charset);
        }
        return $this->text_domains[$domain]["charset"] = $charset;
    }

    /**
     * Sets the path for a domain
     * if gettext is unavailable, translation files will be loaded here
     * 
     */
    function bindtextdomain($domain, $path){
        if ($this->has_gettext){
            bindtextdomain($domain, $path);
        } else {
            $this->load($domain, $path);
        }
        return $this->text_domains[$domain]["path"] = $path;
    }

    /**
     * Sets the default domain textdomain
     */
    function textdomain($domain = null){
        if ($this->has_gettext) {
            $domain = textdomain($domain);
        }
        elseif (!is_null($domain)) {
            $this->domain = $domain;
            $this->load($domain, $this->text_domains[$this->domain]['path']);
        }
        return $this->domain;
    }
    
    /**
     * Overrides the domain for a single lookup
     * This function allows you to override the current domain for a single message lookup. 
     * It also allows you to specify a category. 
     * Categories are folders within the languages directory  .
     * currently, only LC_MESSAGES is implemented     
     *
     *   The values for categories are:
     *   LC_CTYPE        0
     *   LC_NUMERIC      1
     *   LC_TIME         2
     *   LC_COLLATE      3
     *   LC_MONETARY     4
     *   LC_MESSAGES     5
     *   LC_ALL          6
     *
     *   not yet implemented
     */
    function dcgettext($domain, $message, $category){
        return $message;
    }

    /**
     * dcngettext -- Plural version of dcgettext
     * not yet implemented
     */
    function dcngettext($domain, $msg1, $msg2, $count, $category){
        return $msg1;
    }


    /**
     * Plural-Forms: nplurals=2; plural=n == 1 ? 0 : 1; 
     * 
     * nplurals - total number of plurals
     * plural   - the plural index
     * 
     * Plural-Forms: nplurals=1; plural=0;
     * 1 form only      
     * 
     * Plural-Forms: nplurals=2; plural=n == 1 ? 0 : 1; 
     * Plural-Forms: nplurals=2; plural=n != 1;
     * 2 forms, singular used for one only
     * 
     * Plural-Forms: nplurals=2; plural=n>1;
     * 2 forms, singular used for zero and one
     * 
     * Plural-Forms: nplurals=3; plural=n%10==1 && n%100!=11 ? 0 : n != 0 ? 1 : 2;
     * 3 forms, special case for zero
     * 
     * Plural-Forms: nplurals=3; plural=n==1 ? 0 : n==2 ? 1 : 2;
     * 3 forms, special cases for one and two
     * 
     * Plural-Forms: nplurals=4; plural=n%100==1 ? 0 : n%100==2 ? 1 : n%100==3 || n%100==4 ? 2 : 3;
     * 4 forms, special case for one and all numbers ending in 02, 03, or 04
     */
    function getplural($count, $domain) {
        if (isset($this->headers[$domain]['Plural-Forms'])) {
            $plural_forms = $this->headers[$domain]['Plural-Forms'];
            preg_match('/nplurals[\s]*[=]{1}[\s]*([\d]+);[\s]*plural[\s]*[=]{1}[\s]*(.*);$/', $plural_forms, $matches);
            $nplurals   = $matches[1];
            $plural_exp = $matches[2];
            if ($nplurals > 1 && strpos($plural_exp, ':') === false) {
                $plural =  'return ('.preg_replace('/n/', $count, $plural_exp).') ? 1 : 0;';
            } else {
                $plural = 'return '.preg_replace('/n/', $count, $plural_exp);
            }
        } else {
            $plural = 'return '.preg_replace('/n/', $count, 'n != 1 ? 1 : 0;');
        }
        return eval($plural);
    }

    function load($domain, $path) {
        require_once('phpgettext.catalog.php');
        $catalog = new PHPGettext_catalog($domain, $path);
        $catalog->setproperty('mode', _MODE_MO_);
        $catalog->setproperty('lang', $this->lang);
        $catalog->load();
        $this->headers[$domain] = $catalog->headers;
        foreach ($catalog->strings as $string)
        $this->messages[$domain][$string->msgid] = $string->msgstr;
    }
}

class PHPGettextAdmin
{

    function PHPGettextAdmin(){}

    /**
     * Enter description here...
     *
     * @param unknown_type $domain
     * @param unknown_type $langdir
     */
    function msgfmt ($domain, $langdir) {
        $cmd = "msgfmt";
        $arg = "-o $langdir/LC_MESSAGES/$domain.mo $langdir/$domain.po";
        //$exec = 'msgfmt --help';
        return $this->execute($cmd, $arg);
    }
    /**
     * Enter description here...
     *
     * @param unknown_type $domain
     * @param unknown_type $langdir
     */
    function msgmerge ($args) {
        $cmd = "msgmerge ";
        return $this->execute($cmd.$args);
    }

    /**
     * Invoke the xgettext utility with $args
     * the xgettext executable must be in PATH
     *
     * @param string the commandline arguments to gettext
     * @return unknown
     */
    function xgettext($args) {
        $cmd  = "xgettext ";
        return $this->execute($cmd.$args);
    }


    /**
     * Enter description here...
     *
     * @param unknown_type $domain
     * @param unknown_type $langdir
     */
    function execute($cmd) {

        $return = false;
        if (substr(strtoupper(PHP_OS), 0, 3) == 'WIN'){
            $outputfile = session_save_path() . DIRECTORY_SEPARATOR . md5(uniqid(rand(), true)) . ".txt";
            $fp = fopen("execute.bat", 'w+');
            fwrite($fp, "@echo on\r\n $cmd");
            fclose($fp);
            exec("execute.bat", $output, $return);
            unlink("execute.bat");
        } else {
            $cmd = "$cmd";
            $lastline = exec($cmd, $output, $return);
        }
        return $return;
    }
}

/**
 * Enter description here...
 *
 * @return unknown
 */
function &phpgettext(){
    static $gettext;
    if (is_null($gettext)) {
        require_once('phpgettext.class.php');
        $gettext = new PHPGettext();
        $gettext->has_gettext = true;
        if (!function_exists("gettext") || !function_exists("_")) {
            $gettext->has_gettext = false;
            require_once('phpgettext.compat.php');
        }
    }
    return $gettext;
}
function T_($message) {
    $gettext =& phpgettext();
    $trans = $gettext->gettext($message);
    return $trans;
}
function Tn_($msg1, $msg2, $count) {
    $gettext =& phpgettext();
    return $gettext->ngettext($msg1, $msg2, $count);
}
function Td_($domain, $message) {
    $gettext =& phpgettext();
    return $gettext->dgettext($domain, $message);
}
function Tdn_($domain, $msg1, $msg2, $count) {
    $gettext =& phpgettext();
    return $gettext->dngettext($domain, $msg1, $msg2, $count);
}
?>