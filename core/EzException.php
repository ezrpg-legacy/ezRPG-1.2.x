<?php

namespace ezrpg\core;
use \Exception;

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: FileException
  Extends Exception class to specify a file read/write error.
 */

class EzException extends Exception
{
    /*
      Function: __toString
      Formats and returns the error file, line and message.
     */

    public function __toString()
    {
        $trace = nl2br($this->getTraceAsString());
        $this_class = __CLASS__;
        //Output message
        $ret = <<<OUT
<html>
<head>
<title>ezRPG Engine Error!</title>
<style>
#error { width: 50%; margin: auto; font: 0.8em  Verdana, Arial, Sans-serif; color: #666; padding: 10px; border: 1px solid #3182C0; }
</style>
</head>
<body>
<div id="error">
<h1>ezRPG Engine</h1>
<p><strong>Error: $this_class!</strong><br />
OUT;

        //Only show line number and file if debug mode is on
        //if (DEBUG_MODE) {
            $ret .= <<<OUT
<strong>File</strong>: $this->file<br />
<strong>Line</strong>: $this->line<br />
OUT;
        //}

        //The error message itself
        $ret .= <<<OUT
<br />
$this->message
OUT;

        //Only show stack trace if debug mode is on
        if (DEBUG_MODE) {
            $ret .= <<<OUT
<br /><br />
<strong>Stack Trace:</strong><br />
$trace
OUT;
        }

        $ret .= <<<OUT
</p></div>
</body></html>
OUT;
        die($ret);
    }

}

?>