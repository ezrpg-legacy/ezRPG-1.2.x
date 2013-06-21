<?php
defined('IN_EZRPG') or exit;

/*
Class: Menu
A class to handle the menu system
*/
class Settings
{
    /*
    Variable: $db
    Contains the database object.
    */
    protected $db;
    
    
    /*
    Variable: $settings
    An array of all settings.
    */
    protected $settings;
    
    /*
    Function: __construct
    The constructor takes in database, template and player variables to pass onto any hook functions called.
    
    Parameters:
    $db - An instance of the database class.
    $tpl - A smarty object.
    $player - A player result set from the database, or 0 if not logged in.
    */
    public function __construct(&$db)
    {
        $this->db =& $db;
        $query      = $this->db->execute('SELECT * FROM `<ezrpg>settings`');
        $this->settings = $db->fetchAll($query);
    }
    
	public function get_settings_by_cat_name($catName)
	{
		$setting = $this->settings;
		$category = array();
		$settings = array();
		foreach ( $setting as $item => $val )
		{
			$category[$item] = $val;
			if ( $val->name == $catName ) 
			{
				$category[$val->name]['id'] = $val->id;
			}
		}
		foreach ( $setting as $item => $val )
		{
			if ( $val->gid == $category[$catName]['id'] )
			{
				$settings[$val->name] = $val->value;
			}
		}
		if (!empty($settings)){
			return $settings;
		}else{
			return false;
		}
	}
	
	public function get_settings_by_id($catName)
	{
		$setting = $this->settings;
		$settings = array();
		
		foreach ( $setting as $item => $val )
		{
			if ( $val->id == $catName )
			{
				$settings['value'] = $val->value;
			}
		}
		if (!empty($settings)){
			return $settings;
		}else{
			return false;
		}
	}
}
?>
