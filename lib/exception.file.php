<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: FileException
  Extends Exception class to specify a file read/write error.
 */

class FileException extends Exception
{
    /*
      Function: __toString
      Formats and returns the error file, line and message.
     */

    public function __toString()
    {
        switch ($this->code) {
            case 0: //Could not read
                $this->message = 'Could not read the file: ' . $this->message;
                break;
            case 1: //Could not write
                $this->message = 'Could not write to file: ' . $this->message;
                break;
        }

        $ret = '<p><strong>Error: ' . __CLASS__ . '!</strong><br />';
        $ret .= 'File: ' . $this->file . '<br />';
        $ret .= 'Line: ' . $this->line . '<br /><br />';
        $ret .= 'Error Message: ' . $this->message . '</p>';

        return $ret;
    }

}

?>