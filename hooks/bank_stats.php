<?php
defined('IN_EZRPG') or exit;

$hooks->add_hook('player', 'bank_stats', 2);

function hook_bank_stats($db, &$tpl, $player, $args = 0)
{
	if (!isModuleActive('Bank')){
		return $args;
	}
    if ($args === 0 || LOGGED_IN == false){
        return $args;
    }
	print_r($args);
    $Btime = 1440; // time in min (one day)
	if($args->broker_time < floor( (time() - $args->broker_time) / ($Btime * 60))){
		if((rand(0,99)+1) <= 95){
			$new_money = $args->bank + ($args->broker / 100);
			$db->execute('UPDATE <ezrpg>players_meta SET bank = ?, broker_time = ? WHERE pid = ?', array($new_money, time(), $args->id));
		} else {
			$new_money = $args->broker - ($args->broker / 2);
			$db->execute('UPDATE <ezrpg>players_meta SET broker = ?, broker_time = ? WHERE pid = ?', array($new_money, time(), $args->id));
		}
	}

    return $args;
}
?>
