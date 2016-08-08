<?php
/* Smarty version 3.1.28-dev/77, created on 2016-07-31 10:18:12
  from "/Sites/explorer.paw.mx/web/views/header.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28-dev/77',
  'unifunc' => 'content_579e16b43598b0_87434543',
  'file_dependency' => 
  array (
    '9b5261e1b652b5186df3457d53e1c5440c57e323' => 
    array (
      0 => '/Sites/explorer.paw.mx/web/views/header.tpl',
      1 => 1469978286,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_579e16b43598b0_87434543 ($_smarty_tpl) {
?>
<div class="col">
	Tables:
	<ul>
	<?php
$_from = $_smarty_tpl->tpl_vars['_tables']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_table_0_saved_item = isset($_smarty_tpl->tpl_vars['table']) ? $_smarty_tpl->tpl_vars['table'] : false;
$_smarty_tpl->tpl_vars['table'] = new Smarty_Variable();
$__foreach_table_0_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_table_0_total) {
foreach ($_from as $_smarty_tpl->tpl_vars['table']->value) {
$__foreach_table_0_saved_local_item = $_smarty_tpl->tpl_vars['table'];
?>
		<li><a href="/table/<?php echo $_smarty_tpl->tpl_vars['table']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['table']->value;?>
</a></li>
	<?php
$_smarty_tpl->tpl_vars['table'] = $__foreach_table_0_saved_local_item;
}
}
if ($__foreach_table_0_saved_item) {
$_smarty_tpl->tpl_vars['table'] = $__foreach_table_0_saved_item;
}
?>
	</ul>
	<a href="/table/import/">Import table</a>
</div>
<?php }
}
