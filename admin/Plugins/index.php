<?php
defined('IN_EZRPG') or exit;
require_once (LIB_DIR . '/pclzip.lib.php');
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
    public function start() {
  
        if(isset($_GET['act'])){
			switch ($_GET['act']) {
				case 'view' :
					$this->view_modules(); //TODO:View Modules
					break;
				case 'upload' :
					$this->upload_modules(); //Completed:Upload Modules
					break;
				case 'remove' :
					$this->remove_modules(); //TODO:Remove Modules
					break;
				case 'install' :
					$this->install_manager(); //Completed:Install Manager
					break;
				case 'list' :
					$this->list_modules(); //Completed:Lists Modules
					break;
				case 'activate' :
					break; //TODO:activate plugins after they've been installed.
				case 'deactivate' :
					break; //TODO:deactivate plugins but not remove them.
			}
		} else {
			$this->list_modules();
		}
    }
    
    private function list_modules() {
		$query = $this->db->execute('select * from ' . DB_PREFIX . 'plugins');
		$plugins = Array();
		while ($m = $this->db->fetch($query)) {
			$plugins[] = $m;
		}
		$this->install_ask();
		$this->tpl->assign("plugins", $plugins);
		$this->loadView('admin/plugins.tpl');
	}
    private function upload_modules() {
		if(isset($_FILES['file'])){
			if ($_FILES["file"]["error"] > 0)
			{
				$this->tpl->assign("MSG", "Error: " . $_FILES["file"]["error"]);
				$this->loadView('admin/upload_plugins.tpl');
			}
			else
			{
				if($_FILES["file"]["type"] == "application/x-zip-compressed")
				{
					$zip = new PclZip($_FILES["file"]["tmp_name"]);
					$ziptempdir = substr(uniqid ('', true), -5);
					$dir = "Plugins/temp/" . $ziptempdir;
					$results = "";
					if ($zip->extract(PCLZIP_OPT_PATH, $dir ) == 0) {
						$results .= "Error : ".$zip->errorInfo(true);
					}
					$results .= "Plugin extracted to ". $dir . "<br />";
					if(file_exists($dir ."/".  pathinfo($_FILES['file']['name'], PATHINFO_FILENAME) .".xml"))
					{
						$results .= "this is a proper module/plugin. <br />";
						$plug = simplexml_load_file($dir ."/".  pathinfo($_FILES['file']['name'], PATHINFO_FILENAME) .".xml");
						$p_m['n'] = $plug->Module->Name;
						$p_m['v'] = $plug->Module->Version;
						$p_m['a'] = $plug->Module->Author;
						$p_m['s'] = $plug->Module->AuthorSite;
						$p_m['d'] = $plug->Module->Description;
						$p_m['i'] = $plug->Module->InstallCode;
						$this->db->execute("insert into ". DB_PREFIX ."plugins (title, description, author, authorsite, active, version, xml_location) values ('".$p_m['n']."', '".$p_m['d']."', '".$p_m['a']."', '".$p_m['s']."', 0, '".$p_m['v']."', 'modules/". pathinfo($_FILES['file']['name'], PATHINFO_FILENAME) .".xml')");
						$results .= "installed db data <br />";
						$this->re_copy($dir, CUR_DIR);
						rename(CUR_DIR . "/" .pathinfo($_FILES['file']['name'], PATHINFO_FILENAME) .".xml", MOD_DIR ."/".pathinfo($_FILES['file']['name'], PATHINFO_FILENAME). "/" .pathinfo($_FILES['file']['name'], PATHINFO_FILENAME) .".xml");
						$this->rrmdir($dir);
						$results .= "You have successfully uploaded a plugin via the manager! <br />";
						$results .= "<a href='index.php?mod=Plugins'><input name='login' type='submit' class='button' value='Back To Manager' /></a> OR <a href='../". $p_m['i'] ."'><input name='login' type='submit' class='button' value='Install Plugin' /></a>";
					}else{
					    $results .= $dir ."/". pathinfo($_FILES['file']['name'], PATHINFO_FILENAME) .".xml was not found <br />";
						$results .= "this is not a proper module/plugin. <br />";
						$this->rrmdir($dir);
						$results .= "all temporary files have been deleted. <br />";
						$results .= "<a href='index.php?mod=Plugins'><input name='login' type='submit' class='button' value='Back To Manager' /></a>";
					}
				} else {
					$results = "Uploaded Unsupported filetype. Only upload .zips at this time.<br>";
					$results .= "<a href='index.php?mod=Plugins'><input name='login' type='submit' class='button' value='Back To Manager' /></a>"; 
				}
				$this->tpl->assign("RESULTS", $results);
				$this->loadView('admin/plugin_results.tpl');
			}
		} else {
		$this->loadView('admin/upload_plugins.tpl');
		}
	}
	private function install_ask() {
	$result = $this->db->fetchRow('SELECT COUNT(id) AS count FROM <ezrpg>plugins');
	if($result){
	$this->tpl->assign('INSTALLED', TRUE);
    }else{
	$this->tpl->assign('INSTALLED', FALSE);
	}
  }
	private function remove_modules(){
		$data = "This feature still isn't compelte.<br />";
		$data .= "The road map for this feature is as follows:<br />";
		$data .= "<b>FIRST</b><br />Delete files only as they appear from the .xml file.<br />";
		$data .= "By recursively checking through the xml's FileName scope, we can delete files installed based on loc/name<br />";
		$data .= "<b>Secondly</b><br />Only after the initial feature is created, we'll start a md5 check on the files.<br />";
		$data .= "Any files matching the md5 are auto deleted, md5 mismatches are declared with a warning, and user interaction is needed<br />";
		$data .= "<a href='index.php?mod=Plugins'><input name='login' type='submit' class='button' value='Back To Manager' /></a>";
  }
	private function rrmdir($dir) { 
		if (is_dir($dir)) { 
			$objects = scandir($dir); 
			foreach ($objects as $object) { 
				if ($object != "." && $object != "..") { 
					if (filetype($dir."/".$object) == "dir") $this->rrmdir($dir."/".$object); else unlink($dir."/".$object); 
				} 
			} 
			reset($objects); 
			rmdir($dir); 
		} 
	}
	private function re_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                $this->re_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 
}
