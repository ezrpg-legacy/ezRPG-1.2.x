<?php

namespace ezrpg\core;
use ezrpg\core\DbException,
    \PDO,
    \PDOException,
    ezrpg\core\EzException;

// This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: PDO
  Database abstraction class for PDO.

  See Also:
  - <DbFactory>
  - <DbException>
 */

class DbEngine
{
    /*
      Integer: $query_count
      Keeps track of number of queries made in this database connection.
     */

    public $query_count = 0;

    /*
      String: $error
      Contains the last SQL error.
     */
    public $error;

    /*
      String: $query
      Contains the last query executed.
     */
    public $query;

    /*
      Variable: $db
      Contains a PDO link identifier.
     */
    protected $db;

    protected $prepared;

    protected $prefix;

    /*
      Constructor: __construct
      Saves the connection data for later use. Does not start a connection yet.

      Parameters:
      $host - Database server
      $username - Username to login with
      $password - Password to login with
      $dbname - Name of database
     */

    public function __construct(\PDO $db, $prefix = '')
    {
        $this->db = $db;
        $this->prefix = $prefix;
    }

    public function execute($query, $params = 0)
    {
        $query = trim($query);

        try {
            if (strpos($query, $this->prefix) !== true) {
                $query = str_replace('<ezrpg>', $this->prefix, $query);
            }

            if (count($params) > 0 && is_array($params)) {

                foreach ($params as $f => $v) {
                    $tmp[] = ":s_$f";
                    $pos = strpos($query, "?");
                    if ($pos !== false) {
                        $query = substr_replace($query, ":s_$f", $pos, strlen("?"));
                    }
                }

                $sql = $query;
                // pdo prepare statement
                try {
                    $this->prepared = $this->db->prepare($sql);
                    $this->_bindPdoNameSpace($params);
                    // set class where property with array data

                    //die(var_dump($this->prepared->debugDumpParams()));
                    $this->prepared->execute();
                    return $this->prepared;
                } catch (\PDOException $ex) {
                    throw new DbException($ex->getMessage());
                }
            }else{
                try {
                    return $this->db->query($query);
                }catch(\PDOException $ex){
                    throw new DbException($ex->getMessage());
                }
            }

        }catch(\PDOException $ex){
            throw new DbException($ex->getMessage());
        }
    }

    /*
      Function: fetch
      Fetches the data from the result set and returns it as an object.

      Parameters:
      $result - A result set from an SQL query.

      Returns:
      The results from the query in an array.

      Example Usage:
      > $query = $db->execute('SELECT COUNT(id) AS count FROM <ezrpg>players');
      > $result = $db->fetch($query);
      > echo $result->count;

      See Also:
      - <execute>
      - <fetchRow>
     */

    public function fetch(&$result)
    {
        return $result->fetchObject();
    }

    /*
      Function: fetchArray
      Fetches the data from the result set and returns it as an array.

      Parameters:
      $result - A result set from an SQL query.

      Returns:
      An array with the query results.

      Example Usage:
      > $query = $db->execute('SELECT COUNT(`id`) AS `count` FROM `<ezrpg>players`');
      > $result = $db->fetchArray($query);
      > echo $result['count'];

      See Also:
      - <execute>
      - <fetch>
     */

    public function fetchArray(&$result)
    {
        return $this->fetch_array();
    }

    /*
      Function: fetchAll
      Fetches all the data from a result set and returns it as an array of results.

      Parameters:
      $result - A result set from an SQL query.
      $return_array - Boolean to return the result as arrays or objects.

      Returns:
      An array of arrays/objects from query results.
      On a failed query, returns false.

      Example Usage:
      > $query = $db->execute('SELECT `id` FROM `<ezrpg>players`');
      > $results = $db->fetchAll($query);
      > foreach ($results as $row)
      >   echo $row->id;

      See Also:
      - <execute>
      - <fetch>
      - <fetchArray>
     */

    public function fetchAll($result, $return_array = false)
    {
        $fetch_style = $return_array ? PDO::FETCH_ASSOC : PDO::FETCH_OBJ;
        return $result->fetchAll($fetch_style);
    }

    /*
      Function: fetchRow
      Combines <execute> and <fetch> to a single function for fetching single rows of data.

      Equivalent to using mysql_fetch_object(mysql_query($query)).

      After the row is fetched, all memory associated with the result is freed with PDO::closeCursor().

      Parameters:
      $query - A string with the SQL query to execute.
      $params - An optional array of parameters to use with parameter binding.

      Returns:
      The results from the query in an array.

      Example Usage:
      > $result = $db->fetchRow('SELECT COUNT(id) AS count FROM <ezrpg>players');
      > echo $result->count;

      See Also:
      - <execute>
      - <fetch>
     */

    public function fetchRow($query, $params = 0)
    {
        $result = $this->execute($query, $params);
        $ret = $this->fetch($result);
        $result->closecursor();

        return $ret;
    }

    /*
      Function: numRows
      Uses PDO::rowCount on a result set to return the number of rows.

      Parameters:
      $result - A result set from an SQL query.

      Returns:
      The number of rows in the result set.
     */

    public function numRows(&$result)
    {
        return $result->rowCount();
    }

