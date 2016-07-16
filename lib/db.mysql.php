<?php

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Db_mysql
  Database abstraction class for MySQL.

  See Also:
  - <DbFactory>
  - <DbException>
 */

class Db_mysql
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
      Boolean: $isConnected
      A boolean showing the status of the database connection.
     */
    public $isConnected = false;

    /*
      Variable: $db
      Contains a MySQL link identifier.
     */
    protected $db;

    /*
      Variables: Connection Details
      $host - Host name of database server.
      $dbname - Name of the database.
      $username - Username to connect with.
      $password - Password to connect with.
     */
    protected $host;
    protected $dbname;
    protected $username;
    protected $password;
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

    public function __construct($host = 'localhost', $username = 'root', $password = '', $dbname = '')
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->prefix = (defined('DB_PREFIX') ? DB_PREFIX : 'ezrpg');
    }

    /*
      Destructor: __destruct
      Closes the connection to the MySQL server.
     */

    public function __destruct()
    {
        if ($this->isConnected) {
            mysql_close($this->db);
        }
    }

    /*
      Function: execute
      Checks if a connection is made, otherwise first calls <connect>.
      Executes a query and returns the result (a resource or boolean).

      Parameters:
      $query - A string with the SQL query to execute.
      $params - An optional array of parameters to use with parameter binding.

      Returns:
      The result of the SQL query. Either a resource or a boolean.
      On a failed query, it returns false.
      On an SQL error, it throws a <DbException> with the MySQL error.

      Example Usage:
      > $query = $db->execute('SELECT username FROM <ezrpg>players WHERE id=?', array($player->id));

      > $query = $db->execute('SELECT COUNT(id) AS count FROM <ezrpg>players');

      See Also:
      - <connect>
     */

    public function execute($query, $params = 0)
    {
        if ($this->isConnected === false) {
            $this->connect();
        }

        try {
            //SQL queries should query for tables with <ezrpg>tablename so that <ezrpg> is replaced with the table prefix.
            $query = str_replace('<ezrpg>', DB_PREFIX, $query);

            //Parameter binding
            if ($params != 0) {
                //Split the query
                $parts = explode('?', $query);

                //Make sure query parts and parameters match, otherwise adjust the arrays
                $count1 = count($parts);
                $count2 = count($params);
                if ($count1 <= $count2) //Too many parameters, drop the extras
                {
                    $params = array_slice($params, 0, $count1);
                }

                if ($count1 > ($count2 + 1)) //Too little parameters, add extra '?' symbols
                { //OR throw an SQL exception?
                    $diff = $count2 - $count1;
                    array_fill($params, $diff, '?');
                }

                //Sanitize parameters
                for ($i = 0; $i < $count2; $i++) {
                    $val = $params[$i];

                    if (is_string($val)) {
                        //magic quotes
                        if (get_magic_quotes_gpc()) {
                            $val = stripslashes($val);
                        }

                        //Below conditional has been commented out to enforce types
                        //If a string was passed that was meant to be an integer, you must cast it to an int with intval() first.
                        //Otherwise, strings of numbers will still be passed as a string, and surrounded with single quotes
                        //if (!ctype_digit($val))
                        //{
                        $val = '\'' . mysql_real_escape_string($val, $this->db) . '\'';
                        //} //Otherwise the string is acting as a digit, so leave it alone
                    } else {
                        if (is_int($val) || is_float($val)) {
                            //Value is an integer, no sanitation is necessary.
                            //Only need to convert to string so the parameter can be concatenated onto the query string.
                            //(Not really necessary, but otherwise this block would be empty ;])
                            $val = strval($val);
                        } else {
                            //Parameter is not a valid type.
                            $val = '?';
                            //OR throw an SQL exception?
                        }
                    }

                    $params[$i] = $val;
                }

                $query = '';
                //Reconstruct query
                for ($i = 0; $i < $count2; $i++) {
                    $query .= $parts[$i] . $params[$i];
                }
                $query .= $parts[($count1 - 1)];
            }

            $this->query = $query;

            if (DEBUG_MODE === 1) {
                echo $query . '<br />';
            }

            //Execute query
            $result = mysql_query($query, $this->db);

            if ($result === false) { //If there was an error with the query
                $this->error = mysql_error();

                //If in debug mode, send exception, otherwise ignore
                if (SHOW_ERRORS === 1) {
                    //Feature: admin logging of errors?
                    $error_msg = '<strong>Query:</strong> <em>' . $this->query . '</em><br /><strong>' . $this->error . '</strong>';
                } elseif (isset($_SESSION['in_installer']) && $_SESSION['in_installer']) {
                    $error_msg = '<strong>Query:</strong> <em><pre>' . $this->query . '</pre></em><br /><strong>' . $this->error . '</strong> <br />';
                    $error_msg .= 'Contact Current Game Support staff or ezRPGProject.net Support for help. <br />';
                    $error_msg .= '<a href="javascript:document.location.reload();">Reload</a>';
                } else {
                    $error_msg = '<strong>Error:</strong> <em>There has been a database error. This error has been logged.</em>';
                }
                throw new DbException($error_msg, SQL_ERROR);

                return false;
            }
        } catch (SQLException $e) {
            $e->__toString();
        }

        //Update query count
        ++$this->query_count;

        return $result;
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
        $ret = mysql_fetch_object($result);

        return $ret;
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
        $ret = mysql_fetch_array($result);

        return $ret;
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

    public function fetchAll(&$result, $return_array = false)
    {
        $ret = array();

        if ($result === false) {
            return $ret;
        }

        if ($return_array === true) {
            while ($row = $this->fetchArray($result)) {
                $ret[] = $row;
            }
        } else {
            while ($row = $this->fetch($result)) {
                $ret[] = $row;
            }
        }

        return $ret;
    }

    /*
      Function: fetchRow
      Combines <execute> and <fetch> to a single function for fetching single rows of data.

      Equivalent to using mysql_fetch_object(mysql_query($query)).

      After the row is fetched, all memory associated with the result is freed with mysql_free_result().

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
        mysql_free_result($result);

        return $ret;
    }

    /*
      Function: numRows
      Uses mysql_num_rows on a result set to return the number of rows.

      Parameters:
      $result - A result set from an SQL query.

      Returns:
      The number of rows in the result set.
     */

    public function numRows(&$result)
    {
        return mysql_num_rows($result);
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

    public function insert($table, $data)
    {
        if ($this->isConnected === false) {
            $this->connect();
        }

        $query = 'INSERT INTO ' . $table . ' (';
        $cols = count($data);
        $part1 = ''; //List of column names
        $part2 = ''; //List of question marks for parameter binding
        $params = Array();
        $i = 0; //Counter
        foreach ($data as $col => $val) {
            //Append column name
            $part1 .= mysql_real_escape_string($col, $this->db);

            //Append a question mark and leave sanitation to the <execute> method through variable binding.
            $part2 .= '?';

            $params[] = $val;

            if ($i != ($cols - 1)) {
                $part1 .= ', ';
                $part2 .= ', ';
            }

            //Increment counter
            ++$i;
        }

        $query .= $part1 . ') VALUES (';
        $query .= $part2 . ')';

        $this->execute($query, $params);

        return mysql_insert_id($this->db);
    }

    /*
      Function: affected
      Returns the number of affected rows from the last query executed.

      Returns:
      An integer representing the number of affected rows, or -1 if the query failed.
     */

    public function affected()
    {
        return mysql_affected_rows($this->db);
    }

    /*
      Function: connect
      Connects to a MySQL server and selects a database.

      Parameters:
      $host - Database server
      $username - Username to login with
      $password - Password to login with
      $dbname - Name of database

      Returns:
      True if there were no errors.

      Throws a <DbException> if there was a connection problem.
     */

    protected function connect()
    {
        if ($this->isConnected === false) {
            $this->db = mysql_connect($this->host, $this->username, $this->password);
            if (!$this->db) {
                throw new DbException($this->db, SERVER_ERROR);
            } else {
                $this->isConnected = true;

                $db_selected = mysql_select_db($this->dbname);
                if (!$db_selected) {
                    throw new DbException($this->dbname, DATABASE_ERROR);
                } else {
                    return true;
                }
            }
        } else {
            return true;
        }
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

    function update($table, $fields, $where)
    {
        if ($this->isConnected === false) {
            $this->connect();
        }

        $table = str_replace('<ezrpg>', DB_PREFIX, $table);
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
        $sql = "Update " . mysql_real_escape_string($table, $this->db) . " SET " . $var . " WHERE " . $where;

        return $this->execute($sql);
    }

}

?>
