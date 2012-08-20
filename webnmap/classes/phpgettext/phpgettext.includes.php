<?php

/**
 * function phpgettext()
 *
 * static function (we only need one phpgettext instance)
 * loads a PHPGettext instance
 * will detect if gettext is available and load compatibility file if it isn't
 * 
 */

class ActionHandler
{
    var $name;
    var $action;
    var $view;

    function ActionHandler($name)
    {
        $this->name  = $name;
        $this->action  = requestvar('action', '_default');

        $views = $this->name.'Views';

        if (class_exists($views)) {
            $this->view = new $views($this);
        } else {
            trigger_error("View class '$views' does not exist.", E_USER_ERROR);
        }
    }

    function run()
    {
        $method = $this->action;
        /*
        * do acl check here
        */
        if ($method && strlen($method) > 2 && method_exists($this, $method))
        return $this->$method();

        return false;
    }

    function redirect($url)
    {
        if (headers_sent()) {
            echo "<script>document.location.href='$url';</script>\n";
        } else {
            while (@ob_end_clean()); // clear output buffer
            header( "Location: $url" );
        }
        exit;
    }
    function &container($reset = false)
    {
        $name = '__com_' . $this->_name . '_container';
        if (!isset($_SESSION[$name]) || $reset) {
            $_SESSION[$name] = array();
        }
        return $_SESSION[$name];
    }

}

class phpgettextActions extends ActionHandler
{
    function _default(){
        $this->view->_default();
    }
    function view(){
        require_once('phpgettext.catalog.php');

        $mode = requestvar('mode');
        $lang = requestvar('lang');
        $domain = requestvar('domain');
        $textdomain = requestvar('textdomain');

        $catalog = new PHPGettext_catalog($domain, $textdomain);
        $catalog->setproperty('mode', $mode);
        $catalog->setproperty('lang', $lang);
        $catalog->load();

        $renderer =& renderer();
        $renderer->addbyref('catalog', $catalog);
        $renderer->addvar('mode', $mode);
        $renderer->addvar('comments', $catalog->comments);
        $renderer->addvar('headers', $catalog->headers);
        $renderer->addvar('strings', $catalog->strings);

        switch ($mode)
        {
            case 'mo':
            return $this->view->view();
            break;
            case 'po':
            return $this->view->form();
            break;
            case 'pot':
            return $this->view->view();
            break;
            default:
            trigger_error(__CLASS__.'->'.__FUNCTION__.' - mode not recognized', E_USER_ERROR);
            return false;
            break;
        }
    }
    function extract(){
        require_once('phpgettext.catalog.php');        
        $gettext =& phpgettext();
        $path = _site_path_;
        $args = "--keyword=T_ --default-domain=phpgettext ";
        $args .= '-o '.$path.'locale/phpgettext.pot ';
        $args .= $path.'includes/*.php '.$path.'templates/*.php';
        $gettext->xgettext($args);
        $catalog = new PHPGettext_catalog('phpgettext', $path.'locale/');
        $catalog->setproperty('mode', _MODE_POT_);
        $catalog->load();

        $renderer =& renderer();
        $renderer->addbyref('catalog', $catalog);        
        $renderer->addvar('mode', 'pot');
        $renderer->addvar('comments', $catalog->comments);
        $renderer->addvar('headers', $catalog->headers);
        $renderer->addvar('strings', $catalog->strings);
        
        return $this->view->view();
    }
    function cleartests(){
        rmdirr('tests/locale/');
        mkdir('tests/locale');
        $this->redirect($_SERVER['PHP_SELF']);
    }
    
