<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Title: Config
  The most important settings for the game are set here.
*/

/*
  Variables: Database Connection
  Connection settings for the database.
  
  $config_server - Database server
  $config_dbname - Database name
  $config_username - Username to login to server with
  $config_password - Password to login to server with
  $config_driver - Contains the database driver to use to connect to the database.
*/
$config_server = 'localhost';
$config_dbname = 'ezrpg';
$config_username = 'root';
$config_password = '';
$config_driver = 'mysql';

/*
  Constant:
  This secret key is used in the hashing of player passwords and other important data.
  Secret keys can be of any length, however longer keys are more effective.
  
  This should only ever be set ONCE! Any changes to it will cause your game to break!
  You should save a copy of the key on your computer, just in case the secret key is lost or accidentally changed,.
  
  SECRET_KEY - A long string of random characters.
*/
define('SECRET_KEY', '#lkjwdr!@ljdfSFAwje98u342W23adf');


/*
  Constants: Settings
  Various settings used in ezRPG.
  
  DB_PREFIX - Prefix to the table names
  VERSION - Version of ezRPG
  SHOW_ERRORS - Turn on to show PHP errors.
  DEBUG_MODE - Turn on to show database errors and debug information.
*/
define('DB_PREFIX', 'ezrpg_');
define('VERSION', '1.2.0');
define('SHOW_ERRORS', 0);
define('DEBUG_MODE', 0);
?>