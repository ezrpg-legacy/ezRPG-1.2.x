<?php /* Smarty version Smarty-3.1.8, created on 2013-05-30 02:03:41
         compiled from "C:\Users\Tim\Downloads\USBWebserverv8en\root\ezRPG120/smarty/templates\admin\members.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3262051a6975d0e5803-57318971%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '754dd55d9ed1bb1d27636fc29c51ba28e4e4c210' => 
    array (
      0 => 'C:\\Users\\Tim\\Downloads\\USBWebserverv8en\\root\\ezRPG120/smarty/templates\\admin\\members.tpl',
      1 => 1369838357,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3262051a6975d0e5803-57318971',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'playercount' => 0,
    'members' => 0,
    'member' => 0,
    'prevpage' => 0,
    'nextpage' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51a6975d16b085_61146456',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51a6975d16b085_61146456')) {function content_51a6975d16b085_61146456($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("admin/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array('TITLE'=>"Members Admin"), 0);?>


<h1>Members</h1>

<p>
<strong>Total Players: </strong> <?php echo $_smarty_tpl->tpl_vars['playercount']->value;?>

</p>

<table width="100%">
  <tr>
    <th style="text-align: left;">Username</th>
    <th style="text-align: left;">Email</th>
    <th style="text-align: left;">Actions</th>
  </tr>

<?php  $_smarty_tpl->tpl_vars['member'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['member']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['members']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['member']->key => $_smarty_tpl->tpl_vars['member']->value){
$_smarty_tpl->tpl_vars['member']->_loop = true;
?>
  <tr>
    <td><?php echo $_smarty_tpl->tpl_vars['member']->value->username;?>
</td>
    <td><a href="mailto:<?php echo $_smarty_tpl->tpl_vars['member']->value->email;?>
"><?php echo $_smarty_tpl->tpl_vars['member']->value->email;?>
</a></td>
    <td>
      <a href="index.php?mod=Members&act=edit&id=<?php echo $_smarty_tpl->tpl_vars['member']->value->id;?>
"><strong>Edit</strong></a> | <a href="index.php?mod=Members&act=delete&id=<?php echo $_smarty_tpl->tpl_vars['member']->value->id;?>
"><strong>Delete</strong>
    </td>
  </tr>
<?php } ?>
</table>

<span class="space"></span>

<span style="display: block; width: 90%; text-align: center;">
<strong>
<a href="index.php?mod=Members&page=<?php echo $_smarty_tpl->tpl_vars['prevpage']->value;?>
">Previous Page</a>
|
<a href="index.php?mod=Members&page=<?php echo $_smarty_tpl->tpl_vars['nextpage']->value;?>
">Next Page</a>
</strong>
</span>

<?php echo $_smarty_tpl->getSubTemplate ("admin/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>