<?php

defined('IN_EZRPG') or exit;

/*
  Class: Settings
  A class to handle the settings system
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
      The constructor takes in database variable to pass onto any functions called.

      Parameters:
      $db - An instance of the database class.
     */

    public function __construct(&$db)
    {
        $this->db = & $db;
        $this->settings = $this->loadSettings();
        $this->groups = $this->get_settings_by_group($this->settings);
		$this->setting = $this->get_settings($this->settings);
    }

    /*
      Function: get_settings_by
      Function that parses the Settings for specified key

      Parameters:
      $column - An column to check.
      $value - A value to compare
      $setting - An instance of the settings
     */

    public function get_settings_by($column, $value, $setting)
    {
        $settings = array( );

        foreach ( $setting as $item => $val )
        {
            if ( $val->$column == $value )
            {
                return array( 'id' => $val->id, 'value' => $val->value, 'title' => $val->title, 'optionscode' => $val->optionscode );
            }
        }
    }

    public function get_settings_by_group($setting)
    {
        $settings = array( );
        $special = array( 'select', 'radio' );
        foreach ( $setting as $items => $vals )
        {
            if ( $vals->gid == 0 )
            {
				$settings[$vals->name] = array('id'=>$vals->id);
                foreach ( $setting as $item => $val )
                {
                    if ( $val->gid == $vals->id ) // checks if current setting is related to current group
                    {
                        if ( in_array($val->optionscode, $special, true) ) // checks if current setting is a Radio or Select
                        {
                            $settings[$vals->name][$val->name] = array( 'id' => $val->id, 'value' => $this->get_settings_by('id', $val->value, $setting), 'title' => $val->title, 'optionscode' => $val->optionscode );
                        }
                        else
                        {
                            $settings[$vals->name][$val->name] = array( 'id' => $val->id, 'value' => $val->value, 'title' => $val->title, 'optionscode' => $val->optionscode );
                        }
                    }
                }
            }
        }
		return $settings;
    }
	public function get_settings($setting)
    {
        $settings = array( );
        $special = array( 'select', 'radio' );
        foreach ( $setting as $items => $vals )
        {
				$settings[$vals->name] = array('id'=>$vals->id);
                foreach ( $setting as $item => $val )
                {
                    if ( $val->gid == $vals->id ) // checks if current setting is related to current group
                    {
                        if ( in_array($val->optionscode, $special, true) ) // checks if current setting is a Radio or Select
                        {
                            $settings[$vals->name][$val->name] = array( 'id' => $val->id, 'value' => $this->get_settings_by('id', $val->value, $setting), 'title' => $val->title, 'optionscode' => $val->optionscode );
                        }
                        else
                        {
                            $settings[$vals->name][$val->name] = array( 'id' => $val->id, 'value' => $val->value, 'title' => $val->title, 'optionscode' => $val->optionscode );
                        }
                    }
                }
        }
		return $settings;
    }

    public function loadSettings()
    {
        $query = 'SELECT * FROM `<ezrpg>settings`';
        $cache_file = md5($query);

        if ( file_exists(CACHE_DIR . $cache_file) )
        {
            if ( filemtime(CACHE_DIR . $cache_file) > time() - 60 * 60 * 24 )
            {
                $array = unserialize(file_get_contents(CACHE_DIR . $cache_file));
                if ( DEBUG_MODE == 1 )
                {
                    echo 'Loaded Settings Cache! <br />';
                }
            }
            else
            {
                unlink(CACHE_DIR . $cache_file);
                return $this->loadSettings();
            }
        }
        else
        {
            $query1 = $this->db->execute($query);
            $array = $this->db->fetchAll($query1);
            file_put_contents(CACHE_DIR . $cache_file, serialize($array));
            if ( DEBUG_MODE == 1 )
            {
                echo 'Created Settings Cache! <br />';
            }
        }
        return $array;
    }

}

?>
