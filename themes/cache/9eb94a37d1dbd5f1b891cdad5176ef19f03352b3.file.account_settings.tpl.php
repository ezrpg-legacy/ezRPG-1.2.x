<?php /* Smarty version Smarty-3.1.8, created on 2013-06-03 21:16:59
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/themes/default\account_settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1761851acebabe62688-58835871%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9eb94a37d1dbd5f1b891cdad5176ef19f03352b3' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/themes/default\\account_settings.tpl',
      1 => 1369838356,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1761851acebabe62688-58835871',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51acebabed9947_16320568',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51acebabed9947_16320568')) {function content_51acebabed9947_16320568($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Account Settings"), 0);?>


<h1>Account Settings</h1>

<p>
Here you can change your password.
</p>

<form method="post" action="index.php?mod=AccountSettings">

<label>Current Password</label>
<input type="password" size="40" name="current_password" autocomplete="off" />

<label>New Password</label>
<input type="password" size="40" name="new_password" autocomplete="off" />

<label>Verify New Password</label>
<input type="password" size="40" name="new_password2" autocomplete="off" />

<br />
<input name="change_password" type="submit" value="Change Password" class="button" />

</form>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>