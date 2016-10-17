<?php

namespace ezrpg\core;

defined('IN_EZRPG') or exit;

/*
  Class: Hooks
  A class to handle adding/running hooks
 */

class Hooks
{
    /*
      Variable: $db
      Contains the database object.
     */

    protected $db;

    /*
      Variable: $tpl
      The smarty template object.
     */
    protected $tpl;

    /*
      Variable: $player
      The currently logged in player. Value is 0 if no user is logged in.
     */
    protected $player;

    /*
      Variable: $hooks
      An array of all hooks, ordered by priority.
     */
    protected $hooks;

    protected $container;

    /*
      Function: __construct
      The constructor takes in database, template and player variables to pass onto any hook functions called.

      Parameters:
      $db - An instance of the database class.
      $tpl - A smarty object.
      $player - A player result set from the database, or 0 if not logged in.
     */

    public function __construct($container)
    {
        global $debugTimer;
        $this->container = $container;
        $this->hooks = array();
        $debugTimer['Hook Class constructed'] = microtime(1);
    }

    /*
      Function: add_hook
      Adds a function to the list of hooks.

      Parameters:
      $hook_name - The name of the hook to add the function to.
      $function_name - The name of the hook function, minus the hook_ prefix.
      $priority - The priority of the hook function. Higher priority (0) gets called first. Default is 5.

      Example:
      This adds a hook to check the user session data to the 'login' hook, with priority 0 so it will run first.
      add_hook('login', 'check_session', 0);
     */

    public function add_hook($hook_name, $function_name, $priority = 5)
    {
        $this->hooks[$hook_name][$priority][] = $function_name;
    }

    /*
      Function: run_hooks
      This will run all the functions added to a particular hook. Functions called are ordered by priority.

      Parameters:
      $hook_name - The name of the hook to run functions for.
      $func_args - An array of arguments to pass to the hook functions. Optional.
     */

    public function run_hooks()
    {
        global $debugTimer;
        $num_args = func_num_args();
        if ($num_args == 0) {
            return;
        }

        //Get function arguments
        $arg_list = func_get_args();

        $hook_name = $arg_list[0];

        $func_args = 0;

        if ($num_args == 2) {
            //Single argument or an rrray of arguments to pass to hook functions
            $func_args = $arg_list[1];
        }

        //This hook doesn't exist!
        if (!array_key_exists($hook_name, $this->hooks)) {
            return $func_args;
        }

        //Sort by priority
        ksort($this->hooks[$hook_name]);
//        if($hook_name == 'header')
//           die(var_dump($this->hooks[$hook_name]));
        //if($hook_name=='header')
        //die(var_dump($this->player));

        foreach ($this->hooks[$hook_name] as $priority) {
            //Call each hook function separately
            foreach ($priority as $hook_function) {
                $call_func = 'hook_' . $hook_function;

                //Debug mode? Show what's going on
                if (DEBUG_MODE == 1) {
                    echo 'Calling hook: ', $call_func, '<br />';
                }

                if (!function_exists($call_func)) {
                    continue;
                }

                //Hook should have a return value
                $func_args = call_user_func($call_func, $this->container, $func_args);
                $debugTimer[$call_func] = microtime(1);
            }
        }

        return $func_args;
    }

}

?>
