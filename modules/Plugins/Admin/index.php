<?php

namespace ezRPG\Modules\Plugins\Admin;

use \ezRPG\lib\Base_Module;

defined('IN_EZRPG') or exit;
require_once(LIB_DIR . '/pclzip.lib.php');

/*
  Class: Admin_Plugins
  Admin page for managing plugins and modules
 */

class Admin_Plugins extends Base_Module
{
    /*
      Function: start
      Displays the list of modules/plugins.
     */

    public function start()
    {

        if (isset($_GET['act'])) {
            switch ($_GET['act']) {
                case 'view':
                    $this->view_modules($_GET['id']); //TODO:View Modules
                    break;
                case 'adminOnly':
                    $this->list_modules(true);
                    break;
                case 'install':
                    $this->install_modules($_GET['name']);
                    break;
                case 'upload':
                    $this->upload_modules(); //Completed:Upload Modules
                    break;
                case 'remove':
                    $this->remove_modules($_GET['id']); //TODO:Remove Modules
                    break;
                case 'list':
                    $this->list_modules(); //Completed:Lists Modules
                    break;
                case 'enable':
                    $this->enable_modules($_GET['id']);
                    break; //TODO:activate plugins after they've been installed.
                case 'disable':
                    $this->disable_modules($_GET['id']);
                    break; //TODO:deactivate plugins but not remove them.
            }
        } else {
            $this->list_modules();
        }
    }


    /**
     * List all modules, scan dirs for new additions, show status of installation/activation.
     * Combines Jester's ModuleInfo with my database idology.
     *
     * Todo: Needs to cache the results of active modules
     * 
     */
    private function list_modules($adminOnly = false)
    {
        $cached = $this->setModuleCache($adminOnly);
        $this->tpl->assign('adminOnly', $adminOnly);
        $this->tpl->assign("plugins", $cached);
        $this->loadView('plugins.tpl');
    }

    /**
     * Scans the Module Directory for all modules
     * Ported for JesterC's ModuleInfo and acquired from EdwardBlack13's Repo for ezRPG1.0.x 
     */
    private function scanGameModules($mod = '')
    {
        // Grab all files and directories in the modules folder
        $scan = scandir(MOD_DIR);

        // Check if there are any modules present
        $modules = array();
        $modulesInfo = array();
        foreach ($scan AS $module) {
            if (is_dir(MOD_DIR . $module) && $module !== "." && $module !== ".." && !strpos($module, '.php'))
            {
                $modules[] = $module;
                // Make sure it's in proper Module structure format with the index.php
                if(file_exists(MOD_DIR . $module . '/index.php')) {
                    // Check if any of the modules has a Module_Info.txt file.
                    if (fopen(MOD_DIR . $module . "/Module_Info.txt", "r") != false) {
                        // Okay, let's parse the file
                        $contents = file_get_contents(MOD_DIR . $module . "/Module_Info.txt");
                        $contents = explode(":", $contents);

                        $modulesInfo[$module] = array(
                            "name" => $contents[2],
                            "ver" => $contents[4],
                            "desc" => $contents[6],
                            "author" => $contents[8]
                        );
                    } else {

                        $modulesInfo[$module] = array(
                            "name" => $module,
                            "ver" => "?",
                            "desc" => "No Module_Info.txt File",
                            "author" => "Unknown"
                        );
                    }
                }
            }
        }
        return $modulesInfo;
    }

