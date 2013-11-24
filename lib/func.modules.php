<?php

if ( !defined('IN_EZRPG') )
    exit;

/*
  Function: isModuleActive
  checks if module is in Module array

  Paramaters:
  $name - title of the module checking
  $modules - optional param for the modulecache

  Returns:
  BOOL - TRUE/FALSE.
 */

function isModuleActive($name, $modules = 0)
{
    if ( $modules == 0 )
        $modules = (array) loadModuleCache();

    foreach ( $modules as $key => $item )
    {
        if ( in_array($name, (array) $item) )
            return true;
    }
    return false;
}

/*
  Function: setModuleActive
  sets Module to Active state

  Paramaters:
  $name - title of the module checking
  $modules - optional param for the modulecache

  Returns:
  BOOL - TRUE/FALSE.
 */
function setModuleActive($name, $modules = 0)
{
    global $dbase;

    if ( $modules == 0 )
        $modules = (array) loadModuleCache();

    $dbase->execute('UPDATE `<ezrpg>plugins` SET `active`=1 WHERE `title`=?', array( $name ));
    killModuleCache();
    setMenuActive($name);
    return true;
}

/*
  Function: setModuleDeactive
  Sets module to Active=0

  Paramaters:
  $name - title of the module checking
  $modules - optional param for the modulecache

  Returns:
  BOOL - TRUE/FALSE.
 */
function setModuleDeactive($name, $modules = 0)
{
    global $dbase;

    if ( $modules == 0 )
        $modules = (array) loadModuleCache();

    $dbase->execute('UPDATE `<ezrpg>plugins` SET  `active`=0 WHERE `title`=?', array( $name ));
    killModuleCache();
    setMenuActive($name);
    return true;
}

/*
  Function: setMenuActive
  Sets menu to Active=1

  Paramaters:
  $name - title of the module checking
  $modules - optional param for the modulecache

  Returns:
  BOOL - TRUE/FALSE.
 */
function setMenuActive($name)
{
    global $dbase;
    $modules = (array) loadModuleCache();
    foreach ( $modules as $key => $item )
    {
        if ( in_array($name, (array) $item) )
        {
            $mod = (array) $item;
            $mod_id = $mod['id'];
            $dbase->execute('UPDATE `<ezrpg>menu` SET `active`=1 WHERE `module_id`=?', array( $mod_id ));
            killMenuCache();
            return true;
        }
    }
    return false;
}

/*
  Function: get_modules
  Main function for getting the module files from its directories

  Credits: Wordpress (wp-admin/includes/plugin.php:361), SimpleRPG (func.modules.php)
  
  Paramaters:
  $fullinfo - BOOL

 */

function get_modules($fullinfo = 0) {
        $module_files = array();
        $other_files = array();
        
        // Files in modules directory (reading dir names)
        $modules_dir = @ opendir(MOD_DIR);
        if ($modules_dir) {
                while (($file = readdir($modules_dir)) !== false ) {
                                // In $other_files array goes all the files that stored in modules folder directly, without subdir - just to handle them too
                                if ( substr($file, -11) == '.module.php' && $file != 'index.php')
                                        $module_files['public'][] = $file;
                }
                closedir($modules_dir);
        }
        // Files in admin directory - for autoadding links for module settings
        $modules_dir = @ opendir(ADMIN_DIR);
        
        if ($modules_dir) {
                while (($file = readdir($modules_dir)) !== false ) {
                        if (substr($file, 0, 1) == '.')
                                continue;
                        if (is_dir( ADMIN_DIR.'/'.$file )) {
                                $modules_subdir = @ opendir( ADMIN_DIR.'/'.$file );
                                if ($modules_subdir && $file != 'Index') {
                                        $module_files['admin'][] = $file;
                                        closedir( $modules_subdir );
                                }
                        }
                }
                closedir($modules_dir);
        }
        
        if (empty($module_files))
                return;
                
        // Getting full information about plugin, parsing its headers and assigning in array as a module name.
        // To get full info about this plugin, you need to call $var['module'][$module_name], where $module_name is module's dir.
        if ($fullinfo) {
                foreach ($module_files['public'] as $module_file) {
                        if( $data = get_module_data(MOD_DIR.$module_file) ) {
                                $module_files['module'][$module_file] = $data;
                        }
                }
        }
		return $module_files;
}

/*
  Function: get_file_data
  Grabs the first 4kB of a module
  
  Credits: Wordpress (wp-includes/functions.php:3294), SimpleRPG (func.modules.php)

  Paramaters:
  $file - Module File to be parsed
  $default_headers - Array of PHPDoc Headers to parse

  Returns:
  $all_headers - Array of parsed strings.
 */
function get_file_data( $file, $default_headers ) {
        // We don't need to write to the file, so just open for reading.
        $fp = fopen( $file, 'r' );

        // Pull only the first 4kiB of the file in.
        $file_data = fread( $fp, 4096 );

        // PHP will close file handle, but we are good citizens.
        fclose( $fp );

        // Make sure we catch CR-only line endings.
        $file_data = str_replace( "\r", "\n", $file_data );

        $all_headers = $default_headers;

        foreach ( $all_headers as $field => $regex ) {
                if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match ) && $match[1] )
                        $all_headers[ $field ] = htmlentities(trim($match[1]));
                else
                        $all_headers[ $field ] = '';
        }

        return $all_headers;
}

/*
  Function: get_module_data
  Parsing the comments for modules metadata
  
  Credits: Wordpress (wp-admin/includes/plugin.php:72), SimpleRPG (func.modules.php)

  Paramaters:
  $module_file - Module File to be parsed

  Returns:
  BOOL - TRUE/FALSE.
 */

function get_module_data( $module_file ) {

        $default_headers = array(
                'Name' => 'Module Name',
                'Description' => 'Description',
                'Author' => 'Author',
                'AuthorURI' => 'Author URI',
                'ModuleURI' => 'Module URI',
                'Version' => 'Version',
                'Package' => 'Package',
				'Class' => 'Class'
        );

        $module_data = get_file_data( $module_file, $default_headers );

        return $module_data;
}

?>
