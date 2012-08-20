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


class PHPGettext_Message
{
    // --- ATTRIBUTES ---
    /**
     * Short description of attribute comments
     *
     * @access public
     * @var int
     */
    var $comments = array();

    /**
     * Short description of attribute is_fuzzy
     *
     * @access public
     * @var int
     */
    var $is_fuzzy = false;

    /**
     * Short description of attribute msgid
     *
     * @access public
     * @var int
     */
    var $msgid = '';

    /**
     * Short description of attribute msgid
     *
     * @access public
     * @var int
     */
    var $msgid_plural = '';

    /**
     * Short description of attribute msgstr
     *
     * @access public
     * @var int
     */
    var $msgstr = '';

    // --- OPERATIONS ---

    /**
     * Short description of method PHPGettext_Catalog_Entry
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param void
     * @param void
     * @return void
     */
    function PHPGettext_Message($msgid = "", $msgid_plural = null)
    {
        $this->msgid = $msgid;
        $this->msgid_plural =  $msgid_plural;
    }

    /**
     * Short description of method setmsgstr
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param void
     * @return void
     */
    function setmsgstr($msgstr)
    {
        $this->msgstr = $msgstr;
    }

    /**
     * Short description of method setFuzzy
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param void
     * @return void
     */
    function setfuzzy($is_fuzzy = true)
    {
        $this->is_fuzzy = ($is_fuzzy) ? true : false;
    }

    /**
     * Short description of method setComments
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param void
     * @return void
     */
    function setcomments($comments)
    {
        if (is_array($comments))
        return $this->comments = $comments;

        return false;
    }

    /**
     * Short description of method reset
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @param void
     * @return void
     */
    function reset($property = 'all')
    {
        switch ($property)
        {
            case 'comments':
            unset($this->comments);
            break;
            case 'is_fuzzy':
            unset($this->fuzzy);
            break;
            case 'msgid':
            unset($this->msgid);
            break;
            case 'msgstr':
            unset($this->msgstr);
            break;
            case 'all':
            default:
            unset($this->comments);
            unset($this->is_fuzzy);
            unset($this->msgid);
            unset($this->msgstr);
            break;
        }
    }


    /**
     * Short description of method toString
     *
     * @access public
     * @author firstname and lastname of author, <author@example.org>
     * @return void
     */
    function toString()
    {
        $string = '';
        // comments
        if (count($this->comments > 0)) {
            foreach ($this->comments as $comment){
                if (preg_match('/fuzzy/', $comment)) {
                    $string .= trim($comment)."\n";
                }
                if (strncmp($comment, "#:", 2)   == 0) {
                    $string .= trim($comment)."\n";
                }
                // fuzzy entries
                if ($this->is_fuzzy) {
                    $string .= "#, fuzzy\n";
                }
            }
        }
        
        /*
        
        if (strlen($msgid) > 76) {
        $entry .= 'msgid ""'."\n";
        $entry .= '"'.wordwrap($msgid, 76, " \"\n\"")."\"\n";
        } else {
        $entry .= 'msgid "'.$msgid.'"'."\n";
        }
        */
        // msgid
        if (strpos($this->msgid, "\n") > 0) {
            $string .= "msgid \"\"\n";
            $msgid = explode("\n", $this->msgid);
            foreach ($msgid as $line)
                $string .= "\"$line\\n\"\n";
        } else {
            $string .= "msgid \"$this->msgid\"\n";
        }
        
        if (!empty($this->msgid_plural)) {
            $string .= "msgid_plural \"$this->msgid_plural\"\n";    
        } 
        // msgstr
        if (!is_array($this->msgstr) && strpos($this->msgstr, "\n") > 0) {
            $string .= "msgstr \"\"\n";
            $msgstr = explode("\n", $this->msgstr);
            foreach ($msgstr as $line)
                $string .= "\"$line\\n\"\n";
            $string .= "\n";
        }  // plurals
        elseif (is_array($this->msgstr)){            
            foreach ($this->msgstr as $k => $msgstr) {
                $string .= "msgstr[$k] \"$msgstr\"\n";
            }
            $string .= "\n";
        } else {
            $string .= "msgstr \"$this->msgstr\"\n\n";
        }
        return $string;
    }

} /* end of class PHPGettext_Catalog_Entry */

?>