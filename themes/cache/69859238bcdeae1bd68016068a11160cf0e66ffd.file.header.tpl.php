<?php /* Smarty version Smarty-3.1.8, created on 2013-06-03 21:02:13
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/themes/default\header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1695551aca89e041730-20130047%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '69859238bcdeae1bd68016068a11160cf0e66ffd' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/themes/default\\header.tpl',
      1 => 1370286132,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1695551aca89e041730-20130047',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51aca89e10ae09_16525822',
  'variables' => 
  array (
    'TITLE' => 0,
    'ONLINE' => 0,
    'LOGGED_IN' => 0,
    'TOP_MENU_UserMenu' => 0,
    'menu' => 0,
    'menukey' => 0,
    'TOP_MENU_LOGGEDOUT' => 0,
    'player' => 0,
    'new_logs' => 0,
    'GET_MSG' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51aca89e10ae09_16525822')) {function content_51aca89e10ae09_16525822($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120\\lib\\ext\\smarty\\plugins\\modifier.date_format.php';
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
	<ul>
	<?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_smarty_tpl->tpl_vars['menukey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['TOP_MENU_UserMenu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
$_smarty_tpl->tpl_vars['menu']->_loop = true;
 $_smarty_tpl->tpl_vars['menukey']->value = $_smarty_tpl->tpl_vars['menu']->key;
?> 
	<li><a href=<?php echo $_smarty_tpl->tpl_vars['menu']->value;?>
><?php echo $_smarty_tpl->tpl_vars['menukey']->value;?>
</a></li>
	<?php } ?>
	</ul>
	<?php }else{ ?>
	<ul>
	<?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_smarty_tpl->tpl_vars['menukey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['TOP_MENU_LOGGEDOUT']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
$_smarty_tpl->tpl_vars['menu']->_loop = true;
 $_smarty_tpl->tpl_vars['menukey']->value = $_smarty_tpl->tpl_vars['menu']->key;
?> 
	<li><a href=<?php echo $_smarty_tpl->tpl_vars['menu']->value;?>
><?php echo $_smarty_tpl->tpl_vars['menukey']->value;?>
</a></li>
	<?php } ?>
	</ul>
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