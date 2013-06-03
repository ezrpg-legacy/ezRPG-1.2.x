<?php /* Smarty version Smarty-3.1.8, created on 2013-06-03 21:13:46
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/themes/default\city.tpl" */ ?>
<?php /*%%SmartyHeaderCode:495751ace9bf3a21d0-27769562%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '39fac52034847cacf0ac33c70b3eb5fd0e541f41' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/themes/default\\city.tpl',
      1 => 1370286826,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '495751ace9bf3a21d0-27769562',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51ace9bf425bf5_99943187',
  'variables' => 
  array (
    'MENU_UserMenu' => 0,
    'menu' => 0,
    'menukey' => 0,
    'MENU_WorldMenu' => 0,
    'MENU_City' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51ace9bf425bf5_99943187')) {function content_51ace9bf425bf5_99943187($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"City"), 0);?>


<h1>City</h1>

<div class="left">

<h3>Player</h3>

<ul>
	<?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_smarty_tpl->tpl_vars['menukey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['MENU_UserMenu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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
<div class="right">

<h3>World</h3>

<ul>
	<?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_smarty_tpl->tpl_vars['menukey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['MENU_WorldMenu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['menu']->key => $_smarty_tpl->tpl_vars['menu']->value){
$_smarty_tpl->tpl_vars['menu']->_loop = true;
 $_smarty_tpl->tpl_vars['menukey']->value = $_smarty_tpl->tpl_vars['menu']->key;
?> 
<li><a href=<?php echo $_smarty_tpl->tpl_vars['menu']->value;?>
><?php echo $_smarty_tpl->tpl_vars['menukey']->value;?>
</a></li>
	<?php } ?>
</ul>

<h3>City</h3>

<ul>
	<?php  $_smarty_tpl->tpl_vars['menu'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['menu']->_loop = false;
 $_smarty_tpl->tpl_vars['menukey'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['MENU_City']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
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

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>