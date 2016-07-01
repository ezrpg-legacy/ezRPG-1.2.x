<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 6/29/2016
 * Time: 9:05 PM
 */

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Attack_Module
  Preform the Attacks
  Requires an AttackLog Table
 */

class Module_Attack extends Base_Module
{
    public function __construct($db, $tpl, $player, $menu, $settings)
    {
        parent::__construct($db, $tpl, $player, $menu, $settings);
    }

    /*
      Function: start
      Displays the members list page.
     */

    public function start()
    {
        global $player;
        //Require login
        requireLogin();

        if(isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], 'mod=Members')){
            $numquery = $this->db->execute('SELECT count(*) as num FROM `<ezrpg>attack_log` WHERE alog_attacker = ? AND alog_defender = ? AND alog_timestamp > NOW() - INTERVAL 24 HOUR', array($player->id, $_GET['p']));
            $numofattacks = $this->db->fetch($numquery);
            if($numofattacks->num < 3) {
                $attmult = rand(7, 8);
                $attstr = $player->strength * $attmult;
                $attagil = $player->agility * $attmult;
                $attscore = (($attstr) + ($attagil)) * $attmult;
                $query = $this->db->execute('SELECT * FROM `<ezrpg>players` INNER JOIN `<ezrpg>players_meta` ON `pid` = `<ezrpg>players`.`id` WHERE `<ezrpg>players`.`id` = ?',
                    array($_GET['p']));
                $defquery = $this->db->fetch($query);
                $defmulti = rand(5, 7);
                $defstr = $defquery->strength * $defmulti;
                $defvit = $defquery->vitality * $defmulti;
                $defagil = $defquery->agility * $defmulti;
                $defscore = (($defstr) + (($defagil + ($defvit))) * $defmulti);
                $results = "Attacker: " . $attscore . " ( Str=$attstr Agil=$attagil Multi=$attmult ) 
            <br/> Defender: " . $defscore . " (Str=$defstr * (Agil+Vit=$defagil + $defvit) * Multi=$defmulti )";
                $insert = array();
                $insert['alog_victory'] = "0";
                $insert['alog_attacker'] = $player->id;
                $insert['alog_defender'] = $defquery->id;
                if ($attscore > $defscore) {
                    $insert['alog_victory'] = "1";
                }
                $this->db->insert('<ezrpg>attack_log', $insert);
                $this->setMessage("You have " . ($insert['alog_victory'] == "1" ? 'won' : 'lost') . " your attack against " . $defquery->username);
                //$this->setMessage($results);
                header('Location: index.php?mod=Members');
            }else{
                $this->setMessage("You've already attacked this player 3x today!", 'warn');
                header('Location: index.php?mod=Members');
            }
        }else{
            $this->setMessage("You can't access this page that way!", 'warn');
            header('Location: index.php?mod=Members');
        }
    }

}