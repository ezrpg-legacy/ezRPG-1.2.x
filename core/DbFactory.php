<?php

namespace ezrpg\core;

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
      $db - Name of the database.

      Returns:
      A new instance of the database driver class.

      Throws a <DbException> on failure.

      Example Usage:
      > try
      > {
      >     $db = DbFactory::factory('mysql', 'localhost', 'root', 'password', 'ezrpg');
      > }
      > catch (DbException $e)
      > {
      >     $e->__toString();
      > }

      See Also:
      - <DbException>
     */

    public static function factory($config)
    {
        $dbconfig = $config['database'];

        return new \ezrpg\core\DbEngine($dbconfig);
    }

}

?>
