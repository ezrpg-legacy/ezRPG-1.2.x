<?php

defined('IN_EZRPG') or exit;

/*
  Class: Admin_Items
  Module for creating and editing Itemclasses and Items
 */

class Admin_Items extends Base_Module
{
    public function start()
    {        
        if (isset($_GET['act']))
        {
            switch($_GET['act'])
            {
            case 'home':
                $this->home();
                break;
            case 'class':
                $this->itemclass();
                break;
            case 'addclass':
                $this->addclass();
                break;    
            case 'doaddclass':
                $this->doaddclass();
                break;
            case 'editclass':
                $this->editclass();
                break;     
            case 'doeditclass':
                $this->doeditclass();
                break; 
            case 'item':
                $this->items();
                break;
            case 'additem':
                $this->additem();
                break;    
            case 'doadditem':
                $this->doadditem();
                break;
            case 'edititem':
                $this->edititem();
                break;     
            case 'doedititem':
                $this->doedititem();
                break;
            case 'deleteitem':
                $this->deleteitem();
                break;
            default:
                $this->home();
                break;
            }
        }
        else
        {
            $this->home();
        }
    }
    
    private function home()
    {
        $query = $this->db->execute('SELECT * FROM `<ezrpg>itemclass`');
        $class = $this->db->fetchAll($query);
        
        $this->tpl->assign('class', $class);
        $this->loadView('Items/home.tpl');    
    }
    
    private function itemclass()
    {
        $query = $this->db->execute('SELECT * FROM `<ezrpg>itemclass`');
        $class = $this->db->fetchAll($query);
        
        $this->tpl->assign('class', $class);
        $this->loadView('Items/class.tpl');     
    }
    
    private function addclass()
    {
        $this->loadView('Items/addclass.tpl');        
    }
    
    private function doaddclass()
    {
        if (empty($_POST['name']))
        {
            $this->setMessage('You forgot to fill the form!', 'FAIL');	
            header('Location: index.php?mod=Items&act=addclass');
            exit;
        }
        else
        {        
            if ($_POST['useable'] == 1)
            {
                $useable=1;
            }
            else
            {
                $useable=0;
            }
            if ($_POST['buyable'] == 1)
            {
                $buyable=1;
            }
            else
            {
                $buyable=0;
            }
            if ($_POST['sellable'] == 1)
            {
                $sellable=1;
            }
            else
            {
                $sellable=0;
            }
            $insert['class'] = $_POST['name'];
            $insert['useable'] = $useable;
            $insert['buyable'] = $buyable;
            $insert['sellable'] = $sellable;
            
            $this->db->insert('<ezrpg>itemclass', $insert);
            
            $this->setMessage('You successfully added a class!');	
            header('Location: index.php?mod=Items&act=class');
            exit;
        }        
    }
    
    private function editclass()
    {
        $edit = $this->db->fetchRow('SELECT * FROM `<ezrpg>itemclass` WHERE `class_id`=?', array($_GET['id']));
        
        $this->tpl->assign('edit', $edit);
        $this->loadView('Items/editclass.tpl');        
    }
    
    private function doeditclass()
    {
        if (empty($_POST['name']))
        {
            $this->setMessage('You forgot to fill the form!', 'FAIL');	
            header('Location: index.php?mod=Items&act=editclass&id='. $_POST['id']);
            exit;
        }
        else
        {        
            if ($_POST['useable'] == 1)
            {
                $useable=1;
            }
            else
            {
                $useable=0;
            }
            if ($_POST['buyable'] == 1)
            {
                $buyable=1;
            }
            else
            {
                $buyable=0;
            }
            if ($_POST['sellable'] == 1)
            {
                $sellable=1;
            }
            else
            {
                $sellable=0;
            }            
            $this->db->execute('UPDATE `<ezrpg>itemclass` SET `class`=?, `useable`=?, `buyable`=?, `sellable`=? WHERE `class_id`=?', array($_POST['name'], $useable, $buyable, $sellable, $_GET['id']));
            
            $this->setMessage('You successfully edited a class!');	
            header('Location: index.php?mod=Items&act=class');
            exit;
        }
    }
    
