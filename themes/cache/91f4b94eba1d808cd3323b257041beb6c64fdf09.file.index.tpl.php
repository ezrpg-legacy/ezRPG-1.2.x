<?php /* Smarty version Smarty-3.1.8, created on 2013-05-29 17:18:24
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/smarty/templates\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:72751a61c407a1273-70062872%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '91f4b94eba1d808cd3323b257041beb6c64fdf09' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/smarty/templates\\index.tpl',
      1 => 1369838356,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '72751a61c407a1273-70062872',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51a61c407f7ce3_02808698',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a61c407f7ce3_02808698')) {function content_51a61c407f7ce3_02808698($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Home"), 0);?>


<h1>Home</h1>

<div class="left">
<p>
Welcome to ezRPG! Login now!
</p>
</div>

<div class="right">
<form method="post" action="index.php?mod=Login">
<label>Username</label>
<input type="text" name="username" />

<label>Password</label>
<input type="password" name="password" />

<input name="login" type="submit" class="button" value="Login!">
</form>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>