<?php

namespace ezrpg\core;

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: ModuleFactory
  Factory class for modules.
 */

class ModuleFactory
{
    /*
      Function: factory
      A factory class for creating module objects.
      Includes the file /modules/$module/index.php then creates a new instance of the class Module_$module.

     * Example*: index.php?module=Index

      Looks for /modules/Index/index.php, then creates new Module_Index() object.

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

    public static function factory($container, $module = 'Index')
    {
        if (file_exists(MOD_DIR . '/' . $module . '/index.php')) {
            include_once(MOD_DIR . '/' . $module . '/index.php');
            $classname = 'ezrpg\\Modules\\Module_' . $module;

            return new $classname($container);
        } else {
            return false;
        }
    }

    /*
      Function: adminFactory
      A factory class for creating admin modules.
      Basically the same function as <factory>.

      Parameters:
      $db - An instance of the database object.
      $tpl - An instance of the smarty object.
      $module - Name of the module, defaults to Index.

      Returns:
      A new instance of an admin module class.

      Shows the Admin_Index module if the specified module cannot be found.

      Example Usage:
      > $admin_module = ModuleFactory::adminFactory($db, $tpl, $player);
      > $admin_module->start();
     */

    public static function adminFactory($container, $module = 'Index')
    {
        if (file_exists(MOD_DIR . '/' . $module . '/Admin/index.php')) {
            include_once(MOD_DIR . '/' . $module . '/Admin/index.php');
            $classname = 'ezrpg\\Modules\\' . $module . '\\Admin\\Admin_' . $module;

            return new $classname($container);
        } else {
            include_once(MOD_DIR . '/Index/Admin/index.php');
            $container['tpl']->getTemplateDir('admin');

            return new Admin_Index($container);
        }
    }

}