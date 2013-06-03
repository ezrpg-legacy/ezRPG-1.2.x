<?php /* Smarty version Smarty-3.1.8, created on 2013-06-04 01:24:22
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/themes/admin\header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:767351ad258bbc3939-45795871%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4a87513b0f8319e1f76196aed54fc1d2be4236a9' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/themes/admin\\header.tpl',
      1 => 1370301861,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '767351ad258bbc3939-45795871',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51ad258bc3d2b0_48502646',
  'variables' => 
  array (
    'GAMESETTINGS' => 0,
    'TITLE' => 0,
    'ONLINE' => 0,
    'TOP_MENU_AdminMenu' => 0,
    'menu' => 0,
    'menukey' => 0,
    'GET_MSG' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51ad258bc3d2b0_48502646')) {function content_51ad258bc3d2b0_48502646($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120\\lib\\ext\\smarty\\plugins\\modifier.date_format.php';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta name="Description" content="ezRPG Project, the free, open source browser-based game engine!" />
<meta name="Keywords" content="ezrpg, game, game engine, pbbg, browser game, browser games, rpg, ezrpg project" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Distribution" content="Global" />
<meta name="Robots" content="index,follow" />
<link rel="stylesheet" href="../static/default/style.css" type="text/css" />	
<title><?php echo $_smarty_tpl->tpl_vars['GAMESETTINGS']->value['game_name'];?>
 :: <?php echo (($tmp = @$_smarty_tpl->tpl_vars['TITLE']->value)===null||$tmp==='' ? '' : $tmp);?>
</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script>
			// Wait until the DOM has loaded before querying the document
			$(document).ready(function(){
				$('ul.tabs').each(function(){
					// For each set of tabs, we want to keep track of
					// which tab is active and it's associated content
					var $active, $content, $links = $(this).find('a');

					// If the location.hash matches one of the links, use that as the active tab.
					// If no match is found, use the first link as the initial active tab.
					$active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
					$active.addClass('active');
					$content = $($active.attr('href'));

					// Hide the remaining content
					$links.not($active).each(function () {
						$($(this).attr('href')).hide();
					});

					// Bind the click event handler
					$(this).on('click', 'a', function(e){
						// Make the old tab inactive.
						$active.removeClass('active');
						$content.hide();

						// Update the variables with the new link and content
						$active = $(this);
						$content = $($(this).attr('href'));

						// Make the tab active.
						$active.addClass('active');
						$content.show();

						// Prevent the anchor's default click action
						e.preventDefault();
					});
				});
			});
		</script>
		<style>
		.tabs li {
				list-style:none;
				display:inline;
			}

			.tabs a {
				padding:5px 10px;
				display:inline-block;
				color:#fff;
				text-decoration:none;
			}

			.tabs a.active input{
				background:#fff;
				color:#000;
				border-bottom:5px solid #FF0000;
			}

		</style>
</head>
<body>

<div id="wrapper">

<div id="header">
	<span id="title"><?php echo $_smarty_tpl->tpl_vars['GAMESETTINGS']->value['game_name'];?>
</span>
	<span id="time"><?php echo smarty_modifier_date_format(time(),'%A %T');?>

	<br />
	<strong>Players Online</strong>: <?php echo $_smarty_tpl->tpl_vars['ONLINE']->value;?>
</span>
</div>
<div id="nav">
	<ul>
	<?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_smarty_tpl->tpl_vars['menukey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['TOP_MENU_AdminMenu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
$_smarty_tpl->tpl_vars['menu']->_loop = true;
 $_smarty_tpl->tpl_vars['menukey']->value = $_smarty_tpl->tpl_vars['menu']->key;
?> 
	<li><a href=<?php echo $_smarty_tpl->tpl_vars['menu']->value;?>
><?php echo $_smarty_tpl->tpl_vars['menukey']->value;?>
</a></li>
	<?php } ?>
	</ul>
</div>

<span class="space"></span>

<div id="body">
	<?php if ($_smarty_tpl->tpl_vars['GET_MSG']->value!=''){?><div class="msg">
	<span class="red"><strong><?php echo $_smarty_tpl->tpl_vars['GET_MSG']->value;?>
</strong></span>
	</div>
	<span class="space"></span><?php }?>
<?php }} ?>