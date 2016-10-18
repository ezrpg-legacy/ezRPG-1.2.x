<?php

namespace ezRPG\Modules\Update\Admin;

use \ezRPG\lib\Base_Module;

defined('IN_EZRPG') or exit;

/*
  Class: Admin_Update
  Admin page checking for and installing updates
 */

class Admin_Update extends Base_Module
{
    /*
      Function: start
      Displays the list of update/Update.
     */

    public function start()
    {
        if (isset($_GET['act'])) {
            switch ($_GET['act']) {
                case 'upload':
                    $this->upload_update(); 
                    break;
                case 'list':
                    $this->list_update(); 
                    break;
            }
        } else {
            $this->list_update();
        }
    }

    private function list_update()
    {
        $version = $this->container['app']->version;
        $this->tpl->assign("version", $version);
        $this->loadView('update.tpl');
    }

    private function upload_update()
    {
        if (isset($_FILES['file'])) {
            if ($_FILES["file"]["error"] > 0) {
                $this->tpl->assign("MSG", "Error: " . $_FILES["file"]["error"]);
                $this->loadView('upload_update.tpl');
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
                    $dir = MOD_DIR . "/Update/Admin/temp/" . $ziptempdir;
                    $results = "";
                    if ($zip->extract(PCLZIP_OPT_PATH, $dir) == 0) {
                        $results .= "Error : " . $zip->errorInfo(true);
                    }

					//run each sql the folder starting from the version after the current
					$sqlfiles = preg_grep('/^([^.])/', scandir($dir . "/sql/"));
					$versions = [];
					foreach($sqlfiles as $file){
						$version = explode('-', $file);
						$version[1] = str_replace(".sql", "", $version[1]);
						if(version_compare($version[1],$this->container['app']->version, ">")){
							if(version_compare($version[0], $this->container['app']->version, "<="))
							{
								array_push($versions, array('s'=>$version[0], 'e'=>$version[1], 'f'=>$dir . "/sql/". $file));
							}
						}
									
					}
					ksort($versions);
					foreach ($versions as $file) {								
						$sqlfile = file_get_contents($file['f']);
						$sql = explode(";",$sqlfile);
						foreach($sql as $k)
						{
							$this->container['db']->execute($k);
						}
					}
                    
                    $this->re_copy($dir, CUR_DIR);
                    $this->rrmdir($dir);
                    $results .= "You have successfully uploaded and installed an update! <br />";
                    $results .= "<a href='index.php?mod=Update'><input name='back' type='submit' class='button' value='Back to manager' /></a>";
                } else {
                    $results = "Filetype detected: " . $_FILES['file']['type'] . '<br />';
                    $results .= "Uploaded Unsupported filetype. Only upload .zips at this time.<br />";
                    $results .= "If you feel that this message is in error, please ask for support from ezRPGProject.net!<br />";
                    $results .= "<a href='index.php?mod=Update'><input name='login' type='submit' class='button' value='Back To Manager' /></a>";
                }
                $this->tpl->assign("RESULTS", $results);
                $this->loadView('update_results.tpl');
            }
        } else {
            $this->loadView('upload_update.tpl');
        }
    }

    private function remove_update($id = 0)
    {
        if ($id == 0) {
            $this->list_update();
            exit;
        }
        $query = $this->db->execute('SELECT uninstall FROM <ezrpg>Update_meta WHERE plug_id=' . $id);
        $result2 = $this->db->fetch($query);
        $query_mod = $this->db->execute('SELECT * FROM <ezrpg>Update WHERE pid=' . $id . ' OR id=' . $id);
        $result = $this->db->fetchAll($query_mod);
        if ($result2) {
            $mod = ModuleFactory::Factory($this->db, $this->tpl, $this->player, $result[0]->title, $this->menu,
                $this->settings);
            $uninstall_func = (string)$result2->uninstall;
            if ($mod->$uninstall_func() == true) {

                $complete = $this->finish_removal($id, $result);
            } else {
                $this->setMessage('Uninstall Failed', 'FAIL');
                header('Location: index.php?mod=Update');
                exit;
            }
        } else {
            $complete = $this->finish_removal($id, $result);
        }
        if ($complete == true) {
            $this->setMessage('Uninstall Complete', 'GOOD');
            header('Location: index.php?mod=Update');
            exit;
        } else {
            $this->setMessage('Uninstall Failed', 'FAIL');
            header('Location: index.php?mod=Update');
            exit;
        }
    }

    private function view_update($id = 0)
    {
        if ($id == 0) {
            $this->list_update();
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
