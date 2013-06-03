<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Base_Module
  The base class for modules. Every module must extend this class.
*/
abstract class Base_Module
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
	  Variable: $menu
	  The menu class
	*/
	
	protected $menu;
	
    
	/*
	  Variable: $settings
	  The settings class
	*/
	
	protected $settings;
	
	
    /*
      Function: __construct
      The constructor the every module. Saves the database, template and player variables as class variables.
      
      Parameters:
      The parameters are passed by reference so that all modules and other code use the same objects.
      
      $db - An instance of the database class.
      $tpl - A smarty object.
      $player - A player result set from the database, or 0 if not logged in.
    */
    public function __construct(&$db, &$tpl, &$player = 0, &$menu, &$settings)
    {
        $this->db = $db;
        $this->tpl = $tpl;
		$this->theme = $this->getTheme();
        $this->player = $player;
		$this->menu = $menu;
		$this->settings = $settings;
    }
	
	public function getTheme($theme = 'default'){
		if(defined('IN_ADMIN')){
			$this->theme = 'admin';
		}else{
			$query = $this->db->execute('SELECT name FROM <ezrpg>themes WHERE enabled=1');
			$this->theme = $this->db->fetch($query);
			$this->theme = $this->theme->name;
		}
		return $this->theme;
	}
	
	public function loadView($tpl){
		$this->tpl->display('file:['. $this->theme. ']' .$tpl);
	}
}
?>
