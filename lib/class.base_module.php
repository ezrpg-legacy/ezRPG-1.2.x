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
	  Variable: $name
	  The name variable used in loadView()
	*/	
	protected $name;
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
		$this->name = get_class($this);
    }
	
	/*
	  Function: getTheme
	  Retrieves and sets the chosen theme for the overall game.
	  
	  Paramaters:
	  $theme - Which theme type
	  
	  Returns:
	  The currently selected theme.

     */
	public function getTheme($theme = 'default'){
		if(defined('IN_ADMIN')){
			$this->theme = 'admin';
		}else{
			$query = $this->db->execute('SELECT name FROM <ezrpg>themes WHERE enabled=1');
			$this->theme = $this->db->fetch($query);
			if(is_null($this->theme->name)){
				$this->theme = 'default';
			}else{
				$this->theme = $this->theme->name;
			}
		}
		$this->tpl->assign('THEMEDIR', 'templates/themes/' . $this->theme . '/');
		$this->tpl->assign('THEME', $this->theme);
		return $this->theme;
	}

	/*
	  Function: loadView
	  Loads a specific view file
	  
	  Paramaters:
	  $tpl - The template file to load
	  $modtheme - Loads specified module theme (optional)
	  
	  Notes:
	  modtheme should get it's variable via module class, not through
	  developers input in the loadView. (Fix later)
	 */	
	public function loadView($tpl, $modtheme = ''){
		if(file_exists( THEME_DIR . '/themes/'. $this->theme . '/' .$tpl) === TRUE){
			$this->tpl->display('file:['. $this->theme. ']' .$tpl);
		}else{
			if (array_key_exists($modtheme, $this->tpl->getTemplateDir())){
				$this->tpl->display('file:['. $modtheme. ']' .$tpl);
			}else{
				header('Location: index.php?mod=Error404&msg=' . urlencode($msg));
				exit;
			}
		}
	}

	/*
	  Function: getPlugin
	  Gets ID of specified plugin
	  
	  Paramaters:
	  $plugin_name - The plugin to find
	  
	 */		
	public function getPlugin($plugin_name = ''){
		if ( $plugin_name != '' ) {
			$query = $this->db->execute("SELECT * FROM <ezrpg>plugins WHERE title = '".$plugin_name."'");
			$result = $this->db->fetch($query);
			return $result;
		}
	}
}
?>
