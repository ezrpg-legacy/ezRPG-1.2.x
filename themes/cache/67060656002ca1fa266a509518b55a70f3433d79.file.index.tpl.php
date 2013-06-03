<?php /* Smarty version Smarty-3.1.8, created on 2013-06-03 16:30:53
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/themes/default\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2887451aca89dee2be6-11408034%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '67060656002ca1fa266a509518b55a70f3433d79' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/themes/default\\index.tpl',
      1 => 1369838356,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2887451aca89dee2be6-11408034',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51aca89e023975_70942638',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51aca89e023975_70942638')) {function content_51aca89e023975_70942638($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Home"), 0);?>


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