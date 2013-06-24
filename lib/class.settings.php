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
		$this->setting = $this->get_settings($this->settings);
    }
	
	public function get_settings_by($column, $value, $setting)
	{
		$settings = array();
		
		foreach ( $setting as $item => $val )
		{
			if ( $val->$column == $value )
			{
				return array('id' => $val->id, 'value' => $val->value, 'title' => $val->title, 'optionscode' => $val->optionscode);
			}
		}
	}
	
	public function get_settings($setting)
	{
		$settings = array();
		$special = array('select', 'radio');
		foreach ( $setting as $items => $vals )
		{
			if ( $vals->gid == 0 ) 
			{
				foreach ( $setting as $item => $val )
				{
					if ( $val->gid == $vals->id ) // checks if current setting is related to current group
					{
						if ( in_array($val->optionscode, $special, true) ) // checks if current setting is a Radio or Select
						{
							$settings[$vals->name][$val->name] = array('id' => $val->id, 'value' => $this->get_settings_by('id', $val->value,$setting) , 'title' => $val->title, 'optionscode' => $val->optionscode);
						} else {
							$settings[$vals->name][$val->name] = array('id' => $val->id, 'value' => $val->value, 'title' =>$val->title, 'optionscode' => $val->optionscode);
						}
					}
				}
			}
		}
		return $settings;
	}
}
?>
