<?php

function dump($var) {
    $trace = debug_backtrace();
    echo "<div style=\"text-align:left\">dump() in {$trace[0]['file']}:{$trace[0]['line']}</div>";
    //include_once 'Var_Dump.php';
    if (class_exists('Var_Dump')) {
        Var_Dump::displayInit(array('display_mode'=>'HTML4_Table'));
        Var_Dump::display($var);
    } else {
        echo "<pre style=\"text-align:left\">";
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