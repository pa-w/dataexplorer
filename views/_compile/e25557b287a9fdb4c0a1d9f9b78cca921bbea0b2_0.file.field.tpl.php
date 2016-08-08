<?php
/* Smarty version 3.1.28-dev/77, created on 2016-07-30 22:54:01
  from "/Sites/explorer.paw.mx/web/views/table/field.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28-dev/77',
  'unifunc' => 'content_579d7659045a98_28433201',
  'file_dependency' => 
  array (
    'e25557b287a9fdb4c0a1d9f9b78cca921bbea0b2' => 
    array (
      0 => '/Sites/explorer.paw.mx/web/views/table/field.tpl',
      1 => 1469937239,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_579d7659045a98_28433201 ($_smarty_tpl) {
?>
<div class="row th">
<table>
	<tbody>
		<tr>
			<th>Data type:</th>

			<td>
				<select name="data_type">
					<?php
$_from = $_smarty_tpl->tpl_vars['data_types']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_data_type_0_saved_item = isset($_smarty_tpl->tpl_vars['data_type']) ? $_smarty_tpl->tpl_vars['data_type'] : false;
$_smarty_tpl->tpl_vars['data_type'] = new Smarty_Variable();
$__foreach_data_type_0_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_data_type_0_total) {
foreach ($_from as $_smarty_tpl->tpl_vars['data_type']->value) {
$__foreach_data_type_0_saved_local_item = $_smarty_tpl->tpl_vars['data_type'];
?>
					<option value="<?php echo $_smarty_tpl->tpl_vars['data_type']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['data_type']->value == $_smarty_tpl->tpl_vars['field_info']->value['data_type']) {?>selected<?php }?>><?php echo $_smarty_tpl->tpl_vars['data_type']->value;?>
</option>
					<?php
$_smarty_tpl->tpl_vars['data_type'] = $__foreach_data_type_0_saved_local_item;
}
}
if ($__foreach_data_type_0_saved_item) {
$_smarty_tpl->tpl_vars['data_type'] = $__foreach_data_type_0_saved_item;
}
?>
				</select>
			</td>
		</tr>
	</tbody>
</table>
</div>
<br>
<div class="row">
<a data-onload data-debug="Here I am" data-download="/table/unique/<?php echo $_smarty_tpl->tpl_vars['table']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['field']->value;?>
.csv" data-download_id="unique" data-download_parse="#update_matrix"></a>
<a id="update_matrix" data-create_element="tr" data-element_container="#matrix" data-catalog="unique" data-item_element="td"></a>
<table>
	<tbody id="matrix">
	</tbody>
</table>
</div>
<?php }
}
