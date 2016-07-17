<?php

namespace ezRPG\lib;

defined('IN_EZRPG') or exit;

/*
  Class: Router
  A class to handle the Router system
 */

class Router
{
    /*
      Variable: $db
      Contains the database object.
     */

    protected $db;

    /*
      Variable: $player
      The currently logged in player. Value is 0 if no user is logged in.
     */
    protected $player;

    /*
      Variable: $routes
      An array of all routes.
     */
    protected $routes;

    /*
      Variable: $reservedActions
      An array of reserved module actions
    */
    public $reservedActions = array(
        'Install',
        'Uninstall'
    );

    /*
      Function: __construct
      The constructor takes in database, template and player variables to pass onto any hook functions called.

      Parameters:
      $db - An instance of the database class.
      $tpl - A smarty object.
      $player - A player result set from the database, or 0 if not logged in.
     */

    public function __construct(&$db, &$player = 0)
    {
        $this->db = &$db;
        $this->player = &$player;
        $this->routes = $this->getRoutes();
    }

    public function getRoutes()
    {
        $query = 'SELECT * FROM `<ezrpg>router` WHERE active = 1';
        $cache_file = md5($query);

        if (file_exists(CACHE_DIR . $cache_file)) {
            if (filemtime(CACHE_DIR . $cache_file) > time() - 60 * 60 * 24) {
                $array = unserialize(file_get_contents(CACHE_DIR . $cache_file));
                if (DEBUG_MODE == 1) {
                    echo 'Loaded Route Cache! <br />';
                }
            } else {
                unlink(CACHE_DIR . $cache_file);
                $this->getRoutes();

                return;
            }
        } else {
            $query1 = $this->db->execute($query);
            $array = $this->db->fetchAll($query1);
            file_put_contents(CACHE_DIR . $cache_file, serialize($array));
            if (DEBUG_MODE == 1) {
                echo 'Created Route Cache! <br />';
            }
        }

        return $array;
    }

    /*
      Function: add_route
      Adds route to array.

      Returns:
      

      Parameters:
      $url

      Example Usage:
      
     */

    function add_route($url)
    {

    }

    /*
      Function: edit_route
      Applies new values to route
     */

    function edit_route($rid = '')
    {
        //$rid = Route ID
    }

    /*
      Function: delete_route
      Deletes a route to database.

      Parameters:
      $rid (Optional) Route ID

      Example Usage:
      $this->menu->delete_menu(6); //Deletes MenuID #6
      
     */

    function delete_route($rid = null)
    {
        //return $this->db->execute('DELETE FROM `<ezrpg>menu` WHERE id=' . $id);
    }

    /*
      Function: resolve
      Determines if a Route is valid

      Parameters:
      $url

      Example Usage:
      
      
     */

    function resolve($url)
    {
        foreach ($this->routes as $route) {
            $routeMatch = $route->resolve($url);

            if ($routeMatch !== false) {
                return $routeMatch;
            }
        }

        return false;
    }

}

?>
