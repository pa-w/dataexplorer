<?php
/* Smarty version 3.1.28-dev/77, created on 2016-08-04 12:24:03
  from "/Sites/explorer.paw.mx/web/views/main.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28-dev/77',
  'unifunc' => 'content_57a37a33ab87b3_88605933',
  'file_dependency' => 
  array (
    '0c635c90d067ff65f455526bcae66626fa54f4e4' => 
    array (
      0 => '/Sites/explorer.paw.mx/web/views/main.tpl',
      1 => 1470331441,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.tpl' => 1,
  ),
),false)) {
function content_57a37a33ab87b3_88605933 ($_smarty_tpl) {
if (!is_callable('smarty_function_fetch')) require_once '/Sites/explorer.paw.mx/web/libs/smarty/libs/plugins/function.fetch.php';
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<meta charset="UTF-8">
	<title><?php echo $_smarty_tpl->tpl_vars['page']->value['title'];?>
 | Data explorer</title>
                <meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
                <?php echo '<script'; ?>
 src="/js/libs/jquery.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/libs/d3.v4.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/libs/d3-geo.v1.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/libs/d3-geo-projection.v1.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/libs/d3-selection-multi.v0.4.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/libs/d3-axis.v1.min.js"><?php echo '</script'; ?>
>
                <?php echo '<script'; ?>
 src="/js/libs/topojson.js"><?php echo '</script'; ?>
>
                <?php echo '<script'; ?>
 src="/js/libs/queue.min.js"><?php echo '</script'; ?>
>
		<?php echo '<script'; ?>
 src="/js/ant.js"><?php echo '</script'; ?>
>
		<?php
$_from = $_smarty_tpl->tpl_vars['page']->value['js'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_js_0_saved_item = isset($_smarty_tpl->tpl_vars['js']) ? $_smarty_tpl->tpl_vars['js'] : false;
$_smarty_tpl->tpl_vars['js'] = new Smarty_Variable();
$__foreach_js_0_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_js_0_total) {
foreach ($_from as $_smarty_tpl->tpl_vars['js']->value) {
$__foreach_js_0_saved_local_item = $_smarty_tpl->tpl_vars['js'];
?>
		<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
"><?php echo '</script'; ?>
>
		<?php
$_smarty_tpl->tpl_vars['js'] = $__foreach_js_0_saved_local_item;
}
}
if ($__foreach_js_0_saved_item) {
$_smarty_tpl->tpl_vars['js'] = $__foreach_js_0_saved_item;
}
?>
		<?php
$_from = $_smarty_tpl->tpl_vars['page']->value['css'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_css_1_saved_item = isset($_smarty_tpl->tpl_vars['css']) ? $_smarty_tpl->tpl_vars['css'] : false;
$_smarty_tpl->tpl_vars['css'] = new Smarty_Variable();
$__foreach_css_1_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_css_1_total) {
foreach ($_from as $_smarty_tpl->tpl_vars['css']->value) {
$__foreach_css_1_saved_local_item = $_smarty_tpl->tpl_vars['css'];
?> 
                <link rel="stylesheet" href="<?php echo $_smarty_tpl->tpl_vars['css']->value;?>
"/>
		<?php
$_smarty_tpl->tpl_vars['css'] = $__foreach_css_1_saved_local_item;
}
}
if ($__foreach_css_1_saved_item) {
$_smarty_tpl->tpl_vars['css'] = $__foreach_css_1_saved_item;
}
?>
	</head>
	<body>
	<div>
		<div class="header row">
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		</div>
		<div class="content row">
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		</div>
	</div>
	<?php echo '<script'; ?>
>
	$(document).ready (function () {
		<?php echo smarty_function_fetch(array('file'=>$_smarty_tpl->tpl_vars['antDefinition']->value),$_smarty_tpl);?>

		new Ant (conf);
	});
	<?php echo '</script'; ?>
>
	</body>
</html>
<?php }
}