    /**
     * Scans the Module Directory for all modules
     * Ported for JesterC's ModuleInfo and acquired from EdwardBlack13's Repo for ezRPG1.0.x
     */
    private function scanAdminModules()
    {
        // Grab all files and directories in the modules folder
        $scan = scandir(MOD_DIR);

        // Check if there are any modules present
        $modules = array();
        $modulesInfo = array();
        foreach ($scan AS $mod) {
            if (is_dir(MOD_DIR . $mod) && $mod !== "." && $mod !== ".." && !strpos($mod, '.php'))
            {
                $adm = scandir(MOD_DIR . '/'. $mod . '/Admin');
                foreach($adm as $module) {
                    if ($module !== "." && $module !== ".." && !strpos($module, '.php')) {
                        $modules[] = $mod;
                        // Make sure it's in proper Module structure format with the index.php
                        if (file_exists(MOD_DIR . '/'. $mod . '/Admin/index.php')) {
                            // Check if any of the modules has a Module_Info.txt file.
                            if (fopen(MOD_DIR . '/'. $mod . "/Admin/Module_Info.txt", "r") != false) {
                                // Okay, let's parse the file
                                $contents = file_get_contents(MOD_DIR . '/'. $mod . "/Admin/Module_Info.txt");
                                $contents = explode(":", $contents);

                                $modulesInfo[$mod] = array(
                                    "name" => $contents[2],
                                    "ver" => $contents[4],
                                    "desc" => $contents[6],
                                    "author" => $contents[8]
                                );
                            } else {

                                $modulesInfo[$mod] = array(
                                    "name" => $mod,
                                    "ver" => "?",
                                    "desc" => "No Module_Info.txt File",
                                    "author" => "Unknown"
                                );
                            }
                        }
                    }
                }
            }
        }
        return $modulesInfo;
    }

    private function getGameModulesDB()
    {
        $query = $this->db->execute('select `id`, `title`, `installed`, `active` from <ezrpg>plugins');
        $plugins = Array();
        while ($m = $this->db->fetch($query)) {
            $plugins[$m->title] = array('installed' => (bool) $m->installed, 'active' => (bool) $m->active, 'id'=> $m->id);
        }
        return $plugins;
    }

    private function getModuleCache($admin = false)
    {
        if(file_exists(CACHE_DIR . '/module_cache')){
            $cache = file_get_contents(CACHE_DIR . '/module_cache');
            $plugins = unserialize($cache);
            if(!$admin)
                return $plugins['public'];
            else
                return $plugins['admin'];
        }else{
            return $this->setModuleCache();
        }
    }

    public function setModuleCache($adminonly=false)
    {
        $modules = $this->scanGameModules();
        $admin = $this->scanAdminModules();
        $moddb = $this->getGameModulesDB();
        $public = array('public'=>array_merge_recursive($modules, $moddb));
        $admin = array('admin' => array_merge_recursive($admin, $moddb));
        $plugins = array_merge($public, $admin);
        $cache = serialize($plugins);
        file_put_contents(CACHE_DIR . '/module_cache', $cache);
        return $this->getModuleCache($adminonly);
    }

    private function enable_modules($id)
    {
        $query = $this->db->execute('UPDATE <ezrpg>plugins SET <ezrpg>plugins.active = 1 WHERE <ezrpg>plugins.id = ' . $id);
        $this->db->fetch($query);
        $query2 = $this->db->execute('UPDATE <ezrpg>menu SET <ezrpg>menu.active = 1 WHERE <ezrpg>menu.module_id = ' . $id);
        $this->db->fetch($query2);
        killMenuCache();
        $this->setMessage("Module enabled!");
        header('Location: index.php?mod=Plugins');
        exit;
    }

    private function disable_modules($id)
    {
        $query = $this->db->execute('UPDATE <ezrpg>plugins SET <ezrpg>plugins.active = 0 WHERE <ezrpg>plugins.id = ' . $id);
        $this->db->fetch($query);
        $query2 = $this->db->execute('UPDATE <ezrpg>menu SET <ezrpg>menu.active = 0 WHERE <ezrpg>menu.module_id = ' . $id);
        $this->db->fetch($query2);
        killMenuCache();
        $this->setMessage("Module disabled!");
        header('Location: index.php?mod=Plugins');
        exit;
    }
    
    private function install_modules($name){
        if(file_exists(MOD_DIR . '/' . $name . '/index.php'))
            $module = \ezRPG\lib\ModuleFactory::factory($this->container, $name);
        elseif(file_exists(MOD_DIR . '/' . $name . '/Admin/index.php'))
            $module = \ezRPG\lib\ModuleFactory::adminFactory($this->container, $name);

        if (method_exists($module, 'install')) {
            $reflection = new \ReflectionMethod($module, 'install');
            if ($reflection->isPublic()) {
                $this->setMessage("You've installed " . $name);
                $module->install();
                header('Location: index.php?mod=Plugins');
            } else {
                $this->setMessage("This module's installer function is Private", "warn");
                header('Location: index.php?mod=Plugins');
            }
        } else {
            $query = $this->db->execute('UPDATE <ezrpg>plugins SET <ezrpg>plugins.installed = 1 WHERE <ezrpg>plugins.title = "'.$name.'"');
            $this->db->fetch($query);
            $this->setMessage("You've installed " . $name);
            header('Location: index.php?mod=Plugins');
        }
    }

