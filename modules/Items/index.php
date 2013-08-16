<?php
defined('IN_EZRPG') or exit;

/*
  Class: Module_Items
  Item Module for using, buying and selling Items
*/
class Module_Items extends Base_Module
{
    public function start()
    {
        //Require login
        requireLogin();
        
        if (isset($_GET['act']))
        {
            switch($_GET['act'])
            {
            case 'Inventory':
                $this->Inventory();
                break;
            case 'use':
                $this->useItem();
                break;
            case 'Shop':
                $this->shop();
                break;    
            case 'buy':
                $this->buy();
                break;
            case 'sell':
                $this->sell();
                break;
            case 'dobuy':
                $this->dobuy();
                break;
            case 'dosell':
                $this->dosell();
                break;        
            default:
                $this->Inventory();
                break;
            }
        }
        else
        {
            $this->Inventory();
        }
    }
    
    //Show player's items
    private function Inventory()
    {
        if (isset($_GET['page']))
        {
            $page = (intval($_GET['page']) > 0) ? intval($_GET['page']) : 0;
        }
        else
        {
            $page = 0;
        }
        
        $query = $this->db->execute('SELECT * FROM `<ezrpg>playeritems` 
        INNER JOIN `<ezrpg>items` ON `<ezrpg>playeritems`.`item_id`=`<ezrpg>items`.`item_id`
        INNER JOIN `<ezrpg>itemclass` ON `<ezrpg>items`.`class_id`=`<ezrpg>itemclass`.`class_id` 
        WHERE `player_id`=? ORDER BY id ASC LIMIT ?,50 ', array($this->player->id,$page * 50));
        $playeritems = $this->db->fetchAll($query);
        
        $query2 = $this->db->execute('SELECT * FROM `<ezrpg>playeritems` WHERE `player_id`=?', array($this->player->id));
                
        $curpage = $page;
        $rows = $this->db->numRows($query2);
        $maxpages = (ceil($rows / 50) - 1);       
        $prevpage = (($page - 1) >= 0) ? ($page - 1) : 0;
             
        $this->tpl->assign('curpage', $curpage);
        $this->tpl->assign('maxpages', $maxpages);
        $this->tpl->assign('nextpage', ++$page);
        $this->tpl->assign('prevpage', $prevpage);        
        $this->tpl->assign('playeritems', $playeritems);
        $this->loadView('inventory.tpl', 'Items');
    }
    
    private function useItem()
    {   
        $item = $this->db->fetchRow('SELECT * FROM `<ezrpg>playeritems` 
        INNER JOIN `<ezrpg>items` ON `<ezrpg>playeritems`.`item_id`=`<ezrpg>items`.`item_id`
        INNER JOIN `<ezrpg>itemclass` ON `<ezrpg>items`.`class_id`=`<ezrpg>itemclass`.`class_id` 
        WHERE `id`=?', array($_GET['id']));
        
        if ($item == false)
        {
            $this->setMessage('That item doesn\'t exist!', 'FAIL');	
            header('Location: index.php?mod=Items&act=Inventory');
            exit;
        }
        if ($item->player_id != $this->player->id)
        {
            $this->setMessage('That item doesn\'t belong to you!', 'FAIL');	
            header('Location: index.php?mod=Items&act=Inventory');
            exit;
        }
        if ($item->useable == 0)
        {
            if ($item->in_use == 0)
            {
                $items = $this->db->fetchRow('SELECT * FROM `<ezrpg>playeritems` 
                INNER JOIN `<ezrpg>items` ON `<ezrpg>playeritems`.`item_id`=`<ezrpg>items`.`item_id`
                INNER JOIN `<ezrpg>itemclass` ON `<ezrpg>items`.`class_id`=`<ezrpg>itemclass`.`class_id` 
                WHERE `useable`=0 AND `in_use`=1 AND `player_id`=?', array($this->player->id));
            
                if($items !== false)
                {
                    $this->db->execute('UPDATE `<ezrpg>players_meta` 
                    SET `strength`=strength-?, `vitality`=vitality-?, `agility`=agility-?, `dexterity`=dexterity-?, `max_hp`=max_hp-?, `hp`=hp-?, `max_energy`=max_energy-?, `energy`=energy-? 
                    WHERE `pid`=?', array($items->strength, $items->vitality, $items->agility, $items->dexterity, $items->max_hp, $items->hp, $items->max_energy, $items->energy, $this->player->id));
                    $this->db->execute('UPDATE `<ezrpg>playeritems` SET `in_use`=0 WHERE `id`=?', array($items->id));
                }
            
                $this->db->execute('UPDATE `<ezrpg>players_meta` 
                SET `strength`=strength+?, `vitality`=vitality+?, `agility`=agility+?, `dexterity`=dexterity+?, `max_hp`=max_hp+?, `hp`=hp+?, `max_energy`=max_energy+?, `energy`=energy+? 
                WHERE `pid`=?', array($item->strength, $item->vitality, $item->agility, $item->dexterity, $item->max_hp, $item->hp, $item->max_energy, $item->energy, $this->player->id));
                $this->db->execute('UPDATE `<ezrpg>playeritems` SET `in_use`=1 WHERE `id`=?', array($item->id));
            
                loadMetaCache(1);
                $this->setMessage('You equiped '. $item->name .'!');	
                header('Location: index.php?mod=Items&act=Inventory');
                exit;
            }
            else if ($item->in_use == 1)
            {
                $this->db->execute('UPDATE `<ezrpg>players_meta` 
                SET `strength`=strength-?, `vitality`=vitality-?, `agility`=agility-?, `dexterity`=dexterity-?, `max_hp`=max_hp-?, `hp`=hp-?, `max_energy`=max_energy-?, `energy`=energy-? 
                WHERE `pid`=?', array($item->strength, $item->vitality, $item->agility, $item->dexterity, $item->max_hp, $item->hp, $item->max_energy, $item->energy, $this->player->id));
                $this->db->execute('UPDATE `<ezrpg>playeritems` SET `in_use`=0 WHERE `id`=?', array($item->id));
            
                loadMetaCache(1);
                $this->setMessage('You unequiped '. $item->name .'!');	
                header('Location: index.php?mod=Items&act=Inventory');
                exit;
            }
        }
        if ($item->useable == 1)
        {
            if ($item->in_use < $item->times_useable)
            {
                if ($item->in_use = $item->times_useable - 1)
                {
                    if ($item->amount == 1)
                    {
                        $this->db->execute('DELETE FROM `<ezrpg>playeritems` WHERE `id`=?', array($item->id));
                    }
                    else
                    {
                        $this->db->execute('UPDATE `<ezrpg>playeritems` SET `in_use`=0, `amount`=amount-1 WHERE `id`=?', array($item->id));
                    }
                }
                else
                {
                    $this->db->execute('UPDATE `<ezrpg>playeritems` SET `in_use`=in_use+1 WHERE `id`=?', array($item->id));
                }
            }
                $this->db->execute('UPDATE `<ezrpg>players_meta` 
                SET `strength`=strength+?, `vitality`=vitality+?, `agility`=agility+?, `dexterity`=dexterity+?, `max_hp`=max_hp+?, `hp`=hp+?, `max_energy`=max_energy+?, `energy`=energy+? 
                WHERE `pid`=?', array($item->strength, $item->vitality, $item->agility, $item->dexterity, $item->max_hp, $item->hp, $item->max_energy, $item->energy, $this->player->id));
            
                loadMetaCache(1);
                $this->setMessage('You used '. $item->name .'!');	
                header('Location: index.php?mod=Items&act=Inventory');
                exit;            
        }   
    }
    
    private function shop()
    {
        $query = $this->db->execute('SELECT * FROM `<ezrpg>itemclass` WHERE `buyable`=1');
        $buy = $this->db->fetchAll($query);
        unset($query);
        
        $query = $this->db->execute('SELECT * FROM `<ezrpg>itemclass` WHERE `sellable`=1');
        $sell = $this->db->fetchAll($query);
        unset($query);
        
        $this->tpl->assign('sell', $sell);
        $this->tpl->assign('buy', $buy);
        $this->loadView('shop.tpl', 'Items');
    }
    
    private function buy()
    {
        $check = $this->db->fetchRow('SELECT * FROM `<ezrpg>itemclass` WHERE `class`=?', array($_GET['class']));
        if ($check->buyable == 1)
        {
            if (isset($_GET['page']))
            {
                $page = (intval($_GET['page']) > 0) ? intval($_GET['page']) : 0;
            }
            else
            {
                $page = 0;
            }
            
            $query = $this->db->execute('SELECT * FROM `<ezrpg>items` INNER JOIN `<ezrpg>itemclass` 
            ON `<ezrpg>items`.`class_id`=`<ezrpg>itemclass`.`class_id` 
            WHERE `class`=? ORDER BY `item_id` ASC LIMIT ?,50', array($_GET['class'], $page * 50));
            $items = $this->db->fetchAll($query);
            
            $query2 = $this->db->execute('SELECT * FROM `<ezrpg>items` INNER JOIN `<ezrpg>itemclass` 
            ON `<ezrpg>items`.`class_id`=`<ezrpg>itemclass`.`class_id` WHERE `class`=?', array($_GET['class']));
            
            $curpage = $page;
            $rows = $this->db->numRows($query2);
            $maxpages = (ceil($rows / 50) - 1);       
            $prevpage = (($page - 1) >= 0) ? ($page - 1) : 0;
            $class = $_GET['class'];
             
            $this->tpl->assign('class', $class);
            $this->tpl->assign('curpage', $curpage);
            $this->tpl->assign('maxpages', $maxpages);
            $this->tpl->assign('nextpage', ++$page);
            $this->tpl->assign('prevpage', $prevpage);
            $this->tpl->assign('items', $items);
            $this->loadView('buy.tpl', 'Items');
        }
        else
        {
            $this->setMessage($_GET['class'] .'doesn\'t exist!', 'FAIL');	
            header('Location: index.php?mod=Items&act=Shop');
            exit;
        }
    }
    
    private function dobuy()
    {
        $check = $this->db->fetchRow('SELECT * FROM `<ezrpg>items` WHERE item_id=?', array($_GET['id']));
        if ($check == false)
        {
            $this->setMessage('The desired item doesn\'t exist!', 'FAIL');	          
            header('Location: index.php?mod=Items&act=buy&class='. $_GET['class']);
            exit;    
        }
        else if ($check->buy_price <= $this->player->money)
        {
            $check2 = $this->db->fetchRow('SELECT * FROM `<ezrpg>playeritems` WHERE item_id=? and player_id=?', array($check->item_id, $this->player->id));
            if ($check2 == false)
            {
                $insert['player_id'] = $this->player->id;
                $insert['item_id'] = $_GET['id'];
                $insert['amount'] = 1;
                $insert['in_use'] = 0;
                
                $this->db->insert('<ezrpg>playeritems', $insert);
                $this->db->execute('UPDATE `<ezrpg>players_meta` SET `money`=money-? WHERE `pid`=?', array($check->buy_price, $this->player->id));                
            }
            else
            {
                $this->db->execute('UPDATE `<ezrpg>playeritems` SET `amount`=amount+1 WHERE `player_id`=? and `item_id`=?', array($this->player->id, $check->item_id));
                $this->db->execute('UPDATE `<ezrpg>players_meta` SET `money`=money-? WHERE `pid`=?', array($check->buy_price, $this->player->id));
            }
            loadMetaCache(1);
            $this->setMessage('You bought '. $check->name .'!');	
            header('Location: index.php?mod=Items&act=buy&class='. $_GET['class']);  
        }
        else
        {
            $this->setMessage('You don\'t have enough money!', 'FAIL');	
            header('Location: index.php?mod=Items&act=buy&class='. $_GET['class']);
        }
    }
    
    private function sell()
    {
        $check = $this->db->fetchRow('SELECT * FROM `<ezrpg>itemclass` WHERE `class`=?', array($_GET['class']));
        if ($check->sellable == 1)
        {
            if (isset($_GET['page']))
            {
                $page = (intval($_GET['page']) > 0) ? intval($_GET['page']) : 0;
            }
            else
            {
                $page = 0;
            }
            
            $query = $this->db->execute('SELECT * FROM `<ezrpg>playeritems` INNER JOIN `<ezrpg>items` 
            ON `<ezrpg>items`.`item_id`=`<ezrpg>playeritems`.`item_id` INNER JOIN `<ezrpg>itemclass`
            ON `<ezrpg>items`.`class_id`=`<ezrpg>itemclass`.`class_id` WHERE `class`=? and  `player_id`=?
            ORDER BY `id` ASC LIMIT ?,50', array($_GET['class'], $this->player->id, $page * 50));
            $items = $this->db->fetchAll($query);
            
            
            $query2 = $this->db->execute('SELECT * FROM `<ezrpg>playeritems` INNER JOIN `<ezrpg>items` 
            ON `<ezrpg>items`.`item_id`=`<ezrpg>playeritems`.`item_id` INNER JOIN `<ezrpg>itemclass`
            ON `<ezrpg>items`.`class_id`=`<ezrpg>itemclass`.`class_id` 
            WHERE `class`=? and  `player_id`=?', array($_GET['class'], $this->player->id));
            
            $curpage = $page;
            $rows = $this->db->numRows($query2);
            $maxpages = (ceil($rows / 50) - 1);       
            $prevpage = (($page - 1) >= 0) ? ($page - 1) : 0;
            $class = $_GET['class'];
             
            $this->tpl->assign('debug', $debug);
            $this->tpl->assign('class', $class);
            $this->tpl->assign('curpage', $curpage);
            $this->tpl->assign('maxpages', $maxpages);
            $this->tpl->assign('nextpage', ++$page);
            $this->tpl->assign('prevpage', $prevpage);
            $this->tpl->assign('items', $items);
            $this->loadView('sell.tpl', 'Items');
        }
        else
        {
            $this->setMessage($_GET['class'] .'doesn\'t exist!', 'FAIL');	
            header('Location: index.php?mod=Items&act=Inventory');
            exit;
        }
    }
    
    private function dosell()
    {
        $check = $this->db->fetchRow('SELECT * FROM `<ezrpg>playeritems` INNER JOIN `<ezrpg>items` ON `<ezrpg>playeritems`.`item_id`=`<ezrpg>items`.`item_id` 
        INNER JOIN `<ezrpg>itemclass` ON `<ezrpg>items`.`class_id`=`<ezrpg>itemclass`.`class_id` WHERE `id`=?', array($_GET['id']));
        if($check == false)
        {
            $this->setMessage('This item doesn\'t exist!', 'FAIL');	
            header('Location: index.php?mod=Items&act=sell&class='. $_GET['class']);
            exit;
        }
        else if($check->player_id == $this->player->id)
        {
            if($check->useable == 1 and $check->in_use < $check->times_useable and $check->amount)
            {
                $this->setMessage('You can\'t sell used items!', 'FAIL');	
                header('Location: index.php?mod=Items&act=sell&class='. $_GET['class']);
                exit;
            }
            else if($check->useable == 0 and $check->in_use == 1)
            {
                $this->setMessage('This item is currently equipped!', 'FAIL');	
                header('Location: index.php?mod=Items&act=sell&class='. $_GET['class']);
                exit;
            }
            else if($check->amount == 1)
            {
                $this->db->execute('DELETE FROM `<ezrpg>playeritems` WHERE `id`=?', array($_GET['id']));
                $this->db->execute('UPDATE `<ezrpg>players_meta` SET `money`=money+? WHERE `pid`=?', array($check->sell_price, $this->player->id));
            }
            else
            {
                $this->db->execute('UPDATE `<ezrpg>playeritems` SET `amount`=amount-1 WHERE `id`=?', array($_GET['id']));
                $this->db->execute('UPDATE `<ezrpg>players_meta` SET `money`=money+? WHERE `pid`=?', array($check->sell_price, $this->player->id));
            }
            loadMetaCache(1);
            $this->setMessage('You sold '. $check->name .'!');	
            header('Location: index.php?mod=Items&act=sell&class='. $_GET['class']);
            exit;
        }
        else
        {
            $this->setMessage('This item doesn\'t belong to you!', 'FAIL');	
            header('Location: index.php?mod=Items&act=sell&class='. $_GET['class']);
            exit;
        }
    }
    
    public function install()
    {
        if ( $this->player->rank > 5 )
		{
			if(isModuleActive('Items'))
			{
				die('stopped');
			}
			
			     $query = <<<QUERY
CREATE TABLE `<ezrpg>items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `strength` int(11) NOT NULL,
  `vitality` int(11) NOT NULL,
  `agility` int(11) NOT NULL,
  `dexterity` int(11) NOT NULL,
  `max_hp` int(11) NOT NULL,
  `hp` int(11) NOT NULL,
  `max_energy` int(11) NOT NULL,
  `energy` int(11) NOT NULL,
  `times_useable` int(11) NOT NULL,
  `damage` int(11) NOT NULL,
  `buy_price` int(11) NOT NULL,
  `sell_price` int(11) NOT NULL,
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
QUERY;
$query2 = <<<QUERY
CREATE TABLE `<ezrpg>itemclass` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class` varchar(45) NOT NULL,
  `useable` int(11) NOT NULL,
  `buyable` int(11) NOT NULL,
  `sellable` int(11) NOT NULL,
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `class_UNIQUE` (`class`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
QUERY;
$query3 = <<<QUERY
CREATE TABLE `<ezrpg>playeritems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `in_use` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
QUERY;
			$this->db->execute($query);
			$this->db->execute($query2);
			$this->db->execute($query3);
			setModuleActive('Items');
			return true;
		}else 
        {
			return false;
		}
    }
    
    public function uninstall()
    {
        if ( $this->player->rank > 5 )
		    {
			     $query = "DROP TABLE IF EXISTS `<ezrpg>items`;";
			     $query2 ="DROP TABLE IF EXISTS `<ezrpg>itemclass`;";
			     $query3 ="DROP TABLE IF EXISTS `<ezrpg>playeritems`;";
			     $this->db->execute($query);
			     $this->db->execute($query2);
			     $this->db->execute($query3);
			     return true;
		    } 
        else 
        {
			     return false;
		}
    }       
}
?>