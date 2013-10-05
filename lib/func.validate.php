<?php

if ( !defined('IN_EZRPG') )
    exit;

/*
  Title: Validation functions
  This file contains functions you can use to validate player data: username, password, email, etc.
 */

/*
  Function: isUsername
  Checks the length and format of the username.

  Parameters:
  $username - The value to check if it's a username.

  Returns:
  Boolean - true or false
 */

function isUsername($username)
{
    return (preg_match("/^[a-zA-Z0-9_]{3,16}$/", $username));
}

/*
  Function: isPassword
  Checks if the length of the password is long enough.

  Parameters:
  $password - The value to check

  Returns:
  Boolean - true or false
 */

function isPassword($password)
{
	global $settings;
	$length = $settings->setting['validation']['passLenMin']['value'];
	if($settings->setting['validation']['passLens']['value']['value'] == 'minmax')
	{
		$length .= ','. $settings->setting['validation']['passLenMax']['value'];
	}
    return (preg_match("/[a-zA-Z0-9\W]{".$length."}+/", $password));
}

/*
  Function: isEmail
  Checks if the email is valid

  Parameters:
  $email - The value to check

  Returns:
  Boolean - true or false
 */

function isEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/*
  Function: isClean
  Checks if the input is valid

  Parameters:
  $input - The value to check

  Returns:
  Boolean - true or false
 */

function isClean($input)
{
	if(isset($input)){
		return (preg_match("/^[_a-zA-Z0-9]+$/", $input));
	}else{
		return true;
	}
}

?>
