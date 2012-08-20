<?php
/**
 * @version		0.9
 * @author      Carlos Souza
 * @copyright   Copyright (c) 2005 Carlos Souza <csouza@web-sense.net>
 * @package     PHPGettext
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link		http://phpgettext.web-sense.net
 * 
 * gettext.compat.php
 *
 * comptibility file for gettext
 *
 * @author Carlos Souza
 * @package PHPGettext
 */
 
 
function _($message){	
    return gettext($message);
}
function gettext($message){	
    $gettext =& phpgettext();
    return $gettext->gettext($message);
}
function bind_textdomain_codeset($domain, $codeset){
    $gettext =& phpgettext();
    return $gettext->bind_textdomain_codeset($domain, $codeset);
}    
function bindtextdomain($domain, $directory){
    $gettext =& phpgettext();
    return $gettext->bindtextdomain($domain, $directory);
} 
function dgettext($domain, $message){
    $gettext =& phpgettext();
    return $gettext->dgettext($domain, $message);
}
function ngettext($msg1, $msg2, $count){    
    $gettext =& phpgettext();
    return $gettext->ngettext($msg1, $msg2, $count);
}
function dngettext($domain, $msg1, $msg2, $count){    
    $gettext =& phpgettext();
    return $gettext->dngettext($domain, $msg1, $msg2, $count);
}
function dcgettext($domain, $message, $category){
    $gettext =& phpgettext();
    return $gettext->dcgettext($domain, $message, $category);
} 
function dcngettext($domain, $msg1, $msg2, $count, $category){
    $gettext =& phpgettext();
    return $gettext->dcngettext($domain, $msg1, $msg2, $count, $category);
} 
function textdomain($domain = null){
    $gettext =& phpgettext();
    return $gettext->textdomain($domain);
}


?>