<?php
/**
 * @version		0.9
 * @author      Carlos Souza
 * @copyright   Copyright (c) 2005 Carlos Souza <csouza@web-sense.net>
 * @package     PHPGettext
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @link		http://phpgettext.web-sense.net
 */


define('_MODE_MO_', 'mo');
define('_MODE_PO_', 'po');
define('_MODE_POT_','pot');

require_once('phpgettext.message.php');
class PHPGettext_Catalog
{
    var $mode = _MODE_MO_;
    var $name = '';
    var $path = '';
    var $lang = 'en';
    var $charset = 'utf-8';
    var $category = 'LC_MESSAGES';
    var $comments = array();
    var $headers = array();
    var $strings = array();
    function PHPGettext_Catalog($name, $path)
    {
        $this->name = trim($name);
        $this->path = trim($path);
    }

    function load()
    {
        switch ($this->mode)
        {
            case _MODE_MO_:
            return $this->_readMO();
            break;
            case _MODE_PO_:
            return $this->_readPO();
            break;
            case _MODE_POT_:
            $category = $this->setproperty('category');
            $lang = $this->setproperty('lang');
            $ret = $this->_readPO();
            $this->setproperty('category', $category);
            $this->setproperty('lang', $lang);
            return $ret;
            break;
            default:
            trigger_error('Unrecognized mode in '.__CLASS__.'->'.__FUNCTION__, E_USER_ERROR);
            return false;
            break;
        }
    }

    function save()
    {

        $langdir = $this->path.DIRECTORY_SEPARATOR.$this->lang;
        if (!is_dir($langdir)) {
            $this->createdir($langdir.DIRECTORY_SEPARATOR.$this->category);
        }

        switch ($this->mode)
        {
            case _MODE_MO_:
            #if ($gettext->has_gettext) return $gettext->msgfmt($this->name, $langdir);
            return $this->_writeMO();
            break;
            case _MODE_PO_:
            case _MODE_POT_:
            return $this->_writePO();
            break;
            default:
            trigger_error('Unrecognized mode in '.__CLASS__.'->'.__FUNCTION__, E_USER_ERROR);
            return false;
            break;
        }
    }


    function filename()
    {
        $return = $this->path.DIRECTORY_SEPARATOR;
        $return .= !empty($this->lang) ? $this->lang.DIRECTORY_SEPARATOR : '';
        $return .= (!empty($this->category) && $this->mode != _MODE_PO_) ? $this->category.DIRECTORY_SEPARATOR : '';
        $return .= $this->name.'.'.$this->mode;
        return  $return;
    }

    function createdir($path)
    {
        if (!file_exists($path))
        {
            // The directory doesn't exist.  Recurse, passing in the parent
            // directory so that it gets created.
            $this->createdir(dirname($path));
            mkdir($path, 0777);
        }
    }


    function setproperty($property, $value = null) {
        $return = $this->$property;
        $this->$property = $value;
        return $return;
    }
    function getproperty($property) {
        return $this->$property;
    }
    function addentry($msgid, $msgid_plural=null, $msgstr=null, $comments=array())
    {
        $entry =  new PHPGettext_Message($msgid, $msgid_plural);
        if (!is_null($msgstr)) $entry->setmsgstr($msgstr);
        if (!empty($comments)) $entry->setcomments($comments);
        $this->strings[$msgid] = $entry;
    }

    function _writePO()
    {
        $file = $this->filename();

        // open PO file
        if (!is_resource($res = @fopen($file, 'w'))) {
            trigger_error("Cannot create '$file'. ", E_USER_WARNING);
        }
        // lock PO file exclusively
        if (!@flock($res, LOCK_EX)) {
            @fclose($res);
            trigger_error("Cannot lock '$file'. ", E_USER_WARNING);
        }
        // write comments
        if (count($this->comments > 0)) {
            foreach ($this->comments as $line) {
                fwrite($res, trim($line)."\n");
            }
        }
        // write meta info
        if (count($this->headers > 0)) {
            $header = 'msgid ""' . "\nmsgstr " . '""' . "\n";
            foreach ($this->headers as $k => $v) {
                $header .= '"' . $k . ': ' . $v . '\n"' . "\n";
            }
            fwrite($res, $header . "\n");
        }

        // write strings
        if (count($this->strings > 0)) {
            foreach ($this->strings as $string) {
                fwrite($res, $string->toString());
            }
        }
        //done
        @flock($res, LOCK_UN);
        @fclose($res);
        return true;
    }

