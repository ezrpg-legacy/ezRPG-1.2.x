<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('register_after', 'setstat');

function hook_setstat($container, $args = 0)
{
    $gold = $container['settings']->setting['registration']['newgold']['value'];
    $str =  $container['settings']->setting['registration']['strength']['value'];
    $vit = $container['settings']->setting['registration']['vitality']['value'];
    $agi = $container['settings']->setting['registration']['agility']['value'];
    $dex = $container['settings']->setting['registration']['dexerity']['value'];
    $container['db']->update('<ezrpg>players_meta', array('money'=>$gold, 'strength'=>$str, 'vitality'=>$vit, 'agility'=>$agi, 'dexterity'=>$dex), 'pid='.$args);
    return $args;
}

?>