    private function upload_modules()
    {
        killSettingsCache();
        if (isset($_FILES['file'])) {
            if ($_FILES["file"]["error"] > 0) {
                $this->tpl->assign("MSG", "Error: " . $_FILES["file"]["error"]);
                $this->loadView('upload_plugins.tpl');
            } else {
                $type = $_FILES["file"]["type"];
                $okay = false;
                $accepted_types = array(
                    'application/zip',
                    'application/x-zip-compressed',
                    'multipart/x-zip',
                    'application/x-compressed'
                );
                foreach ($accepted_types as $mime_type) {
                    if ($mime_type == $type) {
                        $okay = true;
                        break;
                    }
                }
                if ($okay) {
                    $zip = new PclZip($_FILES["file"]["tmp_name"]);
                    $ziptempdir = substr(uniqid('', true), -5);
                    $dir = "Plugins/temp/" . $ziptempdir;
                    $results = "";
                    if ($zip->extract(PCLZIP_OPT_PATH, $dir) == 0) {
                        $results .= "Error : " . $zip->errorInfo(true);
                    }
                    $results .= "Plugin extracted to " . $dir . "<br />";
                    if (file_exists($dir . "/plugin.xml")) {
                        $results .= "this is a proper module/plugin. <br />";
                        $plug = simplexml_load_file($dir . "/" . "plugin.xml");
                        $p_d['title'] = (string)$plug->Plugin->Name;
                        $p_d['type'] = (string)$plug->Plugin->Type;
                        $p_d['filename'] = (string)$plug->Plugin->File;
                        $p_m['version'] = (string)$plug->Plugin->Version;
                        $p_m['author'] = (string)$plug->Plugin->Author;
                        $p_m['description'] = (string)$plug->Plugin->Description;
                        $p_m['url'] = (string)$plug->Plugin->AccessURL;
                        $p_m['plug_id'] = $this->db->insert('<ezrpg>plugins', $p_d);
                        $p_m['meta_id'] = $this->db->insert('<ezrpg>plugins_meta', $p_m);
                        if (!empty($plug->Plugin->Hook)) {
                            if (!empty($plug->Plugin->Hook->HookFile)) {
                                foreach ($plug->Plugin->Hook->HookFile as $hooks) {
                                    $hook['pid'] = $p_m['plug_id'];
                                    $hook['filename'] = (string)$hooks->HookFileName;
                                    $hook['title'] = (string)$hooks->HookTitle;
                                    $hook['type'] = 'hook';
                                    $this->db->insert('<ezrpg>plugins', $hook);
                                }
                            }
                        }
                        if (!empty($plug->Plugin->Lib)) {
                            if (!empty($plug->Plugin->Lib->LibFile)) {
                                foreach ($plug->Plugin->Lib->LibFile as $libs) {
                                    $lib['pid'] = $p_m['plug_id'];
                                    $lib['filename'] = (string)$libs->LibFileName;
                                    $lib['title'] = (string)$libs->LibTitle;
                                    $lib['type'] = 'library';
                                    $this->db->insert('<ezrpg>plugins', $lib);
                                }
                            }
                        }
                        if (!empty($plug->Plugin->Theme)) {
                            if (!empty($plug->Plugin->Theme->ThemeFolder)) {
                                foreach ($plug->Plugin->Theme->ThemeFolder as $theme) {
                                    $theme_m['pid'] = $p_m['plug_id'];
                                    $theme_m['filename'] = (string)$theme->ThemeFolder;
                                    $theme_m['title'] = (string)$theme->ThemeTitle;
                                    $theme_m['type'] = 'templates';
                                    $this->db->insert('<ezrpg>plugins', $theme_m);
                                }
                            }
                        }
                        if (!empty($plug->Plugin->Menus)) {
                            if (!empty($plug->Plugin->Menus->Menu)) {
                                foreach ($plug->Plugin->Menus->Menu as $menu) {
                                    $menusys = $this->menu;
                                    $newMenu = $menu;
                                    $menu_p['module_id'] = $p_m['plug_id'];
                                    $menu_p['parent_id'] = ($menusys->isMenu((string)$newMenu->MenuParent) ? $menusys->get_menu_id_by_name((string)$newMenu->MenuParent) : '0');
                                    $menu_p['title'] = (string)$newMenu->Title;
                                    $menu_p['uri'] = (string)$newMenu->URL;
                                    $menu_id = $this->db->insert('<ezrpg>menu', $menu_p);
                                    if (!empty($newMenu->MenuChildren)) {
                                        if (!empty($newMenu->MenuChildren->Child)) {
                                            foreach ($newMenu->MenuChildren->Child as $childMenu) {
                                                $menu_c['module_id'] = $p_m['plug_id'];
                                                $menu_c['parent_id'] = $menu_id;
                                                $menu_c['title'] = (string)$childMenu->Title;
                                                $menu_c['uri'] = (string)$childMenu->URL;
                                                $this->db->insert('<ezrpg>menu', $menu_c);
                                            }
                                        }
                                    }
                                }
                                killMenuCache();
                            }
                        }
                        if (!empty($plug->Plugin->Settings)) {
                            if (!empty($plug->Plugin->Settings->Setting)) {
                                foreach ($plug->Plugin->Settings->Setting as $setting) {
                                    killSettingsCache();
                                    $newSetting = $setting;
                                    $setting_p['name'] = (string)$newSetting->Name;
                                    $setting_p['title'] = (string)$newSetting->Title;
                                    $setting_p['description'] = (string)$newSetting->Description;
                                    $setting_p['value'] = (string)$newSetting->Value;
                                    if (!empty($newSetting->Group)) {
                                        $query = $this->db->execute("SELECT id from <ezrpg>settings where name='" . (string)$newSetting->Group . "'");
                                        $setting_p['gid'] = $this->db->fetch($query)->id;
                                    } elseif (!empty($newSetting->GroupID)) {
                                        $setting_p['gid'] = (string)$newSetting->GroupID;
                                    }
                                    $setting_id = $this->db->insert('<ezrpg>settings', $setting_p);
                                    $setting_u['uninstall'] = addslashes("DELETE FROM <ezrpg>settings WHERE id ='" . $setting_id . ";");
                                    $setting_u['pm_id'] = $p_m['meta_id'];
                                    $this->db->insert('<ezrpg>plugins_meta', $setting_u);
                                }
                            }
                        }
                        if (!empty($plug->Plugin->SQL)) {
                            if (!empty($plug->Plugin->SQL->Install)) {
                                if (!empty($plug->Plugin->SQL->Install->SQLCALL)) {
                                    foreach ($plug->Plugin->SQL->Install->SQLCALL as $sql) {
                                        $this->db->execute((string)$sql);
                                    }
                                }
                            }
                            if (!empty($plug->Plugin->SQL->Uninstall)) {
                                if (!empty($plug->Plugin->SQL->Uninstall->SQLCALL)) {
                                    foreach ($plug->Plugin->SQL->Uninstall->SQLCALL as $sql) {
                                        $this->db->execute("INSERT INTO <ezrpg>plugins_meta (plug_id, pm_id, uninstall) VALUES ('" . $p_m['plug_id'] . "', '" . $p_m['meta_id'] . "', '" . addslashes((string)$sql) . "')");
                                    }
                                }
                            }
                        }
                        $results .= "installed db data <br />";
                        if (file_exists($dir . '/modules')) {
                            $this->re_copy($dir . '/modules/', MOD_DIR);
                        }
                        if (file_exists($dir . '/lib')) {
                            $this->re_copy($dir . '/lib/', LIB_DIR);
                        }
                        if (file_exists($dir . '/hooks')) {
                            $this->re_copy($dir . '/hooks/', HOOKS_DIR);
                        }
                        if (file_exists($dir . '/admin')) {
                            $this->re_copy($dir . '/admin/', ADMIN_DIR);
                        }
                        if (file_exists($dir . '/templates')) {
                            $this->re_copy($dir . '/templates/', THEME_DIR);
                        }

                        $this->rrmdir($dir);
                        $results .= "You have successfully uploaded a plugin via the manager! <br />";
                        killModuleCache();
                        $this->db->execute('UPDATE <ezrpg>plugins SET installed=1, active=1 WHERE id=' . $p_m['plug_id'] . ' OR pid=' . $p_m['plug_id']);
                        $this->db->execute('UPDATE <ezrpg>menu SET active=1 WHERE module_id=' . $p_m['plug_id']);

                        if (!empty($plug->Plugin->AccessURL)) {
                            $mod_url = $this->settings->setting['general']['site_url']['value'];
                            $mod_url .= (string)$plug->Plugin->AccessURL;
                        }
                        $results .= "<a href='index.php?mod=Plugins'><input name='back' type='submit' class='button' value='Back to manager' /></a>";
                    } else {
                        $results .= $dir . "/" . pathinfo($_FILES['file']['name'],
                                PATHINFO_FILENAME) . ".xml was not found <br />";
                        $results .= "This is not a valid Plugin <br />";
                        $this->rrmdir($dir);
                        $results .= "All temporary files have been deleted. <br />";
                        $results .= "<a href='index.php?mod=Plugins'><input name='login' type='submit' class='button' value='Back To Manager' /></a>";
                    }
                } else {
                    $results = "Filetype detected: " . $_FILES['file']['type'] . '<br />';
                    $results .= "Uploaded Unsupported filetype. Only upload .zips at this time.<br />";
                    $results .= "If you feel that this message is in error, please ask for support from ezRPGProject.net!<br />";
                    $results .= "<a href='index.php?mod=Plugins'><input name='login' type='submit' class='button' value='Back To Manager' /></a>";
                }
                $this->tpl->assign("RESULTS", $results);
                $this->loadView('plugin_results.tpl');
            }
        } else {
            $this->loadView('upload_plugins.tpl');
        }
    }

