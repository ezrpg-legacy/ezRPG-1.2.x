<?php

namespace ezrpg\Modules\Plugins\Admin;

use \ezrpg\core\Base_Module;

defined('IN_EZRPG') or exit;

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

    /**
     * Basic entry of the module
     *
     * Checks the $_GET['act'] for which sub-function to execute
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
     * List all Modules
     * 
     * List all modules, scan dirs for new additions, show status of installation/activation.
     * Combines Jester's ModuleInfo with my database idology.
     *
     * Loads a view (/templates/themes/admin/plugins.tpl)
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
     * 
     * Ported for JesterC's ModuleInfo and acquired from EdwardBlack13's Repo for ezRPG1.0.x 
     * 
     * @return array | Array of modules found in /modules
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
                    if (file_exists(MOD_DIR . $module . "/Module_Info.txt") && fopen(MOD_DIR . $module . "/Module_Info.txt", "r") != false) {
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
     * 
     * Ported for JesterC's ModuleInfo and acquired from EdwardBlack13's Repo for ezRPG1.0.x
     * 
     * @return array | Array of modules found in /modules/<Module>/Admin/*
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
                $adm = scandir(MOD_DIR . $mod . '/Admin');
                foreach($adm as $module) {
                    if ($module !== "." && $module !== ".." && !strpos($module, '.php')) {
                        $modules[] = $mod;
                        // Make sure it's in proper Module structure format with the index.php
                        if (file_exists(MOD_DIR . $mod . '/Admin/index.php')) {
                            // Check if any of the modules has a Module_Info.txt file.
                            if (fopen(MOD_DIR . $mod . "/Admin/Module_Info.txt", "r") != false) {
                                // Okay, let's parse the file
                                $contents = file_get_contents(MOD_DIR . $mod . "/Admin/Module_Info.txt");
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

    /**
     * Loads the module information from the Database
     * 
     * @return array | Array of modules with "id", "title", "installed", and "active" from DB
     */
    private function getGameModulesDB()
    {
        $query = $this->db->execute('select `id`, `title`, `installed`, `active` from <ezrpg>plugins');
        $plugins = Array();
        while ($m = $this->db->fetch($query)) {
            $plugins[$m->title] = array('installed' => (bool) $m->installed, 'active' => (bool) $m->active, 'id'=> $m->id);
        }
        return $plugins;
    }

    /**
     * @param bool $admin
     * @return mixed
     */
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

    /**
     * @param bool $adminonly
     * @return mixed
     */
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

    /**
     * @param $id
     * @throws \ezrpg\core\Exception
     */
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

    /**
     * @param $id
     */
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

    /**
     * @param $name
     */
    private function install_modules($name){
        if(file_exists(MOD_DIR . $name . '/index.php'))
            $module = \ezrpg\core\ModuleFactory::factory($this->container, $name);
        elseif(file_exists(MOD_DIR . $name . '/Admin/index.php'))
            $module = \ezrpg\core\ModuleFactory::adminFactory($this->container, $name);

        $modQuery = $this->db->execute('SELECT `id` from <ezrpg>plugins WHERE <ezrpg>plugins.title = "'. $name .'"');
        $modID = $this->db->fetch($modQuery);
        if (method_exists($module, 'install')) {
            $reflection = new \ReflectionMethod($module, 'install');
            if ($reflection->isPublic()) {
                $this->setMessage("You've installed " . $name);
                if($modID === false) {
                    $item['title'] = $name;
                    $item['installed'] = 1;
                    $plugID = $this->db->insert("<ezrpg>plugins", $item);
                }else{
                    $plugID = $modID->id;
                    $query = $this->db->execute('UPDATE <ezrpg>plugins SET <ezrpg>plugins.installed = 1, <ezrpg>plugins.active = 0 WHERE <ezrpg>plugins.title = "'.$name.'"');
                    $this->db->fetch($query);
                }
                $module->install($plugID);
                killMenuCache();
                header('Location: index.php?mod=Plugins');
            } else {
                $this->setMessage("This module's installer function is Private", "warn");
                header('Location: index.php?mod=Plugins');
            }
        } else {
            $query = $this->db->execute('INSERT INTO <ezrpg>plugins (`title`, `installed`) VALUES ("'.$name.'",1)');
            $this->db->fetch($query);
            $this->setMessage("You've installed " . $name);
            header('Location: index.php?mod=Plugins');
        }
    }

    /**
     *
     */
    private function upload_modules()
    {
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
                    'application/x-compressed',
		    'application/octet-stream'
                );
                foreach ($accepted_types as $mime_type) {
                    if ($mime_type == $type) {
                        $okay = true;
                        break;
                    }
                }
                if ($okay) {
                    $zip = new \PclZip($_FILES["file"]["tmp_name"]);
                    $ziptempdir = substr(uniqid('', true), -5);
                    $dir = MOD_DIR . "/Plugins/Admin/temp/" . $ziptempdir;
                    $results = "";
                    if ($zip->extract(PCLZIP_OPT_PATH, $dir) == 0) {
                        $results .= "Error : " . $zip->errorInfo(true);
                    }

                    if (file_exists($dir . '/modules')) {
                        $this->re_copy($dir . '/modules/', MOD_DIR);
                    }
                    if (file_exists($dir . '/core')) {
                        $this->re_copy($dir . '/core/', CORE_DIR);
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
                    $results .= "<a href='index.php?mod=Plugins'><input name='back' type='submit' class='button' value='Back to manager' /></a>";
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

    /**
     * @param $name
     * @throws \ezrpg\core\Exception
     */
    private function remove_modules($name)
    {
        if(file_exists(MOD_DIR . '/' . $name . '/index.php')) {
            $module = \ezrpg\core\ModuleFactory::factory($this->container, $name);
        }elseif(file_exists(MOD_DIR . '/' . $name . '/Admin/index.php')) {
            $module = \ezrpg\core\ModuleFactory::adminFactory($this->container, $name);
        }

        if (method_exists($module, 'uninstall')) {
            $reflection = new \ReflectionMethod($module, 'uninstall');
            if ($reflection->isPublic()) {
                $this->setMessage("You've uninstalled " . $name);
                $module->uninstall();
                $query = $this->db->execute('UPDATE <ezrpg>plugins SET <ezrpg>plugins.installed = 0, <ezrpg>plugins.active = 0 WHERE <ezrpg>plugins.title = "'.$name.'"');
                $this->db->fetch($query);
                $modIDquery = $this->db->execute('SELECT `id` FROM <ezrpg>plugins WHERE <ezrpg>plugins.title = "'. $name . '"');
                $modID = $this->db->fetch($modIDquery);
                $delMenu = $this->db->execute('DELETE FROM <ezrpg>menu WHERE <ezrpg>menu.module_id = ' . $modID->id);
                killMenuCache();
                $this->list_modules();
            } else {
                $this->setMessage("This module's uninstaller function is Private", "warn");
                $this->list_modules();
            }
        } else {
            $query = $this->db->execute('UPDATE <ezrpg>plugins SET <ezrpg>plugins.installed = 0, <ezrpg>plugins.active = 0 WHERE <ezrpg>plugins.title = "'.$name.'"');
            $this->db->fetch($query);
            $modIDquery = $this->db->execute('SELECT `id` FROM <ezrpg>plugins WHERE <ezrpg>plugins.title = "'. $name . '"');
            $modID = $this->db->fetch($modIDquery);
            $delMenu = $this->db->execute('DELETE FROM <ezrpg>menu WHERE <ezrpg>menu.module_id = ' . $modID->id);
            killMenuCache();
            $this->setMessage("You've uninstalled " . $name);
            $this->list_modules();
        }
    }
    
    /**
     * @param int $id
     */
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
