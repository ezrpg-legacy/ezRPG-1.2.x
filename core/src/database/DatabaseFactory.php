<?php
namespace ezrpg\core\database;

use PDO,
    ezrpg\core\EzException;

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: DatabaseFactory
  Factory class for database drivers.

  See Also:
  - <DbException>
 */

class DatabaseFactory
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
      >     $db = DatabaseFactory::factory('mysql', 'localhost', 'root', 'password', 'ezrpg');
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
        $conf = $config['database'];

        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        try {
            $dsn = "mysql:host=".$conf['host'].";dbname=".$conf['name'].";";
            $pdo = new PDO( $dsn, $conf['user'], $conf['pass'], $options );
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }catch(\PDOException $ex){
            throw new EzException($ex->getMessage() . ": ".$dsn . ". Line:" . $ex->getLine() . " of Database.php");
        }

        return new \ezrpg\core\database\Database($pdo, $conf['prefix']);
    }

}

?>
