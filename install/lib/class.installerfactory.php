<?php
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: ModuleFactory
  Factory class for modules.
*/
class InstallerFactory
{
    /*
      Function: module
      A class for creating module objects.
      Includes the file /install/modules/$module/index.php then creates a new instance of the class Module_$module.
      
      *Example*: index.php?step=Index
      
      Looks for /admin/modules/Index/index.php, then creates new Install_Index() object.
      
      Parameters:
      $db - An instance of the database object.
      $tpl - An instance of the smarty object.
      $module - Name of the module, defaults to Index.
	
      Returns:
      A new instance of a module class.
	
      Shows the Index module if the specified module cannot be found.
      
      Example Usage:
      > $new_module = ModuleFactory::factory($db, $tpl, $player);
      > $new_module->start();
    */
    public static function module(&$installer, $module='Index')
    {
		if(file_exists('./lock')){
			$installer->header();
			echo "<h2>The installer is locked</h2>\n";
			echo "<p>Unlock the installer by remove the 'lock' file in the install directory</p>";
			$installer->footer();
			die();
		}
        if (file_exists(MOD_DIR . '/' . $module . '/index.php'))
        {
            include_once (MOD_DIR . '/' . $module . '/index.php');
            $classname = 'Install_' . $module;
            return new $classname();
        }
        else
        {
            // Default module to display (the home page)
            include_once (MOD_DIR . '/Index/index.php');
            return new Install_Index();
        }
    }
	public static function header()
	{
    echo <<<HEAD
<html>
	<head>
		<title>ezRPG Installation</title>
		<link rel="stylesheet" href="../static/default/style.css" type="text/css" />
		<style>
			#content
			{
			  width: 50%;
			  margin: auto;
			  font: 1.0em Verdana, Arial, Sans-serif;
			  color: #444;
			  padding: 10px;
			  border: 1px solid #3182C0;
			}
		</style>
	</head>

	<body>
		<div id="content">
		<h1>ezRPG Installation</h1>

HEAD;
	}
	public static function footer()
	{
    echo <<<FOOT
		</div>
	</body>
</html>
FOOT;
	}
}
?>