    function delpot(){
        $domain     = requestvar('domain');
        $textdomain = requestvar('textdomain');
        @unlink($textdomain.'/'.$domain.'.pot');
        $this->redirect($_SERVER['PHP_SELF']);
    }
    function newfrompot(){
        require_once('phpgettext.catalog.php');
        $locale     = requestvar('locale');
        $domain     = requestvar('domain');
        $textdomain = requestvar('textdomain');
        
        $catalog =& new PHPGettext_catalog($domain, $textdomain);
        $catalog->setproperty('mode', _MODE_POT_);
        $catalog->load();
        $catalog->setproperty('lang', $locale);
        $catalog->setproperty('mode', _MODE_PO_);
        $catalog->save();        
        
        $renderer =& renderer();
        $renderer->addbyref('catalog', $catalog);        
        $renderer->addvar('mode', _MODE_PO_);
        $renderer->addvar('comments', $catalog->comments);
        $renderer->addvar('headers', $catalog->headers);
        $renderer->addvar('strings', $catalog->strings);
        
        return $this->view->form();
    }
    function runtests(){
        ob_start();
        require_once('tests/simpletest/unit_tester.php');
        require_once('tests/simpletest/reporter.php');        
        require_once('phpgettext.catalog.php');
        /*
        $test = &new GroupTest('PHPGettext tests');
        $test->addTestFile('tests/gettext_test_cases.php');
        $test->run(new HtmlReporter());
        */
        $test = &new GroupTest('PHPGettext_Catalog tests');
        $test->addTestFile('tests/catalog_test_cases.php');
        $test->run(new HtmlReporter());
        $contents = ob_get_contents();
        ob_end_clean();
        $renderer =& renderer();
        $renderer->addvar('content', $contents);
        $this->view->display();
    }
    function save(){
        require_once('phpgettext.catalog.php');
        $domain     = trim(requestvar('domain'));
        $textdomain = trim(requestvar('textdomain'));
        $lang       = trim(requestvar('lang'));
        $compile    = trim(requestvar('compile'));
        $comments   = trim(requestvar('comments'));
        $headers    = requestvar('headers');
        $msgstr     = requestvar('msgstr');
        $fuzzy      = requestvar('is_fuzzy');

        $catalog =& new PHPGettext_catalog($domain, $textdomain, $lang);
        $catalog->setproperty('mode', 'po');
        $catalog->setproperty('lang', $lang);
        $catalog->load();

        if (isset($comments)) {
            $comments = explode("\n", $comments);
            if (is_array($comments)) {
                foreach ($comments as $comment)   {
                    if (strpos($comment, '#') == 1)  {
                        $catalog->comments .= $comment."\n";
                    }
                }
            }
        }

        foreach ($headers as $key => $value) {
            $catalog->headers[$key] = $value;
        }


        foreach ($msgstr as $index => $string) {
            $catalog->strings[$index]->setmsgstr($string);
            if (isset($is_fuzzy[$index])) {
                $catalog->strings[$index]->setfuzzy(true);
            }
        }
        $catalog->save();
        if ($compile > 0) {
            $catalog->setproperty('mode', 'mo');
            $catalog->save();
        }
        
        $renderer =& renderer();
        $renderer->addbyref('catalog', $catalog);        
        $renderer->addvar('mode', _MODE_MO_);
        $renderer->addvar('comments', $catalog->comments);
        $renderer->addvar('headers', $catalog->headers);
        $renderer->addvar('strings', $catalog->strings);
        
        return $this->view->view();
    }
}

class phpgettextViews
{
    var $action;

    function phpgettextViews(&$action) {
        $this->action =& $action;
    }
    function _default()
    {
        $renderer =& renderer();
        $renderer->addvar('content', $renderer->fetch('home.tpl.php'));
        $this->display();
    }

    function display() {
        $renderer =& renderer();
        $renderer->addbyref('view', $this);
        $renderer->addbyref('t', phpgettext());
        $renderer->display('main.tpl.php');
    }

    function edit()
    {
        $renderer =& Renderer::instance('php');
        $renderer->display();
    }


    function view()
    {
        $renderer =& renderer();
        $renderer->addvar('content', $renderer->fetch('view.tpl.php'));
        $this->display();
    }

    function form()
    {
        $renderer =& renderer();
        $renderer->addvar('content', $renderer->fetch('form.tpl.php'));
        $this->display();
    }


