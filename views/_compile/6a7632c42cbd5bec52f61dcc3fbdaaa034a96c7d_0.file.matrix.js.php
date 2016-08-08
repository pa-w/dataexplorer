<?php
/* Smarty version 3.1.28-dev/77, created on 2016-08-01 10:22:49
  from "/Sites/explorer.paw.mx/web/htdocs/js/matrix.js" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28-dev/77',
  'unifunc' => 'content_579f69496aa7d7_80887991',
  'file_dependency' => 
  array (
    '6a7632c42cbd5bec52f61dcc3fbdaaa034a96c7d' => 
    array (
      0 => '/Sites/explorer.paw.mx/web/htdocs/js/matrix.js',
      1 => 1470064967,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_579f69496aa7d7_80887991 ($_smarty_tpl) {
?>
var conf = {
	prequantifiers: {
		matrix: function () {
			console.log (this.data.matrix.columns);
		}
	},
	quantifiers: {
		lines: {
		}
	},
	callbacks: {
		display_subfunctions (i) {
			console.log ("display _ subfunctions" + i);
		},
		update_groups: function (arg) {
			console.log (arg);
			var grp = arg ["column"], id = arg ["id"], sel = d3.select ("#data_type_"+id).node (), col = d3.select("#grp_"+grp.replace (".", "_")), opts = d3.selectAll ("#groups input"), optsSize = opts.size ();
			if (col.size () == 0) {
				var dType = sel.options [sel.selectedIndex].value;
				var li = d3.select("#groups").append ("li").attr ("id", "grp_"+grp.replace(".","_"));
				li.html (grp.split (".")[1] + ", " + dType);
				var input = li.append ("input")
					.attrs({
						"type": "checkbox",
						"name": "groups[" + optsSize + "]",
						"checked": true,
						"value": grp+"|"+dType,
						"data-change": "#update_groups"
					})
				this.initControl (input);
			} else {
				col.remove ();

				var x = d3.selectAll ("#groups input").each (function (e, i, elms) {
					var elements = d3.select (elms [i]).attr ("name", "groups[" + i + "]");	
				
				});


			}
		}
	}
};
<?php }
}
