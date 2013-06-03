<?php /* Smarty version Smarty-3.1.8, created on 2013-05-29 17:18:55
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/smarty/templates\log.tpl" */ ?>
<?php /*%%SmartyHeaderCode:051a61c5f2418d8-44698882%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2b2d31840ff400a0ddd7930c60bd02ffe88ed916' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/smarty/templates\\log.tpl',
      1 => 1369838357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '051a61c5f2418d8-44698882',
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
  'unifunc' => 'content_51a61c5f2e0495_64857758',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a61c5f2e0495_64857758')) {function content_51a61c5f2e0495_64857758($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120\\lib\\ext\\smarty\\plugins\\modifier.date_format.php';
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