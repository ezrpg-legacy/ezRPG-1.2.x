<?php /* Smarty version Smarty-3.1.8, created on 2013-05-29 17:18:24
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/smarty/templates\header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2656451a61c4081a291-23097584%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ebcdb3b2850ea4c26d4b27fb843b15e784d718d3' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/smarty/templates\\header.tpl',
      1 => 1369838356,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2656451a61c4081a291-23097584',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'TITLE' => 0,
    'ONLINE' => 0,
    'LOGGED_IN' => 0,
    'TOP_MENU_UserMenu' => 0,
    'TOP_MENU_LOGGEDOUT' => 0,
    'player' => 0,
    'new_logs' => 0,
    'GET_MSG' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51a61c408a2dc2_09223452',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a61c408a2dc2_09223452')) {function content_51a61c408a2dc2_09223452($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120\\lib\\ext\\smarty\\plugins\\modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta name="Description" content="ezRPG Project, the free, open source browser-based game engine!" />
<meta name="Keywords" content="ezrpg, game, game engine, pbbg, browser game, browser games, rpg, ezrpg project" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Distribution" content="Global" />
<meta name="Robots" content="index,follow" />
<link rel="stylesheet" href="static/default/style.css" type="text/css" />	
<title>ezRPG :: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['TITLE']->value)===null||$tmp==='' ? '' : $tmp);?>
</title>
</head>
<body>

<div id="wrapper">

<div id="header">
	<span id="title">ezRPG</span>
	<span id="time"><?php echo smarty_modifier_date_format(time(),'%A %T');?>

	<br />
	<strong>Players Online</strong>: <?php echo $_smarty_tpl->tpl_vars['ONLINE']->value;?>
</span>
</div>

<div id="nav">
	<?php if ($_smarty_tpl->tpl_vars['LOGGED_IN']->value=='TRUE'){?>
	<?php echo $_smarty_tpl->tpl_vars['TOP_MENU_UserMenu']->value;?>

	<?php }else{ ?>
	<?php echo $_smarty_tpl->tpl_vars['TOP_MENU_LOGGEDOUT']->value;?>

	<?php }?>
</div>

<span class="space"></span>

<?php if ($_smarty_tpl->tpl_vars['LOGGED_IN']->value=='TRUE'){?>
<div id="sidebar">
<strong>Level</strong>: <?php echo $_smarty_tpl->tpl_vars['player']->value->level;?>
<br />
<strong>Gold</strong>: <?php echo $_smarty_tpl->tpl_vars['player']->value->money;?>
<br />

<img src="bar.php?width=140&type=exp" alt="EXP: <?php echo $_smarty_tpl->tpl_vars['player']->value->exp;?>
 / <?php echo $_smarty_tpl->tpl_vars['player']->value->max_exp;?>
" /><br />
<img src="bar.php?width=140&type=hp" alt="HP: <?php echo $_smarty_tpl->tpl_vars['player']->value->hp;?>
 / <?php echo $_smarty_tpl->tpl_vars['player']->value->max_hp;?>
" /><br />
<img src="bar.php?width=140&type=energy" alt="Energy: <?php echo $_smarty_tpl->tpl_vars['player']->value->energy;?>
 / <?php echo $_smarty_tpl->tpl_vars['player']->value->max_energy;?>
" /><br />

<?php if ($_smarty_tpl->tpl_vars['new_logs']->value>0){?>
<a href="index.php?mod=EventLog" class="red"><strong><?php echo $_smarty_tpl->tpl_vars['new_logs']->value;?>
 New Log Events!</strong></a>
<?php }?>
</div>
<?php }?>

<div id="<?php if ($_smarty_tpl->tpl_vars['LOGGED_IN']->value=='TRUE'){?>gamebody<?php }else{ ?>body<?php }?>">
	<?php if ($_smarty_tpl->tpl_vars['GET_MSG']->value!=''){?><div class="msg">
	<span class="red"><strong><?php echo $_smarty_tpl->tpl_vars['GET_MSG']->value;?>
</strong></span>
	</div>
	<span class="space"></span><?php }?>
<?php }} ?>