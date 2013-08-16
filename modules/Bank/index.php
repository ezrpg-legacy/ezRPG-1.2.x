<?php
defined('IN_EZRPG') or exit;

class Module_Bank extends Base_Module
{
    public function start()
    {
		requireLogin();
		
		if(isset($_GET['act']))
		{
			switch($_GET['act'])
			{
				case 'withdraw':
					$this->withdraw();
					break;
				case 'deposit':
					$this->deposit();
					break;
				case 'transfer':
					if (isset($_GET['move']))
					{
						if($_GET['move'] == 'dosend')
							$this->dosend();
						else
							$this->loadView('transfer.tpl', 'Bank');
					}
				case 'broker':
					if (isset($_GET['move']))
					{
						if ($_GET['move'] == 'deposit'){
							$this->dbroker();
						}elseif ($_GET['move'] == 'withdraw'){
							$this->wbroker();
							break;
						}else{
							$this->loadView('broker.tpl', 'Bank');
							break;
						}
					}else{
						$this->loadView('broker.tpl', 'Bank');
						break;
					}
				default:
					$this->loadView('bank.tpl', 'Bank');
					break;
			}
		} else {
			$this->loadView('bank.tpl', 'Bank');
		}
	}

    private function wbroker()
    {
        if (!isset($_POST['amount']))
        {
            header('Location: index.php?mod=Bank&act=broker');
            exit;
        }
        
        $msg = '';
        $amount = intval($_POST['amount']);
        if ($amount < 0 || $amount > $this->player->broker)
        {
            //No query, just error message
            $msg = 'Your broker does not have much money!';
			$this->setMessage($msg, 'FAIL');
		}
        else
        {
            //Update player database
            $query = $this->db->execute('UPDATE `<ezrpg>players_meta` SET `money`=?, `broker`=? WHERE `pid`=?', array($this->player->money + $amount, $this->player->broker - $amount, $this->player->id));
            loadMetaCache(1);
            $msg = 'Broker paid out ' . $amount . ' money!';
			$this->setMessage($msg, 'GOOD');
		}
        
        //Redirect back to main bank page, including the message
		
        header('Location: index.php?mod=Bank&act=broker');
        exit;
    }
    
    private function dbroker()
    {
        if (!isset($_POST['amount']))
        {
            header('Location: index.php?mod=Bank&act=broker');
            exit;
        }
        
        $msg = '';
        $amount = intval($_POST['amount']);
        if ($amount < 0 || $amount > $this->player->money)
        {
            //No query, just error message
			$this->setMessage("You dont have enough money!", 'FAIL');			
        }
        else
        {
            //Update player database
            $query = $this->db->execute('UPDATE `<ezrpg>players_meta` SET `money`=?, `broker`=? WHERE `pid`=?', array($this->player->money - $amount, $this->player->broker + $amount, $this->player->id));
            loadMetaCache(1);
			$this->setMessage('You deposit ' . $amount . ' money!', 'GOOD');
			
        }
        
        //Redirect back to main bank page, including the message
        header('Location: index.php?mod=Bank&act=broker');
        exit;
    }
    
    private function dosend()
    {
   	if (!isset($_POST['to']))
    	{
			$this->setMessage("Please specify a subject", 'FAIL');
			
        	Header("Location: index.php?mod=Bank&act=transfer&move=compose");
        	exit;
    	}
    	elseif (!isset($_POST['money']))
    	{
			$this->setMessage("You cant send a money without a money!", 'FAIL');
        	Header("Location: index.php?mod=Bank&act=transfer&move=compose");
        	exit;
    	}
    	else
    	{
    		//so people can't send html.
    		$money = str_replace(array("\n"),array("<br />"),strip_tags($_POST['money']));
      		$subject = str_replace(array("\n"),array("<br />"),strip_tags($_POST['name']));
      		//
      		$tosql = $this->db->fetchRow('SELECT `username` FROM `<ezrpg>players_meta` WHERE `username`=?', array($_POST['to']));
    	        if ($tosql == false)
		        {
					$this->setMessage("You cant send money to someone who doesnt exist!", 'FAIL');
		        	Header("Location: index.php?mod=Bank&act=transfer&move=compose");
		            exit;
		        }
        if ($this->player->money == 0)
        {
			$this->setMessage("You dont have enough money to send!", 'FAIL');
            header('Location: index.php?mod=Bank&act=transfer');
            exit;    
        }
       if ($this->player->money < $money)
        {
			$this->setMessage("You dont have enough money to send!", 'FAIL');
            header('Location: index.php?mod=Bank&act=transfer');
            exit;    
        }
                $this->db->execute('UPDATE `<ezrpg>players_meta` SET `money`=money+? WHERE `username`=?', array($_POST['money'], $_POST['to']));
                $this->db->execute('UPDATE `<ezrpg>players_meta` SET `money`=money-? WHERE `username`=?', array(($_POST['money']+($_POST['money']/100)), $this->player->username));
				loadMetaCache(1);
                $msg='The '. $_POST['money'] .' money has been sent to '. $_POST['money'] .'! (fee = '. $_POST['money']/100 .')';
				$this->setMessage($msg, 'GOOD');
			
		Header("Location: index.php?mod=Bank&act=transfer");
    	}
    }
        
