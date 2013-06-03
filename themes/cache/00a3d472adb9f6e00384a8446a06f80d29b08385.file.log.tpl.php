<?php /* Smarty version Smarty-3.1.8, created on 2013-06-03 21:16:57
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/themes/default\log.tpl" */ ?>
<?php /*%%SmartyHeaderCode:335151aceba952ac13-49478550%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '00a3d472adb9f6e00384a8446a06f80d29b08385' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/themes/default\\log.tpl',
      1 => 1369838357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '335151aceba952ac13-49478550',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'logs' => 0,
    'log' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51aceba95f6144_32733040',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51aceba95f6144_32733040')) {function content_51aceba95f6144_32733040($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120\\lib\\ext\\smarty\\plugins\\modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Event Log"), 0);?>


<?php if ($_smarty_tpl->tpl_vars['logs']->value){?>
	<form method="post" action="index.php?mod=EventLog&act=clear">
	<input type="submit" value="Clear Messages" />
	</form>

	<span class="space"></span>

	<?php  $_smarty_tpl->tpl_vars['log'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['log']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['logs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['log']->key => $_smarty_tpl->tpl_vars['log']->value){
$_smarty_tpl->tpl_vars['log']->_loop = true;
?>
		<div class="block">
		<?php if ($_smarty_tpl->tpl_vars['log']->value->status==0){?>
			<span class="red"><strong><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['log']->value->time,'%B %e, %Y %l:%M %p');?>
</strong></span>
		<?php }else{ ?>
			<?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['log']->value->time,'%B %e, %Y %l:%M %p');?>

		<?php }?>
		<span class="space"></span>
		<?php echo $_smarty_tpl->tpl_vars['log']->value->message;?>

		<span class="space"></span>
		</div>
	<?php } ?>
<?php }else{ ?>
	<p>
	<strong>You have no log messages!</strong>
	</p>
<?php }?>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>