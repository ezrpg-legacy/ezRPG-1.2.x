<?php

defined('IN_EZRPG') or exit;

/*
  Class: Players
  A class to handle Players
 */

class Players
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
      Function: __construct
      The constructor takes in database, template and player variables to pass onto any hook functions called.

      Parameters:
      $db - An instance of the database class.
      $tpl - A smarty object.
      $player - A player result set from the database, or 0 if not logged in.
     */

    public function __construct(&$db, &$tpl, &$player = 0)
    {
        $this->db = & $db;
        $this->tpl = & $tpl;
        $this->player = & $player;
    }
	
	public function updateMeta($data, $id)
	{
		$this->db->update('<ezrpg>players_meta', $data, 'id='.$id);
		killPlayerCache($id);
		return true;
	}
	
	public function createMeta($id)
	{
		return $this->db->insert('<ezrpg>players_meta', array('pid'=>$id));
	}
	
	public function register($username, $password, $email)
	{
		global $settings, $hooks;
		
		$insert = Array( );
		//Add new user to database
		$insert['username'] = $username;
		$insert['email'] = $email;
		$insert['secret_key'] = createKey(16);
		$insert['password'] = createPassword($insert['secret_key'], $password);
		$insert['pass_method'] = $settings->setting['general']['pass_encryption']['value']['value'];
		$insert['registered'] = time();
		//Run register hook
		$insert = $hooks->run_hooks('register', $insert);
		$playerID = $this->db->insert('<ezrpg>players', $insert);
		$this->createMeta($playerID);
		return $playerID;
	}
}
?>
