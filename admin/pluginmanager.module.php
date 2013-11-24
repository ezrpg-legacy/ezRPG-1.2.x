<?php

defined('IN_EZRPG') or exit;
require_once( LIB_DIR . '/pclzip.lib.php' );
/*
  Class: Admin_Plugins
  Admin page for managing plugins and modules
 */

class PluginManager extends Base_Module
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
		$query = $this->app['hooks']->run_hooks('get_plugins');
        //$query = $this->db->execute('select * from <ezrpg>plugins JOIN <ezrpg>plugins_meta ON <ezrpg>plugins.id = <ezrpg>plugins_meta.plug_id');
        $plugins = Array( );
		/*
        while ( $m = $this->db->fetch($query) )
        {
            $plugins[] = $m;
        }
		*/
		
		foreach($query as $key)
		{
			$plugins[]=$key;
		}
		//die(print_r($plugins));
		$this->tpl->assign("plugins", $plugins);
        $this->loadView('plugins.tpl');
    }

    private function enable_modules($id)
    {
		$module_query = $this->db->execute('SELECT class FROM <ezrpg>plugins WHERE id='.$id);
		$result = $this->db->fetchArray($module_query);
		if(file_exists(MOD_DIR.'/'.$result['class'].'.module.php'))
			include MOD_DIR.'/'.$result['class'].'.module.php';
		if(class_exists($result['class']))
			$plugin = $result['class'];
		if(isset($plugin))
		{
			$plugin = new $plugin($this->app);
			if(method_exists($plugin, '__activate'))
				$plugin->__activate();
		}
        $query = $this->db->execute('UPDATE <ezrpg>plugins SET <ezrpg>plugins.active = 1 WHERE <ezrpg>plugins.id = ' . $id . ' OR <ezrpg>plugins.pid = ' . $id);
        $this->db->fetch($query);
        $query2 = $this->db->execute('UPDATE <ezrpg>menu SET <ezrpg>menu.active = 1 WHERE <ezrpg>menu.module_id = ' . $id);
        $this->db->fetch($query2);
        killMenuCache();
        $this->setMessage("Module enabled!");
        killModuleCache();
        header('Location: index.php?mod=PluginManager');
        exit;
    }

    private function disable_modules($id)
    {
		$module_query = $this->db->execute('SELECT class FROM <ezrpg>plugins WHERE id='.$id);
		$result = $this->db->fetchArray($module_query);
		if(file_exists(MOD_DIR.'/'.$result['class'].'.module.php'))
			include MOD_DIR.'/'.$result['class'].'.module.php';
		if(class_exists($result['class']))
			$plugin = $result['class'];
		if(isset($plugin))
		{
			$plugin = new $plugin($this->app);
			if(method_exists($plugin, '__deactivate'))
				$plugin->__deactivate();
		}		
		$query = $this->db->execute('UPDATE <ezrpg>plugins SET <ezrpg>plugins.active = 0 WHERE <ezrpg>plugins.id = ' . $id . ' OR <ezrpg>plugins.pid = ' . $id);
        $this->db->fetch($query);
        $query2 = $this->db->execute('UPDATE <ezrpg>menu SET <ezrpg>menu.active = 0 WHERE <ezrpg>menu.module_id = ' . $id);
        $this->db->fetch($query2);
        killMenuCache();
        $this->setMessage("Module disabled!");
        killModuleCache();
        header('Location: index.php?mod=PluginManager');
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
                    $dir = "PluginTemp/" . $ziptempdir;
                    $results = "";
                    if ( $zip->extract(PCLZIP_OPT_PATH, $dir) == 0 )
                    {
                        $results .= "Error : " . $zip->errorInfo(true);
                    }
                    $results .= "Plugin extracted to " . $dir . "<br />";
                    $plugin_files = scandir($dir);
					foreach ( $plugin_files as $plugin_file )
					{
						$path_parts = pathinfo($dir . '/' . $plugin_file);
						if ( $path_parts['extension'] == 'php' && explode('.',$path_parts['basename'])[1] == 'module' )
						{
							include ($dir . '/' . $plugin_file);
							$plugin = ucfirst(explode('.',$plugin_file)[0]);
						}
					}
					if(class_exists($class = 'Module_'.$plugin) OR class_exists($plugin)){
						if(class_exists($class))
							$plugin = $class;
						$plugin = new $plugin($this->app);
						$error=0;
						$errors[]= '';
						$module_data = get_module_data($dir.'/'.$plugin_file);
						$result=true;
						if(method_exists($plugin, '__activate'))
							$result = $plugin->__activate();
						if($result==false)
						{
							$insert_plug = $this->db->INSERT('<ezrpg>plugins', array('title'=>$plugin, 'type'=>'module', 'active'=>0));
							$meta = $this->db->INSERT('<ezrpg>plugins_meta', array('plug_id'=>$insert_plug));
							$results .= "There was an issue activating your plugin!<br />";
						}else{
							$insert_plug = $this->db->INSERT('<ezrpg>plugins', array('title'=>$module_data['Name'], 'type'=>'module', 'active'=>1));
							$insert_meta['plug_id'] = $insert_plug;
							$insert_meta['version'] = (!empty($module_data['Version'])?$module_data['Version']:'0');
							$insert_meta['description'] = (!empty($module_data['Description'])?$module_data['Description']:'');
							$this->db->INSERT('<ezrpg>plugins_meta', $insert_meta);
							killModuleCache();
							$results .= "Plugin has been activated!";
						}
						$results .= "<a href='index.php?mod=PluginManager'><input name='back' type='submit' class='button' value='Back to manager' /></a>";
                    }
                    else
                    {
                        $results .= ucfirst($plugin) ." class was not found <br />";
                        $results .= "This is not a valid Plugin <br />";
                        $this->rrmdir($dir);
                        $results .= "All temporary files have been deleted. <br />";
                        $results .= "<a href='index.php?mod=PluginManager'><input name='login' type='submit' class='button' value='Back To Manager' /></a>";
                    }
                }
                else
                {
					$results = "Filetype detected: " . $_FILES['file']['type'] . '<br />';
                    $results .= "Uploaded Unsupported filetype. Only upload .zips at this time.<br />";
					$results .= "If you feel that this message is in error, please ask for support from ezRPGProject.net!<br />";
                    $results .= "<a href='index.php?mod=PluginManager'><input name='login' type='submit' class='button' value='Back To Manager' /></a>";
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
            break;
        }
		$module_query = $this->db->execute('SELECT class FROM <ezrpg>plugins WHERE id='.$id);
		$result = $this->db->fetchArray($module_query);
		if(file_exists(MOD_DIR.'/'.$result['class'].'.module.php'))
			include MOD_DIR.'/'.$result['class'].'.module.php';
		if(class_exists($result['class']))
			$plugin = $result['class'];
		if(isset($plugin))
		{
			$plugin = new $plugin($this->app);
			if(method_exists($plugin, '__uninstall'))
				$plugin->__uninstall();
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
			header('Location: index.php?mod=PluginManager');
		}else{
			$this->setMessage('Uninstall Failed', 'FAIL');
			header('Location: index.php?mod=PluginManager');
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
						$this->rrmdir(MOD_DIR . $file->class . '.module.php');
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
			killModuleCache();
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
            break;
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
        }else{
			unlink($dir);
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

	function file_get_php_classes($filepath) {
  $php_code = file_get_contents($filepath);
  $classes = $this->get_php_classes($php_code);
  return $classes;
}

function get_php_classes($php_code) {
  $classes = array();
  $tokens = token_get_all($php_code);
  $count = count($tokens);
  for ($i = 2; $i < $count; $i++) {
    if (   $tokens[$i - 2][0] == T_CLASS
        && $tokens[$i - 1][0] == T_WHITESPACE
        && $tokens[$i][0] == T_STRING) {

        $class_name = $tokens[$i][1];
        $classes[] = $class_name;
    }
  }
  return $classes;
}

function processPHPDoc(ReflectionMethod $reflect)
{
    $phpDoc = array('params' => array(), 'return' => null, 'title'=>array(), 'author'=>array());
    $docComment = $reflect->getDocComment();
    if (trim($docComment) == '') {
        return null;
    }
    $docComment = preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ ]{0,1}(.*)?#', '$1', $docComment);
    $docComment = ltrim($docComment, "\r\n");
    $parsedDocComment = $docComment;
    $lineNumber = $firstBlandLineEncountered = 0;
    while (($newlinePos = strpos($parsedDocComment, "\n")) !== false) {
        $lineNumber++;
        $line = substr($parsedDocComment, 0, $newlinePos);
 
        $matches = array();
        if ((strpos($line, '@') === 0) && (preg_match('#^(@\w+.*?)(\n)(?:@|\r?\n|$)#s', $parsedDocComment, $matches))) {
            $tagDocblockLine = $matches[1];
            $matches2 = array();
 
            if (!preg_match('#^@(\w+)(\s|$)#', $tagDocblockLine, $matches2)) {
                break;
            }
            $matches3 = array();
            if (!preg_match('#^@(\w+)\s+([\w|\\\]+)(?:\s+(\$\S+))?(?:\s+(.*))?#s', $tagDocblockLine, $matches3)) {
                break;
            }
            if ($matches3[1] != 'param') {
                if (strtolower($matches3[1]) == 'return') {
                    $phpDoc['return'] = array('type' => $matches3[2]);
                }
            } else {
                $phpDoc['params'][] = array('name' => $matches3[3], 'type' => $matches3[2]);
            }
 
            $parsedDocComment = str_replace($matches[1] . $matches[2], '', $parsedDocComment);
        }
    }
    return $phpDoc;
}


}
