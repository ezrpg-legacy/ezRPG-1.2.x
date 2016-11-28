<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('register_after', 'setstat');

function hook_setstat($container, $args = 0)
{
    $gold = $container['config']['registration']['gold']['value'];
    $str =  $container['config']['registration']['strength']['value'];
    $vit = $container['config']['registration']['vitality']['value'];
    $agi = $container['config']['registration']['agility']['value'];
    $dex = $container['config']['registration']['dexerity']['value'];
    $container['db']->update('<ezrpg>players_meta', array('money'=>$gold, 'strength'=>$str, 'vitality'=>$vit, 'agility'=>$agi, 'dexterity'=>$dex), 'pid='.$args);
    return $args;
}

?>