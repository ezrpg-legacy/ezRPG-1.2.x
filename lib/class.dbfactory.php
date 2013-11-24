<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: DbFactory
  Factory class for database drivers.

  See Also:
  - <DbException>
 */

class DbFactory
{
    /*
      Function: factory
      A static function to return the correct database object according to the database type.

      Parameters:
      $type - The type of database driver to use.
      $host - The host of the database server.
      $username - Username to login with.
      $password - Password to login with.
      $dbase - Name of the database.

      Returns:
      A new instance of the database driver class.

      Throws a <DbException> on failure.

      Example Usage:
      > try
      > {
      >     $dbase = DbFactory::factory('mysql', 'localhost', 'root', 'password', 'ezrpg');
      > }
      > catch (DbException $e)
      > {
      >     $e->__toString();
      > }

      See Also:
      - <DbException>
     */

    public static function factory($type = 'mysql', $host = 'localhost', $username = 'root', $password = '', $dbase = 'ezrpg', $port = '3306')
    {
        if ( include_once(LIB_DIR . '/db.' . $type . '.php') )
        {
            $classname = 'Db_' . $type;
            return new $classname($host, $username, $password, $dbase, $port);
        }
        else
        {
            throw new DbException($type, DRIVER_ERROR);
        }
    }

}

?>