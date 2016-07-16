<?php

namespace ezRPG\Modules\Plugins\Admin;
use \ezRPG\lib\Base_Module;

defined('IN_EZRPG') or exit;
require_once( LIB_DIR . '/pclzip.lib.php' );
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

        if ( isset($_GET['act']) )
        {
            switch ( $_GET['act'] )
            {
                case 'view':
                    $this->view_modules($_GET['id']); //TODO:View Modules
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
        }
        else
        {
            $this->list_modules();
        }
    }

    private function list_modules()
    {
        $query = $this->db->execute('select * from <ezrpg>plugins JOIN <ezrpg>plugins_meta ON <ezrpg>plugins.id = <ezrpg>plugins_meta.plug_id'); // WHERE <ezrpg>plugins_meta.pm_id=0 //Not sure what this was for -_-
        $plugins = Array( );
        while ( $m = $this->db->fetch($query) )
        {
            $plugins[] = $m;
        }
        $this->tpl->assign("plugins", $plugins);
        $this->loadView('plugins.tpl');
    }

    private function enable_modules($id)
    {
        $query = $this->db->execute('UPDATE <ezrpg>plugins SET <ezrpg>plugins.active = 1 WHERE <ezrpg>plugins.id = ' . $id . ' OR <ezrpg>plugins.pid = ' . $id);
        $this->db->fetch($query);
        $query2 = $this->db->execute('UPDATE <ezrpg>menu SET <ezrpg>menu.active = 1 WHERE <ezrpg>menu.module_id = ' . $id);
        $this->db->fetch($query2);
        killMenuCache();
        $this->setMessage("Module enabled!");
        killModuleCache();
        header('Location: index.php?mod=Plugins');
        exit;
    }

    private function disable_modules($id)
    {
        $query = $this->db->execute('UPDATE <ezrpg>plugins SET <ezrpg>plugins.active = 0 WHERE <ezrpg>plugins.id = ' . $id . ' OR <ezrpg>plugins.pid = ' . $id);
        $this->db->fetch($query);
        $query2 = $this->db->execute('UPDATE <ezrpg>menu SET <ezrpg>menu.active = 0 WHERE <ezrpg>menu.module_id = ' . $id);
        $this->db->fetch($query2);
        killMenuCache();
        $this->setMessage("Module disabled!");
        killModuleCache();
        header('Location: index.php?mod=Plugins');
        exit;
    }

    private function upload_modules()
    {
		killSettingsCache();
        if ( isset($_FILES['file']) )
        {
            if ( $_FILES["file"]["error"] > 0 )
            {
                $this->tpl->assign("MSG", "Error: " . $_FILES["file"]["error"]);
                $this->loadView('upload_plugins.tpl');
            }
            else
            {
				$type = $_FILES["file"]["type"];
				$okay = false;
				$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
				foreach($accepted_types as $mime_type) {
					if($mime_type == $type) {
						$okay = true;
						break;
					} 
				}
                if ( $okay )
                {
                    $zip = new PclZip($_FILES["file"]["tmp_name"]);
                    $ziptempdir = substr(uniqid('', true), -5);
                    $dir = "Plugins/temp/" . $ziptempdir;
                    $results = "";
                    if ( $zip->extract(PCLZIP_OPT_PATH, $dir) == 0 )
                    {
                        $results .= "Error : " . $zip->errorInfo(true);
                    }
                    $results .= "Plugin extracted to " . $dir . "<br />";
                    if ( file_exists($dir . "/plugin.xml") )
                    {
                        $results .= "this is a proper module/plugin. <br />";
                        $plug = simplexml_load_file($dir . "/" . "plugin.xml");
                        $p_d['title'] = (string) $plug->Plugin->Name;
                        $p_d['type'] = (string) $plug->Plugin->Type;
                        $p_d['filename'] = (string) $plug->Plugin->File;
                        $p_m['version'] = (string) $plug->Plugin->Version;
                        $p_m['author'] = (string) $plug->Plugin->Author;
                        $p_m['description'] = (string) $plug->Plugin->Description;
                        $p_m['url'] = (string) $plug->Plugin->AccessURL;
                        $p_m['plug_id'] = $this->db->insert('<ezrpg>plugins', $p_d);
                        $p_m['meta_id'] = $this->db->insert('<ezrpg>plugins_meta', $p_m);
                        if ( !empty($plug->Plugin->Hook) )
                        {
                            if ( !empty($plug->Plugin->Hook->HookFile) )
                            {
                                foreach ( $plug->Plugin->Hook->HookFile as $hooks )
                                {
                                    $hook['pid'] = $p_m['plug_id'];
                                    $hook['filename'] = (string) $hooks->HookFileName;
                                    $hook['title'] = (string) $hooks->HookTitle;
                                    $hook['type'] = 'hook';
                                    $this->db->insert('<ezrpg>plugins', $hook);
                                }
                            }
                        }
                        if ( !empty($plug->Plugin->Lib) )
                        {
                            if ( !empty($plug->Plugin->Lib->LibFile) )
                            {
                                foreach ( $plug->Plugin->Lib->LibFile as $libs )
                                {
                                    $lib['pid'] = $p_m['plug_id'];
                                    $lib['filename'] = (string) $libs->LibFileName;
                                    $lib['title'] = (string) $libs->LibTitle;
                                    $lib['type'] = 'library';
                                    $this->db->insert('<ezrpg>plugins', $lib);
                                }
                            }
                        }
                        if ( !empty($plug->Plugin->Theme) )
                        {
                            if ( !empty($plug->Plugin->Theme->ThemeFolder) )
                            {
                                foreach ( $plug->Plugin->Theme->ThemeFolder as $theme )
                                {
                                    $theme_m['pid'] = $p_m['plug_id'];
                                    $theme_m['filename'] = (string) $theme->ThemeFolder;
                                    $theme_m['title'] = (string) $theme->ThemeTitle;
                                    $theme_m['type'] = 'templates';
                                    $this->db->insert('<ezrpg>plugins', $theme_m);
                                }
                            }
                        }
                        if ( !empty($plug->Plugin->Menus) )
                        {
                            if ( !empty($plug->Plugin->Menus->Menu) )
                            {
								foreach ( $plug->Plugin->Menus->Menu as $menu )
								{
									$menusys = $this->menu;
									$newMenu = $menu;
									$menu_p['module_id'] = $p_m['plug_id'];
									$menu_p['parent_id'] = ($menusys->isMenu((string) $newMenu->MenuParent) ? $menusys->get_menu_id_by_name((string) $newMenu->MenuParent) : '0');
									$menu_p['title'] = (string) $newMenu->Title;
									$menu_p['uri'] = (string) $newMenu->URL;
									$menu_id = $this->db->insert('<ezrpg>menu', $menu_p);
									if ( !empty($newMenu->MenuChildren) )
									{
										if ( !empty($newMenu->MenuChildren->Child) )
										{
											foreach ( $newMenu->MenuChildren->Child as $childMenu )
											{
												$menu_c['module_id'] = $p_m['plug_id'];
												$menu_c['parent_id'] = $menu_id;
												$menu_c['title'] = (string) $childMenu->Title;
												$menu_c['uri'] = (string) $childMenu->URL;
												$this->db->insert('<ezrpg>menu', $menu_c);
											}
										}
									}
								}
                                killMenuCache();
                            }
                        }
						if ( !empty($plug->Plugin->Settings) )
                        {
                            if ( !empty($plug->Plugin->Settings->Setting) )
                            {
								foreach ( $plug->Plugin->Settings->Setting as $setting )
								{
									killSettingsCache();
									$newSetting = $setting;
									$setting_p['name'] = (string) $newSetting->Name;
									$setting_p['title'] = (string) $newSetting->Title;
									$setting_p['description'] = (string) $newSetting->Description;
									$setting_p['value'] = (string) $newSetting->Value;
									if ( !empty($newSetting->Group))
									{
										$query = $this->db->execute("SELECT id from <ezrpg>settings where name='".(string) $newSetting->Group."'");
										$setting_p['gid'] = $this->db->fetch($query)->id;
									}elseif( !empty($newSetting->GroupID)){
										$setting_p['gid'] = (string) $newSetting->GroupID;
									}
									$setting_id = $this->db->insert('<ezrpg>settings', $setting_p);
									$setting_u['uninstall'] = addslashes("DELETE FROM <ezrpg>settings WHERE id ='".$setting_id.";");
									$setting_u['pm_id'] = $p_m['meta_id'];
									$this->db->insert('<ezrpg>plugins_meta', $setting_u);
								}
                            }
                        }
						if ( !empty($plug->Plugin->SQL) )
						{
							if ( !empty($plug->Plugin->SQL->Install) )
							{
								if ( !empty($plug->Plugin->SQL->Install->SQLCALL))
								{
									foreach ( $plug->Plugin->SQL->Install->SQLCALL as $sql )
									{
										$this->db->execute((string) $sql);
									}
								}
							}
							if ( !empty($plug->Plugin->SQL->Uninstall) )
							{
								if ( !empty($plug->Plugin->SQL->Uninstall->SQLCALL) )
								{
									foreach ( $plug->Plugin->SQL->Uninstall->SQLCALL as $sql )
									{
										$this->db->execute("INSERT INTO <ezrpg>plugins_meta (plug_id, pm_id, uninstall) VALUES ('". $p_m['plug_id'] ."', '". $p_m['meta_id']."', '".addslashes((string) $sql)."')");
									}
								}
							}
						}
                        $results .= "installed db data <br />";
                        if ( file_exists($dir . '/modules') )
                            $this->re_copy($dir . '/modules/', MOD_DIR);
                        if ( file_exists($dir . '/lib') )
                            $this->re_copy($dir . '/lib/', LIB_DIR);
                        if ( file_exists($dir . '/hooks') )
                            $this->re_copy($dir . '/hooks/', HOOKS_DIR);
                        if ( file_exists($dir . '/admin') )
                            $this->re_copy($dir . '/admin/', ADMIN_DIR);
                        if ( file_exists($dir . '/templates') )
                            $this->re_copy($dir . '/templates/', THEME_DIR);

                        $this->rrmdir($dir);
                        $results .= "You have successfully uploaded a plugin via the manager! <br />";
                        killModuleCache();
						$this->db->execute('UPDATE <ezrpg>plugins SET installed=1, active=1 WHERE id=' . $p_m['plug_id'] . ' OR pid=' . $p_m['plug_id']);
						$this->db->execute('UPDATE <ezrpg>menu SET active=1 WHERE module_id=' . $p_m['plug_id']);

                        if ( !empty($plug->Plugin->AccessURL) )
                        {
                            $mod_url = $this->settings->setting['general']['site_url']['value'];
							$mod_url .= (string) $plug->Plugin->AccessURL;
                        }
                        $results .= "<a href='index.php?mod=Plugins'><input name='back' type='submit' class='button' value='Back to manager' /></a>";
                    }
                    else
                    {
                        $results .= $dir . "/" . pathinfo($_FILES['file']['name'], PATHINFO_FILENAME) . ".xml was not found <br />";
                        $results .= "This is not a valid Plugin <br />";
                        $this->rrmdir($dir);
                        $results .= "All temporary files have been deleted. <br />";
                        $results .= "<a href='index.php?mod=Plugins'><input name='login' type='submit' class='button' value='Back To Manager' /></a>";
                    }
                }
                else
                {
					$results = "Filetype detected: " . $_FILES['file']['type'] . '<br />';
                    $results .= "Uploaded Unsupported filetype. Only upload .zips at this time.<br />";
					$results .= "If you feel that this message is in error, please ask for support from ezRPGProject.net!<br />";
                    $results .= "<a href='index.php?mod=Plugins'><input name='login' type='submit' class='button' value='Back To Manager' /></a>";
                }
                $this->tpl->assign("RESULTS", $results);
                $this->loadView('plugin_results.tpl');
            }
        }
        else
        {
            $this->loadView('upload_plugins.tpl');
        }
    }

    private function remove_modules($id = 0)
    {
        if ( $id == 0 )
        {
            $this->list_modules();
            exit;
        }
        $query1 = $this->db->execute('SELECT * FROM <ezrpg>plugins_meta WHERE plug_id=' . $id);
        $query_mod = $this->db->execute('SELECT * FROM <ezrpg>plugins WHERE pid=' . $id . ' OR id=' . $id);
        $result = $this->db->fetchAll($query_mod);
		if ( $this->db->numRows($query1) != 0 )
        {
			$result2 = $this->db->fetchAll($query1);
			foreach( $result2 as $item=>$key)
			{
				if(!empty($key->uninstall))
					$this->db->execute($key->uninstall);
			}
			$complete = $this->finish_removal($id, $result);
        }
        else
        {
            $complete = $this->finish_removal($id, $result);
        }
		if ($complete == true)
		{
			$this->setMessage('Uninstall Complete', 'GOOD');
			header('Location: index.php?mod=Plugins');
		}else{
			$this->setMessage('Uninstall Failed', 'FAIL');
			header('Location: index.php?mod=Plugins');
		}
    }
	
	private function finish_removal($id, $result)
	{
		try
		{
			foreach ( $result as $file )
			{
				switch ( $file->type )
				{
					case 'module':
						$this->rrmdir(MOD_DIR . $file->title);
						break;
					case 'templates':
						$this->rrmdir(THEME_DIR . $file->filename . '/' . $file->title);
						break;
					case 'hook':
						if(file_exists(HOOKS_DIR .'/'. $file->filename))
							unlink(HOOKS_DIR . '/'. $file->filename);
						break;
				}
			}
			$this->db->execute('DELETE FROM <ezrpg>plugins WHERE pid=' . $id . ' OR id=' . $id);
			$this->db->execute('DELETE FROM <ezrpg>plugins_meta WHERE plug_id=' . $id);
			$this->db->execute('DELETE FROM <ezrpg>menu WHERE module_id=' . $id);
			killMenuCache();
			return true;
		}catch (Exception $e)
		{
			$this->setMessage($e, 'FAIL');
			return false;
		}
	}

    private function view_modules($id = 0)
    {
        if ( $id == 0 )
        {
            $this->list_modules();
            exit;
        }
    }

    private function rrmdir($dir)
    {
        if ( is_dir($dir) )
        {
            $objects = scandir($dir);
            foreach ( $objects as $object )
            {
                if ( $object != "." && $object != ".." )
                {
                    if ( filetype($dir . "/" . $object) == "dir" )
                        $this->rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
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
        while ( false !== ( $file = readdir($dir) ) )
        {
            if ( ( $file != '.' ) && ( $file != '..' ) )
            {
                if ( is_dir($src . '/' . $file) )
                {
                    $this->re_copy($src . '/' . $file, $dst . '/' . $file);
                }
                else
                {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

}
