<?php /* Smarty version Smarty-3.1.8, created on 2013-05-30 02:03:36
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/smarty/templates\account_settings.tpl" */ ?>
<?php /*%%SmartyHeaderCode:355451a69758014b41-79764691%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'efe78b6610ecc10f02a567b2adde88f165bb56af' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/smarty/templates\\account_settings.tpl',
      1 => 1369838356,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '355451a69758014b41-79764691',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51a69758060726_16955019',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a69758060726_16955019')) {function content_51a69758060726_16955019($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Account Settings"), 0);?>


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