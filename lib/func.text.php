<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Title: Text Functions
  This file contains functions that deal with manipulating pieces of text.
*/

/*
  Function: shorten
  Shortens a text to a specified length.

  This will keep shortening the text until it finds a space, so that it won't cut off words.

  Parameters:
  $text - The text to shorten.
  $length - The number of characters the text must be shortened to.

  Returns:
  The shortened text, or the full text if the text was already short enough.

  Example Usage:
  > $str = 'blablabla long string here';
  > $new_str = shorten($str, 15);
  > echo $new_str;
  > //Outputs: 'blablabla long...'
*/
function shorten($text, $length = 50)
{	
    if (strlen($text) > $length)
    {
        $ret = '';
        while (substr($text, $length, 1) != ' ')
        {
            --$length;
        }
        $ret = substr($text, 0, $length);
        $ret .= "...";
        return $ret;
    }
    else
    {
        return $text;
    }
}
?>