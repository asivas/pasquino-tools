<?php /* Smarty version Smarty 3.1.4, created on 2014-10-08 16:16:59
         compiled from "0400329494a2af8720189fafdf454111de13ab41" */ ?>
<?php /*%%SmartyHeaderCode:377654358dab265e70-47080958%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0400329494a2af8720189fafdf454111de13ab41' => 
    array (
      0 => '0400329494a2af8720189fafdf454111de13ab41',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '377654358dab265e70-47080958',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'msg' => 0,
    'status' => 0,
    'otros' => 0,
    'k' => 0,
    'valor' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_54358daba776e',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54358daba776e')) {function content_54358daba776e($_smarty_tpl) {?><status msg="<?php echo $_smarty_tpl->tpl_vars['msg']->value;?>
" status="<?php echo $_smarty_tpl->tpl_vars['status']->value;?>
" <?php  $_smarty_tpl->tpl_vars['valor'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['valor']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['otros']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['valor']->key => $_smarty_tpl->tpl_vars['valor']->value){
$_smarty_tpl->tpl_vars['valor']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['valor']->key;
?> <?php echo $_smarty_tpl->tpl_vars['k']->value;?>
="<?php echo $_smarty_tpl->tpl_vars['valor']->value;?>
" <?php } ?>></status><?php }} ?>