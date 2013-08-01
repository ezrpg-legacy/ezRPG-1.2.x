<?php
defined('IN_EZRPG') or exit;

$hooks->add_hook('header', 'player_cache');
$hooks->add_hook('admin_header', 'player_cache', 1);

function hook_player_cache(&$db, &$tpl, &$player, $args = 0)
{
	$sql = $db->execute('SELECT * FROM `<ezrpg>players` WHERE `force_cache` = 1');
    $query = $db->fetchAll($sql);
    if($query){
		foreach($query as $item)
		{
			killPlayerCache($item);
			$db->execute('UPDATE <ezrpg>players SET force_cache = 0 WHERE id=?', array($item));
		}
	}
    return $args;
}
?>