    /*
      Function: insert
      Takes an array to generate an INSERT query, then executes it.

      Parameters:
      $table - Name of the table to insert into.
      $data - An array with keys being column names.

      Returns:
      ID generated by the AUTO_INCREMENT column.

      Example Usage:
      > $insert = Array();
      > $insert['username'] = 'Andy';
      > $insert['password'] = 'a9629b9ff4f0637362a0954224e1cd5792effb62';
      > $insert['email'] = 'andy@ezrpgproject.com';
      > $insert['registered'] = time();
      > $new_player = $db->insert('<ezrpg>players', $insert);

      See Also:
      - <execute>
     */

    public function insert($table, $data){
        // check if table name not empty
        if ( !empty( $table ) ) {
            if (strpos($table, $this->prefix) !== true) {
                $table = str_replace('<ezrpg>', $this->prefix, $table);
            }
            // and array data not empty
            if (count($data) > 0 && is_array($data)) {
                // get array insert data in temp array
                foreach ($data as $f => $v) {
                    $tmp[] = ":s_$f";
                }
                // make name space param for pdo insert statement
                $sNameSpaceParam = implode(',', $tmp);
                // unset temp var
                unset($tmp);
                // get insert fields name
                $sFields = implode(',', array_keys($data));
                // set pdo insert statement in class property
                $sql = "INSERT INTO `$table` ($sFields) VALUES ($sNameSpaceParam);";
                // pdo prepare statement
                try {
                    $this->prepared = $this->db->prepare($sql);
                    $this->_bindPdoNameSpace($data);
                    // set class where property with array data
                    $this->prepared->execute();
                    return $this->db->lastInsertId();
                }catch(\PDOException $ex){
                    throw new DbException($ex->getMessage());
                }
            }
        }
    }
    /*
      Function: affected
      Returns the number of affected rows from the last query executed.

      Returns:
      An integer representing the number of affected rows, or -1 if the query failed.
     */

    public function affected()
    {
        return $this->db->affected_rows;
    }

    /*
      Function: update
      Updates a row based on where clause

      Parameters:
      $table - Database Table
      $fields - Array containing Col and Val
      $where - Where Clause

      Usuage:
      $fields['money'] = $this->player->money + 100;
      $fields['exp'] = $this->player->exp + 25;
      $fields['kills'] = $this->player->kills + 1;
      $this->db->update('<ezrpg>players', $fields, "ID = '" . $this->player->id . "'");

      Considerations:
      Possible a 4th Argument: WhereCol and WhereVal
      This would allow you to preform functions on the value itself.
      Also it could allow for a default column to be select (perhaps player->id?)
     */

    public function update($table, $fields, $where)
    {
        $i = 0;
        $var = "";
        $numFields = count($fields);
        foreach ($fields as $key => $val) {
            if (++$i === $numFields) {
                $var .= $key . "='" . $val . "'";
            } else {
                $var .= $key . "='" . $val . "', ";
            }
        }
        $sql = "Update " . $table . " SET " . $var . " WHERE " . $where;

        return $this->execute($sql);
    }

    /**
     * PDO Bind Param with :namespace
     * @param array $array
     */
    private function _bindPdoNameSpace( $array = array() ) {
        if(strstr(key($array), ' ')){
            // bind array data in pdo
            foreach ( $array as $f => $v ) {
                // get table column from array key
                $field = $this->getFieldFromArrayKey($f);
                // check pass data type for appropriate field
                switch ( gettype( $array[$f] ) ):
                    // is string found then pdo param as string
                    case 'string':
                        $this->prepared->bindParam( ":s" . "_" . "$field", $array[$f], PDO::PARAM_STR );
                        break;
                    // if int found then pdo param set as int
                    case 'integer':
                        $this->prepared->bindParam( ":s" . "_" . "$field", $array[$f], PDO::PARAM_INT );
                        break;
                    // if boolean found then set pdo param as boolean
                    case 'boolean':
                        $this->prepared->bindParam( ":s" . "_" . "$field", $array[$f], PDO::PARAM_BOOL );
                        break;
                endswitch;
            } // end for each here
        }else{
            // bind array data in pdo
            foreach ( $array as $f => $v ) {
                // check pass data type for appropriate field
                switch ( gettype( $array[$f] ) ):
                    // is string found then pdo param as string
                    case 'string':
                        $this->prepared->bindParam( ":s" . "_" . "$f", $array[$f], PDO::PARAM_STR );
                        break;
                    // if int found then pdo param set as int
                    case 'integer':
                        $this->prepared->bindParam( ":s" . "_" . "$f", $array[$f], PDO::PARAM_INT );
                        break;
                    // if boolean found then set pdo param as boolean
                    case 'boolean':
                        $this->prepared->bindParam( ":s" . "_" . "$f", $array[$f], PDO::PARAM_BOOL );
                        break;
                endswitch;
            } // end for each here
        }
    }
    /**
     * Bind PDO Param without :namespace
     * @param array $array
     */
    private function _bindPdoParam( $array = array() ) {
        // bind array data in pdo
        foreach ( $array as $f => $v ) {
            // check pass data type for appropriate field
            switch ( gettype( $array[$f] ) ):
                // is string found then pdo param as string
                case 'string':
                    $this->prepared->bindParam( $f + 1, $array[$f], PDO::PARAM_STR );
                    break;
                // if int found then pdo param set as int
                case 'integer':
                    $this->prepared->bindParam( $f + 1, $array[$f], PDO::PARAM_INT );
                    break;
                // if boolean found then set pdo param as boolean
                case 'boolean':
                    $this->prepared->bindParam( $f + 1, $array[$f], PDO::PARAM_BOOL );
                    break;
            endswitch;
        } // end for each here
    }
}
