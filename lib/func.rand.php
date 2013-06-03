<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Title: Random Functions
  This file contains functions that deal with generating random values.
*/

/*
  Function: createKey
  Generates a random sequence of characters.

  Parameters:
  $length - Length of key to generate.
  $option - Change the character set.

  Returns:
  The generated key.

  Example Usage:
  > $new_key = createKey(1024);
*/
function createKey($length, $option=0) {
    if ($option == 1)
    {
        $chars = "cefhklmnrtuvwxyCEFHKLMNRTUVWXY349";
    }
    else
    {
        $chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|:><,./?`~";
    }
	
    //srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;

    while ($i < $length)
    {
        $num = mt_rand() % (strlen($chars) - 1);
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}

function randColor()
{
    $rand1 = mt_rand(0, 1);
    if ($rand1 == 1)
    {
        return mt_rand(0, 70);
    }
    else
    {
        return mt_rand(180, 255);
    }
}
?>