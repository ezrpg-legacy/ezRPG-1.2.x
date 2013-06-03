<?php /* Smarty version Smarty-3.1.8, created on 2013-05-30 02:03:33
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/smarty/templates\city.tpl" */ ?>
<?php /*%%SmartyHeaderCode:412751a69755f40610-35898601%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '566f826cdfff3190c6506882854e54429eb004aa' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/smarty/templates\\city.tpl',
      1 => 1369838356,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '412751a69755f40610-35898601',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'MENU_UserMenu' => 0,
    'MENU_WorldMenu' => 0,
    'MENU_City' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51a69756061ed1_30413976',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a69756061ed1_30413976')) {function content_51a69756061ed1_30413976($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"City"), 0);?>


<h1>City</h1>

<div class="left">

<h3>Player</h3>
<?php echo $_smarty_tpl->tpl_vars['MENU_UserMenu']->value;?>


</div>
<div class="right">

<h3>World</h3>
<?php echo $_smarty_tpl->tpl_vars['MENU_WorldMenu']->value;?>



<h3>City</h3>

<?php echo $_smarty_tpl->tpl_vars['MENU_City']->value;?>



</div>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php }} ?>