    function _readPO()
    {
        $file = $this->filename();
        if (!is_readable($file)) {
            trigger_error('Gettext Catalog '.$this->filename().' not found', E_USER_WARNING);
            return false;
        }
        // load file
        if (!$data = @file($file)) {
            trigger_error('Gettext Catalog '.$this->filename().' not found', E_USER_WARNING);
            return false;
        }
        $count      = 0;
        $comments   = '';
        $is_fuzzy   = false;
        $nplural    = false;

        // get all strings
        foreach ($data as $line) {
            // comments
            if (strncmp($line, "#", 1) == 0) {
                if ($count < 1) {
                    $this->comments[] = $line;
                } else {
                    if (strncmp($line, "#,", 2) == 0 && preg_match('/fuzzy/', $line)) {
                        unset($line);
                        $is_fuzzy = true;
                    }
                    $comments[] = $line;
                }
            }// msgid
            elseif (preg_match('/^msgid\s*"(.*)"\s*|^msgid_plural\s*"(.*)"\s*/s', $line, $matches)) {
                if (preg_match('/^msgid_plural\s*/s', $line, $arr)) {
                    $nplural = true;
                    $strings[$count]['msgid_plural'] = $matches[2];
                } else {
                    $count++;
                    $strings[$count]['comments'] = $comments;
                    $strings[$count]['msgid']    = '';
                    $strings[$count]['msgid_plural']    = '';
                    $strings[$count]['msgstr']   = '';
                    $strings[$count]['is_fuzzy'] = $is_fuzzy;
                    if (!empty($matches[1])) {
                        $strings[$count]['msgid'] = $matches[1];
                    }
                    unset($msgstr);
                    unset($comments);
                    $nplural  = false;
                    $is_fuzzy = false;

                }
            } // msgstr
            elseif  (preg_match('/^msgstr\s*"(.*)"\s*|^msgstr\[[0-9]\]\s*"(.*)"\s*/s', $line, $matches)) {
                $msgstr = true;
                if ($nplural) {
                    $strings[$count]['msgstr'][] = $matches[2];
                } else {
                    $strings[$count]['msgstr'] = $matches[1];
                }
            } // multiline msgid or msgstr
            elseif (preg_match('/^"(.*)"\s*$/s', $line, $matches)) {
                // headers
                if (isset($msgstr) && $count == 1) {
                    list($key, $value) = explode(':', $matches[1], 2);
                    $this->headers[$key] = $value;
                }
                elseif (isset($msgstr) && $count > 1) {
                    $strings[$count]['msgstr'] .= $matches[1];
                }
                else { // msgid
                    $strings[$count]['msgid']  .= $matches[1];
                }
            }
        }

        // load the strings
        array_shift($strings);
        for ($a=0; $a < count($strings); $a++) {
            $this->strings[$a] = new PHPGettext_Message($strings[$a]['msgid'], $strings[$a]['msgid_plural']);
            $this->strings[$a]->setmsgstr($strings[$a]['msgstr']);
            $this->strings[$a]->setfuzzy($strings[$a]['is_fuzzy']);
            $this->strings[$a]->setcomments($strings[$a]['comments']);
        }
        return true;
    }

