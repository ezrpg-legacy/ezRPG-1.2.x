<?php

if (!defined('IN_EZRPG')) {
    exit;
}

/*
  Title: Security functions
  This file contains functions used for security pruposes.
 */

/*
  Function: createPBKDF2
  Creates a PBKDF2 has from the arguments passed to it.

  Paramaters:
  $password - The string/password to apply PBKDF2 to.
  $salt - A string to be used as a salt.
  $global_salt - A string to be used a global salt.
  $count - Possitive integer for how many times the hash should be iterated.
  $length - The derived key length.
  $algorithm - Algorithm to apply on hash.

  Returns:
  String - The derived key.
 */

function createPBKDF2(
    $password,
    $salt = 'ezRPG',
    $global_salt = "",
    $count = 1005,
    $length = 32,
    $algorithm = 'sha256'
) {
    if($global_salt == ""){
        $config_paths = [
            'config/*.php'
        ];
        $configLoader = new \ezrpg\core\ConfigLoader();
        $config = $configLoader->loadConfigFromPaths($config_paths);
        $global_salt = $config['secret'];
    }
    $password = $salt . $password;

    $kb = $length;
    $derived = '';

    for ($block = 1; $block <= $kb; $block++) {
        $hash = hash_hmac($algorithm, $global_salt . pack('N', $block), $password, true);
        $initial_block = $hash;

        for ($i = 1; $i < $count; $i++) {
            $initial_block ^= ($h = hash_hmac($algorithm, $hash, $password, true));
        }

        $derived .= $initial_block;
    }

    return substr($derived, 0, $length);
}

/*
  Function: comparePBKDF2
  Compares two PBKDF2 passwords.

  Paramaters:
  $origin - An array, double cell when introducing a salt.
  $comparison - A PBKDF2 hash representation of a password.

  Returns:
  Boolean - true or false.
 */

function comparePBKDF2($origin, $comparison)
{
    if (count($origin) == 2) {
        if (createPBKDF2($origin[0], $origin[1]) == $comparison) {
            return true;
        } else {
            return false;
        }
    } else {
        if (createPBKDF2($origin[0]) === $comparison) {
            return true;
        } else {
            return false;
        }
    }
}

/*
  Function: createSalt
  Creates a random salt to be used for hashing.

  Paramaters:
  $length - Amount of bytes that should be returned.

  Returns:
  String - A random string.
 */

function createSalt($length)
{
    $byte = array();
    for ($i = 0; $i <= $length; $i++) {
        $mockup = sha1(microtime());
        $rand = mt_rand(0, (strlen($mockup) - 1));
        $byte[] = substr($mockup, $rand, ($rand + 1));
    }

    return implode($byte);
}

/*
  Function: createBcrypt
  Creates a Bcrypt has from the arguments passed to it.

  Paramaters:
  $password - The string/password to apply the Bcrypt algorithm to.
  $salt - A string to be used as a salt.
  $count - Possitive integer for how many times the hash should be iterated.

  Returns:
  String - The derived hash.
 */

function createBcrypt($password, $salt = 'ezRPG', $count = 7)
{
    $salt = sprintf('$2a$%02d$%s$', $count, $salt);

    return crypt($password, $salt);
}

/*
  Function: compareBcrypt
  Compares two Bcrypt passwords.

  Paramaters:
  $origin - An array, double cell when introducing a salt.
  $comparison - A Bcrypt hash representation of a password.

  Returns:
  Boolean - true or false.
 */

function compareBcrypt($origin, $comparison)
{
    return (createBcrypt($origin[0]) === $comparison);
}

function generateSignature()
{

    $client = array_key_exists('userid', $_SESSION) ?
        $_SESSION['userid'] : 'guest';

    $config_paths = [
        'config/*.php'
    ];
    $configLoader = new \ezrpg\core\ConfigLoader();
    $config = $configLoader->loadConfigFromPaths($config_paths);

    $bits = array(
        'userid' => $client,
        'ip' => $_SERVER['REMOTE_ADDR'],
        'browser' => $_SERVER['HTTP_USER_AGENT'],
        'key' => $config['secret']
    );

    $signature = false;

    foreach ($bits as $key => $bit) {
        $signature .= $key . $bit;
    }

    return sha1($signature);
}

function compareSignature($origin)
{
    return $origin === generateSignature();
}

function createLegacyPassword($secret, $password)
{
    $config_paths = [
        'config/*.php'
    ];
    $configLoader = new \ezrpg\core\ConfigLoader();
    $config = $configLoader->loadConfigFromPaths($config_paths);

    return sha1($secret . $password . $config['secret']);
}

function compareLegacyPassword($secret, $input, $password)
{
    $config_paths = [
        'config/*.php'
    ];
    $configLoader = new \ezrpg\core\ConfigLoader();
    $config = $configLoader->loadConfigFromPaths($config_paths);

    if (sha1($secret . $input . $config['secret']) == $password) {
        return true;
    } else {
        return false;
    }
}

function createPassword($secret, $password)
{
    global $container;
    $pass_meth = $container['config']['app']['pass_encryption']['value'];

    switch ($pass_meth) {
        case 1:
            return createLegacyPassword($secret, $password);
            break;
        case 2:
            return createPBKDF2($password, $secret);
            break;
        case 3:
            return createBcrypt($password);
            break;
    }
}

function checkPassword($secret, $input, $password, $override = '0')
{
    global $container;
    $pass_meth = $container['config']['app']['pass_encryption']['value'];
    switch (($override == '0' ? $pass_meth : $override)) {
        case 1:
            return compareLegacyPassword($secret, $input,
                ($override == 0 ? $password : createLegacyPassword($secret, $input)));
            break;
        case 2:
            return comparePBKDF2(array($input, $secret), ($override == 0 ? $password : createPBKDF2($input, $secret)));
            break;
        case 3:
            return compareBcrypt(array($input, $secret), ($override == 0 ? $password : createBcrypt($input)));
            break;
    }
}

?>