    private function remove_modules($name)
    {
        if(file_exists(MOD_DIR . '/' . $name . '/index.php'))
            $module = \ezRPG\lib\ModuleFactory::factory($this->container, $name);
        elseif(file_exists(MOD_DIR . '/' . $name . '/Admin/index.php'))
            $module = \ezRPG\lib\ModuleFactory::adminFactory($this->container, $name);

        if (method_exists($module, 'uninstall')) {
            $reflection = new \ReflectionMethod($module, 'uninstall');
            if ($reflection->isPublic()) {
                $this->setMessage("You've installed " . $name);
                $module->uninstall();
                $this->list_modules();
            } else {
                $this->setMessage("This module's uninstaller function is Private", "warn");
                $this->list_modules();
            }
        } else {
            $query = $this->db->execute('UPDATE <ezrpg>plugins SET <ezrpg>plugins.installed = 0 WHERE <ezrpg>plugins.title = "'.$name.'"');
            $this->db->fetch($query);
            $this->setMessage("You've uninstalled " . $name);
            $this->list_modules();
        }
    }

    private function finish_removal($id, $result)
    {
        try {
            foreach ($result as $file) {
                switch ($file->type) {
                    case 'module':
                        $this->rrmdir(MOD_DIR . $file->title);
                        break;
                    case 'templates':
                        $this->rrmdir(THEME_DIR . $file->filename . '/' . $file->title);
                        break;
                    case 'hook':
                        if (file_exists(HOOKS_DIR . '/' . $file->filename)) {
                            unlink(HOOKS_DIR . '/' . $file->filename);
                        }
                        break;
                }
            }
            $this->db->execute('DELETE FROM <ezrpg>plugins WHERE pid=' . $id . ' OR id=' . $id);
            $this->db->execute('DELETE FROM <ezrpg>plugins_meta WHERE plug_id=' . $id);
            $this->db->execute('DELETE FROM <ezrpg>menu WHERE module_id=' . $id);
            killMenuCache();

            return true;
        } catch (Exception $e) {
            $this->setMessage($e, 'FAIL');

            return false;
        }
    }

    private function view_modules($id = 0)
    {
        if ($id == 0) {
            $this->list_modules();
            exit;
        }
    }

    private function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        $this->rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    private function re_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->re_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