    private function deleteclass()
    {
        $this->db->execute('DELETE FROM `<ezrpg>itemclass` WHERE `class_id`=?', array($_GET['id']));
        
        $this->setMessage('You successfully deleted a class!');	
        header('Location: index.php?mod=Items&act=class');
        exit;        
    }
    
    private function items()
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
        WHERE `<ezrpg>itemclass`.`class_id`=? ORDER BY `item_id` ASC LIMIT ?,50', array($_GET['classid'], $page * 50));
        $items = $this->db->fetchAll($query);
      
        $query2 = $this->db->execute('SELECT * FROM `<ezrpg>items` WHERE `<ezrpg>items`.`class_id`=?', array($_GET['classid']));
        
        $curpage = $page;
        $rows = $this->db->numRows($query2);
        $maxpages = (ceil($rows / 50) - 1);       
        $prevpage = (($page - 1) >= 0) ? ($page - 1) : 0;
        $classid = $_GET['classid'];
           
        $this->tpl->assign('classid', $classid);
        $this->tpl->assign('curpage', $curpage);
        $this->tpl->assign('maxpages', $maxpages);
        $this->tpl->assign('nextpage', ++$page);
        $this->tpl->assign('prevpage', $prevpage);  
        $this->tpl->assign('items', $items);
        $this->loadView('Items/items.tpl');  
    } 
    
    private function additem()
    {
        $class = $this->db->fetchRow('SELECT * FROM `<ezrpg>itemclass` WHERE `class_id`=?', array($_GET['classid']));
        
        $this->tpl->assign('class', $class);
        $this->loadView('Items/additem.tpl');
    }
    
    private function doadditem()
    {    
        if (empty($_POST['name'])  || empty($_POST['buy_price']) || empty($_POST['sell_price']))
        {
            $this->setMessage('You forgot to fill the form!', 'FAIL');	
            header('Location: index.php?mod=Items&act=additem&classid='. $_GET['classid']);
            exit;
        }
        else
        {    
            $insert['class_id'] = $_GET['classid'];
            $insert['name'] = $_POST['name'];
            if(isset($_POST['strength'])) {$insert['strength'] = $_POST['strength'];} else {$insert['strength']=0;}
            if(isset($_POST['vitality'])) {$insert['vitality'] = $_POST['vitality'];} else {$insert['vitality']=0;}
            if(isset($_POST['agility'])) {$insert['agility'] = $_POST['agility'];} else {$insert['agility']=0;}
            if(isset($_POST['dexterity'])) {$insert['dexterity'] = $_POST['dexterity'];} else {$insert['dexterity']=0;}
            if(isset($_POST['max_hp'])) {$insert['max_hp'] = $_POST['max_hp'];} else {$insert['max_hp']=0;}
            if(isset($_POST['hp'])) {$insert['hp'] = $_POST['hp'];} else {$insert['hp']=0;}
            if(isset($_POST['max_energy'])) {$insert['max_energy'] = $_POST['max_energy'];} else {$insert['max_energy']=0;}
            if(isset($_POST['energy'])) {$insert['energy'] = $_POST['energy'];} else {$insert['energy']=0;}
            if(isset($_POST['times_useable'])) {$insert['times_useable'] = $_POST['times_useable'];} else {$insert['times_useable']=0;}
            if(isset($_POST['damage'])) {$insert['damage'] = $_POST['damage'];} else {$insert['damage']=0;}
            if(isset($_POST['buy_price'])) {$insert['buy_price'] = $_POST['buy_price'];} else {$insert['buy_price']=0;}
            if(isset($_POST['sell_price'])) {$insert['sell_price'] = $_POST['sell_price'];} else {$insert['sell_price']=0;}                             

            $this->db->insert('<ezrpg>items', $insert);
            
            $this->setMessage('You successfully added a item!');	
            header('Location: index.php?mod=Items&act=additem&classid='. $_GET['classid']);
            exit;        
            } 
    }
    
    private function edititem()
    {
        $items = $this->db->fetchRow('SELECT * FROM `<ezrpg>itemclass` INNER JOIN `<ezrpg>items` ON
        `<ezrpg>itemclass`.`class_id`=`<ezrpg>items`.`class_id` WHERE `item_id`=?', array($_GET['id']));
        
        $this->tpl->assign('items', $items);
        $this->loadView('Items/edititem.tpl');
    }
    
    private function doedititem()
    {        
        if (empty($_POST['name'])  || empty($_POST['buy_price']) || empty($_POST['sell_price']))
        {
            $this->setMessage('You forgot to fill the form!', 'FAIL');	
            header('Location: index.php?mod=Items&act=edititem&id='. $_GET['id']);
            exit;
        }
        else
        {    
            $insert['name'] = $_POST['name'];
            if(isset($_POST['strength'])) {$insert['strength'] = $_POST['strength'];} else {$insert['strength']=0;}
            if(isset($_POST['vitality'])) {$insert['vitality'] = $_POST['vitality'];} else {$insert['vitality']=0;}
            if(isset($_POST['agility'])) {$insert['agility'] = $_POST['agility'];} else {$insert['agility']=0;}
            if(isset($_POST['dexterity'])) {$insert['dexterity'] = $_POST['dexterity'];} else {$insert['dexterity']=0;}
            if(isset($_POST['max_hp'])) {$insert['max_hp'] = $_POST['max_hp'];} else {$insert['max_hp']=0;}
            if(isset($_POST['hp'])) {$insert['hp'] = $_POST['hp'];} else {$insert['hp']=0;}
            if(isset($_POST['max_energy'])) {$insert['max_energy'] = $_POST['max_energy'];} else {$insert['max_energy']=0;}
            if(isset($_POST['energy'])) {$insert['energy'] = $_POST['energy'];} else {$insert['energy']=0;}
            if(isset($_POST['times_useable'])) {$insert['times_useable'] = $_POST['times_useable'];} else {$insert['times_useable']=0;}
            if(isset($_POST['damage'])) {$insert['damage'] = $_POST['damage'];} else {$insert['damage']=0;}
            if(isset($_POST['buy_price'])) {$insert['buy_price'] = $_POST['buy_price'];} else {$insert['buy_price']=0;}
            if(isset($_POST['sell_price'])) {$insert['sell_price'] = $_POST['sell_price'];} else {$insert['sell_price']=0;}                             

            $this->db->execute('UPDATE `<ezrpg>items` SET `name`=?, `strength`=?, `vitality`=?, `agility`=?, `dexterity`=?, `max_hp`=?, `hp`=?, `max_energy`=?, `energy`=?, `times_useable`=?,`damage`=?, `buy_price`=?, `sell_price`=? WHERE `item_id`=?', array($insert['name'], $insert['strength'], $insert['vitality'], $insert['agility'], $insert['dexterity'], $insert['max_hp'], $insert['hp'], $insert['max_energy'], $insert['energy'], $insert['times_useable'], $insert['damage'], $insert['buy_price'], $insert['sell_price'], $_GET['id']));
            
            $this->setMessage('You successfully added a item!');	
            header('Location: index.php?mod=Items&act=item&classid='. $_GET['classid']);
            exit;        
            }
    }
    
    private function deleteitem()
    {
        $class = $this->db->fetchRow('SELECT * FROM `<ezrpg>items` WHERE `item_id`=?', array($_GET['id']));
        $this->db->execute('DELETE FROM `<ezrpg>items` WHERE `item_id`=?', array($_GET['id']));
        
        $this->setMessage('You successfully deleted an item!');	
        header('Location: index.php?mod=Items&act=item&classid='. $class->class_id);
        exit;        
    } 
}

?>