    function _writeMO()
    {
        $file = $this->filename();

        // open MO file
        if (!is_resource($res = @fopen($file, 'w'))) {
            trigger_error("Cannot create '$file'. ", E_USER_WARNING);
        }
        // lock MO file exclusively
        if (!@flock($res, LOCK_EX)) {
            @fclose($res);
            trigger_error("Cannot lock '$file'. ", E_USER_WARNING);
        }

        // get the headers
        foreach ($this->headers as $key => $val) {
            $headers .= $key . ': ' . $val . "\n";
        }
        $strings[] = array('msgid' => "", 'msgstr' => $headers);

        // don't write fuzzy entries
        foreach ($this->strings as $message) {
            if (!$message->is_fuzzy) {
                $strings[] = array('msgid' => $message->msgid,'msgid_plural' => $message->msgid_plural, 'msgstr' => $message->msgstr);
            }

        }

        $count = count($strings);
        fwrite($res, pack('L', (int) 0x950412de));  // magic number
        fwrite($res, pack('L', 0));                 // revision  0
        fwrite($res, pack('L', $count));            // N - number of strings
        $offset = 28;
        fwrite($res, pack('L', $offset));           // O - offset of table with original strings
        $offset += ($count * 8);
        fwrite($res, pack('L', $offset));           // T - offset of table with translation strings
        fwrite($res, pack('L', 0));                 // S - size of hashing table (set to 0 to omit the table)
        $offset += ($count * 8);
        fwrite($res, pack('L', $offset));           // H - offset of hashing table

        // offsets for original strings
        for ($a=0; $a<$count; $a++) {
            if (isset($strings[$a]['msgid_plural'])) { // plurals
                $strings[$a]['msgid'] = $strings[$a]['msgid'] ."\0".$strings[$a]['msgid_plural'];
            }
            $len = strlen($strings[$a]['msgid']);
            fwrite($res, pack('L', $len));
            fwrite($res, pack('L', $offset));
            $offset += $len + 1;
        }

        // offsets for translated strings
        for ($a=0; $a<$count; $a++) {
            if (is_array($strings[$a]['msgstr'])) { // plurals
                $strings[$a]['msgstr'] = implode("\0", $strings[$a]['msgstr']);
            }
            $len = strlen($strings[$a]['msgstr']);
            fwrite($res, pack('L', $len));
            fwrite($res, pack('L', $offset));
            $offset += $len + 1;
        }

        // write original strings
        foreach ($strings as $str) {
            fwrite($res, $str['msgid'] . "\0");
        }
        // write translated strings
        foreach ($strings as $str) {
            fwrite($res, $str['msgstr'] . "\0");
        }
        // done
        @flock($res, LOCK_UN);
        @fclose($res);
        return true;
    }


    function _readMO()
    {
        $file = $this->filename();
        if (!file_exists($file)) return false;

        //  read in data file completely
        $f = fopen($file, "rb");
        $data = fread($f, 1<<20);
        fclose($f);

        //  extract header fields and check file magic
        if ($data) {
            $header = substr($data, 0, 20);
            $header = unpack("L1magic/L1version/L1count/L1o_msg/L1o_trn", $header);
            extract($header);
            if ((dechex($magic) == "950412de") && ($version == 0)) {
                //  fetch all strings
                for ($a=0; $a<$count; $a++) {
                    //  msgid
                    $r = unpack("L1len/L1offs", substr($data, $o_msg + $a * 8, 8));
                    $msgid = substr($data, $r["offs"], $r["len"]);
                    unset($msgid_plural);
                    if (strpos($msgid, "\0")) { // plurals
                        list($msgid, $msgid_plural) = explode("\0", $msgid);
                    }
                    //  msgstr
                    $r = unpack("L1len/L1offs", substr($data, $o_trn + $a * 8, 8));
                    $msgstr = substr($data, $r["offs"], $r["len"]);
                    if (isset($msgid_plural)) { // plurals
                        $msgstr = explode("\0", $msgstr);
                    }
                    $strings[$a]['msgid'] = $msgid;
                    $strings[$a]['msgstr'] = $msgstr;
                    $strings[$a]['msgid_plural'] = isset($msgid_plural) ? $msgid_plural : '';
                }
                if (!empty($strings[0]['msgstr'])){ // header
                    $str = explode("\n", $strings[0]['msgstr']);
                    foreach ($str as $s){
                        if (!empty($s)) {
                            list($key, $value) = explode(':', $s, 2);
                            $this->headers[$key] = $value;
                        }
                    }
                }
                // load the strings
                array_shift($strings);
                for ($a=0; $a < count($strings); $a++) {
                    $this->strings[$a] = new PHPGettext_Message($strings[$a]['msgid'], $strings[$a]['msgid_plural']);
                    $this->strings[$a]->setmsgstr($strings[$a]['msgstr']);
                }
                return true;
            }
        }
        return false;
    }


}
?>