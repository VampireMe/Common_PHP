<?php /* Smarty version 3.1.27, created on 2015-09-04 11:11:58
         compiled from "tpl\common.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:2265155e9605e7b9bf3_62485938%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7440ac163ef29c374bb890f6deb51290392effca' => 
    array (
      0 => 'tpl\\common.tpl',
      1 => 1441357913,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2265155e9605e7b9bf3_62485938',
  'variables' => 
  array (
    'title' => 0,
    'str' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_55e9605e911854_68772435',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_55e9605e911854_68772435')) {
function content_55e9605e911854_68772435 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_cut')) require_once 'plugins\\modifier.cut.php';
if (!is_callable('smarty_function_input')) require_once 'plugins\\function.input.php';

$_smarty_tpl->properties['nocache_hash'] = '2265155e9605e7b9bf3_62485938';
?>
<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>

</head>


<body>
	主内容为：<?php echo smarty_modifier_cut($_smarty_tpl->tpl_vars['str']->value);?>

	
	<br>
	
	自定义函数：input :
	<?php echo smarty_function_input(array('name'=>"username",'width'=>20,'value'=>"请输入"),$_smarty_tpl);?>

</body>
</html><?php }
}
?>