    function dtree($return = false) {
        $textdomains = $this->gettextdomains(rtrim($_SERVER['DOCUMENT_ROOT'], '/'));
        $dtree =  "<script language=\"javascript\" type=\"text/javascript\">\n";
        $dtree .= "d = new dTree('d', '"._site_url_."img/');\n";
        $dtree .= "d.add(0,-1,'Text Domains');\n";
        $cnt = 1;
        foreach ($textdomains as $path) {
            $name = str_replace($_SERVER['DOCUMENT_ROOT'], "", $path);
            $name = str_replace(_gettext_dirname_, "", $name);
            $name = rtrim(ucwords($name), '/');
            if (strlen($name) < 1) {
                $name = T_("Main");
            }
            $dtree .= "d.add($cnt,0,'$name ($path)');\n"; // textdomains
            $td = $cnt;
            $cnt++;

            $translations = $this->getlanguages($path);

            if (is_array($translations)) {
                foreach ($translations as $lang => $files) {
                    $dtree .= "d.add($cnt,$td,'$lang');\n"; // languages
                    $lg = $cnt;
                    $cnt++;

                    if (is_array($files)) {
                        foreach ($files as $file) {
                            if (is_array($file)) {
                                $dtree .= "d.add($cnt,$lg,'LC_MESSAGES');\n";
                                $mo = $cnt;
                                $cnt++;
                                foreach ($file as $k => $v) {
                                    if (is_array($v)) {
                                        foreach ($v as $f) {
                                            $dom = substr($f, 0, -3);
                                            $dtree .= "d.add($cnt, $mo, '$f', '".$_SERVER['PHP_SELF']."?action=view&mode=mo&lang=$lang&domain=$dom&textdomain=$path', '', '', '"._site_url_."img/square.gif');\n";
                                            $cnt++;
                                        }
                                    }
                                }
                            } else {
                                $dom = substr($file, 0, -3);
                                $dtree .= "d.add($cnt,$lg,'$file', '".$_SERVER['PHP_SELF']."?action=view&mode=po&lang=$lang&domain=$dom&textdomain=$path', '', '', '"._site_url_."img/add_content.gif');\n";
                                $cnt++;
                            }
                        }
                    }
                }
            }


            $templates = $this->gettemplates($path);
            for ($a=0; $a<count($templates); $a++) {
                $dom = substr($templates[$a], 0, -4);
                $dtree .= "d.add($cnt,$td,'".$templates[$a]."', '".$_SERVER['PHP_SELF']."?action=view&mode=pot&domain=$dom&textdomain=$path');\n";
                $cnt++;
            }

        }
        $dtree .= "document.write(d);\n";
        $dtree .= "</script>\n";
        if ($return)
        return $dtree;

        echo $dtree;
    }
    function gettextdomains($path){
        static $domains;
        if ($handle=@opendir($path)) {
            while (($file = readdir($handle)) !== false) {
                if (is_dir("$path/$file") && $file!= "." && $file!= "..") {
                    if (preg_match('/^'._gettext_dirname_.'$/', $file)) {
                        $domains[] = "$path/$file";
                    }
                    $this->gettextdomains("$path/$file");
                }
            }
            closedir($handle);
        } else {
            trigger_error(get_class($this)." File ( $path ) not found.", E_USER_ERROR);
        }
        return isset($domains) ? $domains : array();
    }

    function getlanguages($domain) {
        if ($handle = opendir($domain)) {
            while (false !== ($file = readdir($handle))) {
                if (is_dir("$domain/$file") && $file!= "." && $file!= "..")
                $langs[$file] = $this->gettranslations("$domain/$file");
            }
            closedir($handle);
        }
        return (isset($langs) ? $langs : array());
    }

    function gettemplates($textdomain) {
        if ($handle=@opendir($textdomain)) {
            while (($file = readdir($handle)) !== false)
            if (preg_match('/.pot$/', $file))
            $templates[] = $file;
            closedir($handle);
        } else {
            trigger_error(get_class($this)." textdomain ( $path ) not found.", E_USER_ERROR);
        }
        return isset($templates) ? $templates : array();
    }

