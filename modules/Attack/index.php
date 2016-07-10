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
            if($player->energy !=0 || $player->hp !=0)
            {
                if($numofattacks->num < 3) {
                    $Attacker = $this->getAttack($player);
                    $query = $this->db->execute('SELECT * FROM `<ezrpg>players` INNER JOIN `<ezrpg>players_meta` ON `pid` = `<ezrpg>players`.`id` WHERE `<ezrpg>players`.`id` = ?',
                        array($_GET['p']));
                    $defquery = $this->db->fetch($query);
                    $Defender = $this->getDefense($defquery);
                    $results = "Attacker: " . $Attacker['Score'] . " ( Str=". $Attacker['Strength'] ." Agil=". $Attacker['Agility'] ." Multi=".$Attacker['Multi']. " ) ".
                    "<br/> Defender: " . $Defender['Score'] . " (Str=". $Defender['Strength']." * (Agil+Vit=" . $Defender['Agility']." + ". $Defender['Vitality']. ") * Multi=". $Defender['Multi'] ." )";
                    $insert = array();
                    $insert['alog_victory'] = $victor = "0";
                    $insert['alog_attacker'] = $player->id;
                    $insert['alog_defender'] = $defquery->id;
                    if ($Attacker['Score'] > $Defender['Score']) {
                        $insert['alog_victory'] = $victor = "1";
                    }
                    $this->db->insert('<ezrpg>attack_log', $insert);

                    $AttackDamage=$this->getdamage($player, $victor);
                    $AttackNewHP = $afields['hp'] = $AttackDamage['HP'];
                    $AttackNewXP = $afields['exp'] = $AttackDamage['XP'];
                    $afields['energy'] = $player->energy - 1;
                    $DefenseDamage = $this->getDamage($defquery, ($victor?'0':'1'));
                    $DefenseNewHP = $dfields['hp'] = $DefenseDamage['HP'];
                    $DefenseNewXP = $dfields['exp'] = $DefenseDamage['XP'];
                    $results = ("Battle Start " . "<br />".
                        "Attack Stats:" . $Attacker['HP'] . "HP " . $Attacker['XP'] . "XP" . "<br />".
                        "Defender Stats:" . $Defender['HP'] . "HP" . $Defender['XP'] . "XP" . "<br />".
                        "Attacker Results: " . $AttackNewHP . "HP " . $AttackNewXP . "XP". "<br />".
                        "Defender Results: " . $DefenseNewHP . "HP " . $DefenseNewXP . "XP" . "<br />".
                        "Attacker: ".$AttackDamage['Debug'] . "  Defender: ". $DefenseDamage['Debug'] . "  Winner: " . ($victor?'Attacker':'Defender'));


                    $this->db->update('<ezrpg>players_meta', $afields, "ID = '" . $this->player->id . "'");
                    $this->db->update('<ezrpg>players_meta', $dfields, "ID = '" . $defquery->id . "'");
                    killPlayerCache($player->id);
                    killPlayerCache($defquery->id);

                    $this->setMessage("You have " . ($insert['alog_victory'] == "1" ? 'won' : 'lost') . " your attack against " . $defquery->username);
                    $this->setMessage($results);
                    header('Location: index.php?mod=Members');
                }else{
                    $this->setMessage("You've already attacked this player 3x today!", 'warn');
                    header('Location: index.php?mod=Members');
                }
            }elseif($player->energy == 0){
                $this->setMessage("You don't have any Energy to attack!", 'warn');
                header('Location: index.php?mod=Members');
            }else{
                $this->setMessage("You don't have any HP to attack!", 'warn');
                header('Location: index.php?mod=Members');
            }

        }else{
            $this->setMessage("You can't access this page that way!", 'warn');
            header('Location: index.php?mod=Members');
        }
    }

    private function getAttack($attacker){
        $attmult = rand(7, 8);
        $attstr = $attacker->strength * $attmult;
        $attagil = $attacker->agility * $attmult;
        return array('Score'=>(($attstr) + ($attagil)) * $attmult,
                     'Strength'=>$attstr,
                     'Agility' => $attagil,
                     'Multi'   => $attmult,
                     'HP'      => $attacker->hp,
                     'XP'      => $attacker->exp,
                     'Level'   => $attacker->level
        );
    }

    private function getDefense($defender){
        $defmulti = rand(5, 7);
        $defstr = $defender->strength * $defmulti;
        $defvit = $defender->vitality * $defmulti;
        $defagil = $defender->agility * $defmulti;
        $defscore = (($defstr) + (($defagil + ($defvit))) * $defmulti);
        return array('Strength' => $defstr,
                     'Agility' => $defagil,
                     'Vitality' => $defvit,
                     'Score' => $defscore,
                     'Multi' => $defmulti,
                     'HP'      => $defender->hp,
                     'XP'      => $defender->exp,
                     'Level'   => $defender->level
        );
    }

    private function getDamage($player, $victor = false)
    {
        if($victor) {
            return array('HP' => $player->hp - round(rand($player->hp / 6, $player->hp)),
                         'XP' => ($player->exp !='0' ? $player->exp + round(rand($player->exp/ 5, $player->exp / 2)): rand(6,12)),
                         'Debug' => $player->exp
            );
        }else{
            return array('HP' => $player->hp - rand($player->hp / 2, $player->hp + 10),
                         'XP' => ($player->exp !='0' ? $player->exp + round(rand($player->exp/ 6, $player->exp / 4)) : rand(3,8)),
                         'Debug' => $player->exp
            );
        }
    }
}