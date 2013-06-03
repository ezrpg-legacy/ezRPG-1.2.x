<?php
defined('IN_EZRPG') or exit;

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
    if (strlen($username) < 3)
        return false;
    if (!preg_match("/^[_a-zA-Z0-9]+$/", $username))
        return false;
    //Everything's fine, return true
    return true;
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
    if (strlen($password) < 3)
        return false;
    return true;
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
    if (strlen($email) < 3)
        return false;
    if (!preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", $email))
        return false;
    return true;
}


function isClean($input)
{
if (!preg_match("/^[_a-zA-Z0-9]+$/", $input))
        if (!isset($input))
  		return false;
    //Everything's fine, return true
    return true;
}
?>
