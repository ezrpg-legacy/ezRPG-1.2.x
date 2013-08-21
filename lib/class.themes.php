<?php

defined('IN_EZRPG') or exit;

/*
  Class: Themes
  A class to handle the theme system
 */

class Themes
{
    /*
      Variable: $db
      Contains the database object.
     */

    protected $db;
    /*
      Variable: $tpl
      Contains the smarty object.
     */
    protected $tpl;

    /*
      Function: __construct
      The constructor takes in database and template to pass onto any functions called.

      Parameters:
      $db - An instance of the database class.
      $this->tpl - A smarty object.
     */

    public function __construct(&$db, &$tpl)
    {
        $this->db = & $db;
        $this->tpl = & $tpl;
        $this->template = $this->loadCache();
        $this->loadTemplates();
    }

    /*
      Function: loadCache
      Function that checks for a Cache file and loads/builds it.
     */

    public function loadCache($id = null)
    {
        $query = 'SELECT * FROM <ezrpg>themes';
        $cache_file = md5($query);

        if ( file_exists(CACHE_DIR . $cache_file) || !is_null($id) )
        {
            if ( filemtime(CACHE_DIR . $cache_file) > time() - 60 * 60 * 24 )
            {
                $array = unserialize(file_get_contents(CACHE_DIR . $cache_file));
                if ( DEBUG_MODE == 1 )
                {
                    echo 'Loaded Themes Cache! <br />';
                }
            }
            else
            {
                if(file_exists(CACHE_DIR . $cache_file))
				{
					unlink(CACHE_DIR . $cache_file);
					$this->loadCache();
					return;
				}
            }
        }
        else
        {
            $query1 = $this->db->execute($query);
            $array = $this->db->fetchAll($query1);
            file_put_contents(CACHE_DIR . $cache_file, serialize($array));
            if ( DEBUG_MODE == 1 )
            {
                echo 'Created Themes Cache! <br />';
            }
        }
        return $array;
    }

    public function loadTemplates()
    {
        $query = 'SELECT * FROM <ezrpg>themes';
        $cache_file = md5($query);
        $themetpldir = scandir(THEME_DIR . 'themes/', 0);
        $moduletpldir = scandir(THEME_DIR . 'modules/', 0);
        $entries = array_merge($themetpldir, $moduletpldir);
        $templates = array( );
		if (isset($this->template))
		{
			foreach ( $this->template as $item => $val )
			{
				$templates[$val->name] = $val->name;
			}
		}
        foreach ( $entries as $entry )
        {
            if ( $entry != '.' && $entry != '..' && $entry != 'index.php' )
            {
				$entry_dir = THEME_DIR . 'themes/' . $entry;
				$entry_dir2 = THEME_DIR . 'modules/' . $entry;
                if ( !array_key_exists($entry, $templates) && !array_key_exists($entry, $this->tpl->getTemplateDir()) )
                {
                    if ( is_dir($entry_dir) )
                    {
                        $this->tpl->addTemplateDir(array(
                            $entry => $entry_dir,
                        ));
                        $this->db->execute("INSERT INTO <ezrpg>themes (name, dir, enabled) VALUES ('" . $entry . "', '" . $entry_dir . "', 0)");
                        unlink(CACHE_DIR . $cache_file);
                        $this->loadCache(1);
                    }
                    if ( is_dir($entry_dir2) )
                    {
                        $this->tpl->addTemplateDir(array(
                            $entry => $entry_dir2,
                        ));
                        $this->db->execute("INSERT INTO <ezrpg>themes (name, dir, enabled, type) VALUES ('" . $entry . "', '" . $entry_dir2 . "', 0, 1)");
                        unlink(CACHE_DIR . $cache_file);
                        $this->loadCache(1);
                    }
                }
                elseif(!array_key_exists($entry, $templates) && array_key_exists($entry, $this->tpl->getTemplateDir()))
                {
                    if ( is_dir($entry_dir) || is_dir($entry_dir2) )
                    {
                        $this->db->execute("INSERT INTO <ezrpg>themes (name, dir, enabled, type) VALUES ('" . $entry . "', '" . $entry_dir . "', 0, ". (is_dir($entry_dir) ? 0 : 1) . ")");
                        unlink(CACHE_DIR . $cache_file);
                        $this->loadCache(1);
                    }else{
						$this->db->execute("DELETE FROM <ezrpg>themes WHERE name = '" . $entry . "'");
                        unlink(CACHE_DIR . $cache_file);
						echo 'deleted theme ' . $entry;
                        $this->loadCache(1);
					}
                }elseif(array_key_exists($entry, $templates) && !array_key_exists($entry, $this->tpl->getTemplateDir()))
				{ 
					if ( is_dir($entry_dir) )
                    { 
						$this->tpl->addTemplateDir(array(
							$entry => $entry_dir,
						));
					}
					if ( is_dir($entry_dir2) )
                    { 
						$this->tpl->addTemplateDir(array(
							$entry => $entry_dir2,
						));
					}
				}
            }//print_r($templates);
        }
    }

}

?>