    function gettranslations($path) {
        $lang = basename($path);
        if (is_dir($path) && $handle=@opendir($path)) {
            while (($file = readdir($handle)) !== false) {
                if ($file== "LC_MESSAGES") {
                    $po[]['LC_MESSAGES'] = $this->getbinaries($path.'/LC_MESSAGES');
                } elseif (preg_match('/.po$/', $file)) {
                    $po[]= $file;
                }
            }
            closedir($handle);
        } else {
            trigger_error(get_class($this)." textdomain ( $path ) not found.", E_USER_ERROR);
        }
        return isset($po) ? $po : array();
    }

    function getbinaries($path) {
        if (is_dir($path) && $handle=@opendir($path)) {
            while (($file = readdir($handle)) !== false) {
                if (preg_match('/.mo$/', $file)) {
                    $mo[] = $file;
                }
            }
            closedir($handle);
        } else {
            trigger_error(get_class($this)." textdomain ( $path ) not found.", E_USER_ERROR);
        }
        return isset($mo) ? $mo : array();
    }

}

class Renderer
{

    var $dir;
    var $vars = array();
    var $engine = 'php';
    var $template = '';

    function Renderer(){}

    function display($template, $return = false){
        if ($template == NULL){
            $error = 'A template has not been specified';
            trigger_error($error, E_USER_ERROR);
            return false;
        }
        $this->template = $this->dir . $template;

        if (is_readable($this->template)) {
            extract($this->getvars());
            if ($return) {
                ob_start();
                include_once($this->template);
                $ret = ob_get_contents();
                ob_end_clean();
                return $ret;
            } else {
                include_once($this->template);
            }
        } else {
            $error = 'Template file ' . $template . ' does ' . 'not exist or is not readable';
            trigger_error($error, E_USER_ERROR);
            return false;
        }
        return false;
    }

    function fetch($template){
        return $this->display($template, true);
    }
    function &getengine(){
        return $this->engine;
    }
    function addvar($key, $value){
        $this->vars[$key] = $value;
    }
    function addbyref ($key, &$value) {
        $this->vars[$key] =& $value;
    }
    function getvars($name = false){
        return (isset($this->vars[$name])) ? $this->vars[$name] : $this->vars;
    }

    function setdir($dir){
        $this->dir = (substr($dir, -1) == DIRECTORY_SEPARATOR) ? $dir : $dir.DIRECTORY_SEPARATOR;
    }
    function getdir(){
        return $this->dir;
    }
    function template($template){
        $this->template = $template;
    }
}




// cleans out a directory recursively
function rmdirr($dir) {
    if($objs = glob($dir."/*")){
        foreach($objs as $obj) {
            is_dir($obj)? rmdirr($obj) : unlink($obj);
        }
    }
    rmdir($dir);
}




function &renderer($engine = 'php') {
    static $renderer;
    if (is_null($renderer[$engine])) {
        if ($engine == 'php') {
            $renderer[$engine] = new Renderer();
        } else {
            $classname = $engine . 'Renderer';
            if (class_exists($classname))
            $renderer[$engine] = new $classname();
        }
    }
    return $renderer[$engine];
}


function dump($var) {
    $trace = debug_backtrace();
    echo "<div>dump() in {$trace[0]['file']}:{$trace[0]['line']}</div>";
    include_once 'Var_Dump.php';
    if (class_exists('Var_Dump')) {
        Var_Dump::displayInit(array('display_mode'=>'HTML4_Table'));
        Var_Dump::display($var);
    } else {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }
}

