<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: DbException
  Extends Exception class to specify a database error.

  Example (throwing an exception):
  > throw new DbException('tablename', DATABASE_ERROR);
  > throw new DbException($this->db, SERVER_ERROR);
  > throw new DbException($query, SQL_ERROR);

  See Also:
  - <DbFactory>
  - <Error Codes>
 */

class DbException extends Exception
{
    /*
      Function: __toString
      Formats and returns the error file, line and message.

      Uses die() to print the formatted error message and to stop further execution of any code.

      Example Usage:
      > try
      > {
      >     //Failed sql query
      > }
      > catch (DbException $e)
      > {
      >     $e->__toString(); //Display the error page. This will stop further execution of the script.
      > }
     */

    public function __toString()
    {
        switch ( $this->code )
        {
            case DRIVER_ERROR: //Could not connect to server
                $this->message = 'Could not find driver: ' . $this->message;
                break;
            case SERVER_ERROR: //Could not connect to server
                $this->message = 'Could not connect to database server!';
                break;
            case DATABASE_ERROR: //Could not select database
                $this->message = 'Could not select database: ' . $this->message;
                break;
            case SQL_ERROR: //SQL error
                break;
            default:
                break;
        }

        $trace = nl2br($this->getTraceAsString());
        $this_class = __CLASS__;

        //Output message
        $ret = <<<OUT
<html>
<head>
<title>ezRPG Error!</title>
<style>
#error { width: 50%; margin: auto; font: 0.8em  Verdana, Arial, Sans-serif; color: #666; padding: 10px; border: 1px solid #3182C0; }
</style>
</head>
<body>
<div id="error">
<h1>ezRPG</h1>
<p><strong>Error: $this_class!</strong><br />
OUT;

        //Only show line number and file if debug mode is on
        if ( DEBUG_MODE )
        {
            $ret .= <<<OUT
<strong>File</strong>: $this->file<br />
<strong>Line</strong>: $this->line<br />
OUT;
        }

        //The error message itself
        $ret .= <<<OUT
<br />
$this->message
OUT;

        //Only show stack trace if debug mode is on
        if ( DEBUG_MODE )
        {
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
