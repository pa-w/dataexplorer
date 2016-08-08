<?php
/* Smarty version 3.1.28-dev/77, created on 2016-08-01 12:04:48
  from "/Sites/explorer.paw.mx/web/views/table/import.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28-dev/77',
  'unifunc' => 'content_579f8130574426_79270672',
  'file_dependency' => 
  array (
    '7926bd2e5a905f593ca4ae8f6f7d6d9b590e7110' => 
    array (
      0 => '/Sites/explorer.paw.mx/web/views/table/import.tpl',
      1 => 1470071078,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_579f8130574426_79270672 ($_smarty_tpl) {
?>
<form method="POST" action="/table/import_save/">
<table>
	<thead>
		<tr><th>Import dataset</th></tr>
	</thead>
	<tbody>
		<tr>
			<th>URL:</th>
			<td><input type="text" name="url" size="15"></td>
		</tr>
		<tr>
			<th>Alias: </th>
			<td><input type="text" name="alias" size="10"></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2">
				<input type="submit" value="Import">
			</td>
		</tr>
	</tfoot>
</table>
</form>
<?php }
}