function error_handler($errno, $errmsg, $filename, $linenum) {

    $dt = date("Y-m-d H:i:s (T)");
    $errortype = array (
    E_ERROR           => "Error",
    E_WARNING         => "Warning",
    E_PARSE           => "Parse Error",
    E_NOTICE          => "Notice",
    E_CORE_ERROR      => "Core Error",
    E_CORE_WARNING    => "Core Warning",
    E_COMPILE_ERROR   => "Compile Error",
    E_COMPILE_WARNING => "Compile Warning",
    E_USER_ERROR      => "User Error",
    E_USER_WARNING    => "User Warning",
    E_USER_NOTICE     => "User Notice"
    );

    // set of errors for which a var trace will be saved
    //$user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);

    $output = <<<EOT
<style type="text/css">
<!-- 
    .red_bold {color:red; font-weight:bold;}
    .error { border: 1px solid grey; color: #000000;}
    .error p.errmsg { font-size: 1em;  background-color: #990033; color: white; margin: 0 0 0 0;}
    .error p.errfile { font-size: .8em; font-style: italic; margin: 0 0 0 20px;}
    .error table.backtrace { }
    .error table.backtrace th { text-align: left; background-color: #339900; color: white}
    .error table.backtrace td {  color: #000000; background-color: #E8E8E8;}
    .error table.backtrace span.function { font-weight: bold; }
    .error table.backtrace span.file { font-size: .8em; font-style: italic; }
    .error table.backtrace span.args { color: #000000; }
-->
</style>
EOT;
    $output .= "<div class=\"error\">";
    $output .= "<p class=\"errmsg\">$errortype[$errno] : $errmsg</p>\n";
    $output .= "<p class=\"errfile\">in file $filename : $linenum</p>\n";
    $output .=  backtrace();
    $output .= "</div>";
    echo $output;
    // save to the error log, and e-mail it if there is a critical user error
    /*
    error_log($err, 3, "/error.log");
    if ($errno == E_USER_ERROR) {
    mail("phpdev@example.com", "Critical User Error", $err);
    }
    */
}

function backtrace(){
    $backtrace = debug_backtrace();
    $output = "<table class=\"backtrace\"  border=\"1\" cellpadding=\"0\" cellspacing=\"0\">";
    $output .= "<tr><th>#</th><th>function / location</th><th>args</th></tr>";
    $count = 0;
    if (isset($backtrace[0]['line'])) {
        foreach ($backtrace as $bt) {
            $argstr = '';
            $class = isset($bt['class']) ? $bt['class'] : '';
            $type = isset($bt['type']) ? $bt['type'] : '';
            $function = isset($bt['function']) ? $bt['function'] : '';
            $file = isset($bt['file']) ? $bt['file'] : '';
            $line = isset($bt['line']) ? $bt['line'] : '';
            $args = isset($bt['args']) ? $bt['args'] : array();
            foreach ($args as $a) {
                if (!empty($args)) {
                    $argstr .= ', ';
                }
                switch (gettype($a)) {
                    case 'integer':
                    case 'double':
                    $argstr .= $a;
                    break;
                    case 'string':
                    $a = htmlspecialchars($a);
                    $argstr .= "\"$a\"";
                    break;
                    case 'array':
                    $argstr .= 'Array('.count($a).')';
                    break;
                    case 'object':
                    $argstr .= 'Object('.get_class($a).')';
                    break;
                    case 'resource':
                    $argstr .= 'Resource('.strstr($a, '#').')';
                    break;
                    case 'boolean':
                    $argstr .= $a ? 'True' : 'False';
                    break;
                    case 'NULL':
                    $argstr .= 'Null';
                    break;
                    default:
                    $argstr .= 'Unknown';
                }
            }
            if ($count > 1) {
                $output .= "<tr>\n";
                $output .= "<td><span>".($count-1)."</span></td>\n";
                $output .= "<td><span class=\"function\">{$class}{$type}{$function}()</span><br />\n";
                $output .= "<span class=\"file\">{$file}:{$line}</span></td>\n";
                $output .= "<td><span class=\"args\">$argstr<br /></span></td>\n";
                $output .= "</tr>\n";
            }
            $count++;
        }
    }
    $output .= "</table>\n";
    return $output;
}

?>