    private function withdraw()
    {
        if (!isset($_POST['amount']))
        {
            header('Location: index.php?mod=Bank');
            exit;
        }
        
        $amount = intval($_POST['amount']);
        if ($amount < 0 || $amount > $this->player->bank)
        {
            //No query, just error message
            $this->setMessage("You can not withdraw so much!", 'FAIL');
        }
        else
        {
            //Update player database
            $query = $this->db->execute('UPDATE `<ezrpg>players_meta` SET `money`=?, `bank`=? WHERE `pid`=?', array($this->player->money + $amount, $this->player->bank - $amount, $this->player->id));
            loadMetaCache(1);
            $this->setMessage("You've withdrawn " . $amount ."!", 'GOOD');
        }
        
        //Redirect back to main bank page, including the message
        header('Location: index.php?mod=Bank');
        exit;
    }
    
    private function deposit()
    {
        if (!isset($_POST['amount']))
        {
            header('Location: index.php?mod=Bank');
            exit;
        }
        
        $msg = '';
        $amount = intval($_POST['amount']);
        if ($amount < 0 || $amount > $this->player->money)
        {
            //No query, just error message
			$this->setMessage('You cannot deposit mroe than you have!');
        }
        else
        {
            //Update player database
            $query = $this->db->execute('UPDATE `<ezrpg>players_meta` SET `money`=?, `bank`=? WHERE `pid`=?', array($this->player->money - $amount, $this->player->bank + $amount, $this->player->id));
            loadMetaCache(1);
            $this->setMessage("You've deposited " . $amount ."!", 'GOOD');
        }
        
        //Redirect back to main bank page, including the message
        header('Location: index.php?mod=Bank');
        exit;
    }
    public function install()
	{
		if ( $this->player->rank > 5 )
		{
			if(isModuleActive('Bank')){
				$this->setMessage("The action you attempted doesn't exist", 'FAIL');
				header('Location: index.php?mod=Bank');
				exit;
			}
			$query = "ALTER TABLE  `<ezrpg>players_meta` ADD  `broker_time` INT(11) NOT NULL DEFAULT '0' AFTER  `money`;";
			$query2 = "ALTER TABLE  `<ezrpg>players_meta` ADD  `broker` INT(11) NOT NULL DEFAULT '0' AFTER  `money`;";
			$query3 = "ALTER TABLE  `<ezrpg>players_meta` ADD  `bank` INT(11) NOT NULL DEFAULT '0' AFTER  `money`;";
			$this->db->execute($query);
			$this->db->execute($query2);
			$this->db->execute($query3);
			secondaryInstallComplete('Bank');
			setModuleActive('Bank');
			forcePrunePlayerCache();
      return true;
		} else {
      return false;
		}
	}
	
	public function uninstall()
	{
		if ( $this->player->rank > 5 )
		{
			$query = "ALTER TABLE  `<ezrpg>players_meta` DROP  `broker_time`;";
			$query2 ="ALTER TABLE  `<ezrpg>players_meta` DROP  `broker`;";
			$query3 ="ALTER TABLE  `<ezrpg>players_meta` DROP  `bank`;";
			$this->db->execute($query);
			$this->db->execute($query2);
			$this->db->execute($query3);
			setModuleDeactive('Bank');
			forcePrunePlayerCache();
			return true;
		} else {
			return false;
		}
	}
}
?>