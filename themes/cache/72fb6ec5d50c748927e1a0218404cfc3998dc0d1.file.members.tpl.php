<?php /* Smarty version Smarty-3.1.8, created on 2013-05-30 02:03:34
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/smarty/templates\members.tpl" */ ?>
<?php /*%%SmartyHeaderCode:478451a69756e06416-51542122%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '72fb6ec5d50c748927e1a0218404cfc3998dc0d1' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/smarty/templates\\members.tpl',
      1 => 1369838357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '478451a69756e06416-51542122',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'members' => 0,
    'member' => 0,
    'prevpage' => 0,
    'nextpage' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51a69756e7db42_19082396',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a69756e7db42_19082396')) {function content_51a69756e7db42_19082396($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Members"), 0);?>


<table width="90%">
  <tr>
    <th style="text-align: left;">Username</th>
    <th style="text-align: left;">Level</th>
  </tr>

<?php  $_smarty_tpl->tpl_vars['member'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['member']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['members']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['member']->key => $_smarty_tpl->tpl_vars['member']->value){
$_smarty_tpl->tpl_vars['member']->_loop = true;
?>
  <tr>
    <td><?php echo $_smarty_tpl->tpl_vars['member']->value->username;?>
</td>
    <td><?php echo $_smarty_tpl->tpl_vars['member']->value->level;?>
</td>
  </tr>
<?php } ?>
</table>

<span class="space"></span>

<span style="display: block; width: 90%; text-align: center;">
<strong>
<a href="index.php?mod=Members&page=<?php echo $_smarty_tpl->tpl_vars['prevpage']->value;?>
">Previous Page</a> | <a href="index.php?mod=Members&page=<?php echo $_smarty_tpl->tpl_vars['nextpage']->value;?>
">Next Page</a>
</strong>
</span>

<?php echo $_smarty_tpl->getSubTemplate ("footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>