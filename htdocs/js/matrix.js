var conf = {
	prequantifiers: {
		treemap: function () {
			console.log (arguments);
			//return new Nestfiy (this.data.matrix 
		},
		matrix: function () {
			var counts = [], lines = [], nests = [];
			for (var c in this.data.matrix.columns) {
				var col = this.data.matrix.columns [c];
				if (col.indexOf ("count") !== -1 || col.indexOf ("sum") !== -1) {
					counts.push (col);
				} else {
					nests.push (col);
				}
			}
			for (var cnt in counts) {
				lines.push ({"values": this.data.matrix, "count": counts [cnt], "nests": nests, attrs: {"stepped": true, "class": counts [cnt] } });
			}

			return {data: lines, scale: d3.scaleLinear ().domain (d3.extent (this.data.matrix, function (a) { return parseInt (a.count); }).reverse ()) };

		}
	},
	quantifiers: {
		treemap: {
			treemap: function () {
				console.log (arguments);
			}
		},
		lines: {
			matrix: function (e, x, d, line) {
				var count = line.count;
				var nests = line.nests;
				return {"y": d.scale (parseInt (e [count])), "class": count };
			}
		}
	},
	callbacks: {
		create_hierarchy: function () {
			console.log ("will create hierarchy");
			//this.data.hierarchy = new Nestify (this.data.matrix);
		},
		display_subfunctions (i) {
			console.log ("display _ subfunctions" + i);
		},
		/*
		add_filter: function () {
			var valSelect = document.getElementById ("filter_value"), val = valSelect.options[valSelect.selectedIndex].value
				opSelect = document.getElementById ("filter_operator"), op = opSelect.options[opSelect.selectedIndex].value,
				dtSelect = document.getElementById ("filter_data_type"), dt = dtSelect.options[dtSelect.selectedIndex].value,
				func = document.getElementById ("filter_function").value, colSelect = document.getElementById ("filter_col"),
				col = colSelect.options [colSelect.selectedIndex].value;
				if (col && dt && op && val) {
					var filtersCount =d3.select ("#filters").node ().children.length;

					var tr = d3.select ("#filters").append ("tr");
					var colDt = col + "::" + dt
					var colDesc = func != "" ? func.replace (":val:", colDt) : colDt;
					tr.append ("td")
						.append ("input")
						.attrs ({
							"id": "filter_" + filtersCount ,
							"type": "hidden", 
							"value": colDesc + "|"+op+"|"+val
						})
					var sx = tr.append ("td")
						.append ("select")
						.attrs ({
							"id": "operator_" + filtersCount
						});
					sx.append ("option").attr("value", "AND").html ("AND");
					sx.append ("option").attr("value", "OR").html ("OR");
					tr.append ("td").html (colDesc);
					tr.append ("td").html (op);
					tr.append ("td").html (val);
				}
		},
		*/
		set_data_type: function (arg) {
			var elm = $(arg.element)[0];
			if (elm) {
				for (var opt in elm.options){
					if (elm.options [opt].value == arg.data_type) {
						elm.options [opt].selected = true;
					} else {
						elm.options [opt].selected = false;
					}
				}
			}
		},
		update_groups: function (arg) {
			var grp = arg ["column"], colInfo = grp.split ("."), tbl = colInfo [0], colName = colInfo [1], colDataType = colInfo [2], id = arg ["id"], sel = d3.select ("#data_type_"+id).node (), 
				opts = d3.selectAll ("#groups input"), 
				optsSize = opts.size (),
				col = d3.select("#grp_" + optsSize);

			if (col.size () == 0) {
				var tr = d3.select("#groups").append ("tr").attr ("id", "grp_" + optsSize);
				var input = tr.append ("td")
						.attr ("rowspan", "2")
						.append ("input")
						.attrs ({
							"type": "checkbox",
							"name": "groups[" + optsSize + "]",
							"checked": true,
							"value": tbl+"."+colName,
						});
				var agg = tr.append ("td")
						.append ("select")
						.attrs ({
							"name": "aggregate[" + optsSize + "]"
						});
				agg.append ("option").html ("- none -");
				for (var x in this.data.aggregate_functions) {
					agg.append ("option")
						.attr ("value", this.data.aggregate_functions [x].key)
						.html (this.data.aggregate_functions [x].key);
				}
				var distinct = tr.append ("td");
				
				distinct.append ("input")
					.attrs ({
						"type": "checkbox",
						"name": "distinct[" + optsSize + "]",
						"value": "Y"
					});

				var name = tr.append ("td").html (colName);
				var dType = tr.append ("td")
						.append ("select")
						.attrs ({
							"name": "data_type[" + optsSize + "]"
						});
				for (var x in this.data.data_types) {
					var opt = dType.append ("option")
							.attr ("value", this.data.data_types [x].value)
							.html (this.data.data_types [x].value);
					if (this.data.data_types [x].value == colDataType) {
						opt.attr ("selected", true);
					}
				}
				var fTr = d3.select("#groups").append ("tr").attr ("id", "filter_cont_" + optsSize),
					fTbl = fTr.append ("td").attr ("colspan", 4).append ("table"), 
					tClone = document.getElementById ("filter_control").cloneNode (true);

				fTbl.node ().appendChild (tClone);
				fTbl.append ("input").attr ("type", "hidden").attr ("name", "filter_col[" + optsSize +"]").attr ("id", "filter_" + optsSize)
				fTbl.append ("a").attr ("data-callback", "update_tree").attr ("data-callback_args", optsSize).attr ("data-debug", optsSize + "is being parsed").classed ("update_tree", true);
				var fTbody = fTbl.append ("tbody").attr ("id", "filter_main_" + optsSize)
				var addT = fTbl.select (".rule")
					.attr ("data-callback_args", JSON.stringify ({"type": "rule", "cont": "#" + fTbody.attr ("id")}));
				var grpT = fTbl.select (".group")
					.attr ("data-callback_args", JSON.stringify ({"type": "group", "cont": "#" + fTbody.attr ("id")}));
				this.initControl (addT.node ());
				this.initControl (grpT.node ());
				

				
			} else {
				col.remove ();
				var x = d3.selectAll ("#groups input").each (function (e, i, elms) {
					var elements = d3.select (elms [i]).attr ("name", "groups[" + i + "]");	
				});
			}
		},
		update_tree: function (optsSize) {
			var fMain = "filter_main" + (optsSize !== undefined ? "_" + optsSize : "");
			var x = document.getElementById (fMain);
			console.log (optsSize + " : " + x);

			console.log (fMain);
			var getInputs = function (node) {
				var xd = node.querySelectorAll ("input,select"), r = {};
				for (var e in xd) {
					if (xd [e].value !== undefined) {
						var n = xd [e].name != "" ? xd [e].name : xd [e].id;
						if (n != undefined) { 
							if ((xd [e].type != "radio" && xd [e].type != "checkbox") || xd [e].checked) {
								r [n] = xd [e].value;	
							} 
						}
					}
				}
				return r;
			}
			var y = function (node, tree, f, cb) { 
				if (f (node)) {
					tree.push ({node: node, items: [], input: getInputs (node)});
					tree = tree [tree.length - 1].items;
				}
				node = node.firstChild;
				while (node) {
					y (node, tree, f);
					node = node.nextSibling;
				}
				if (cb !== undefined) {
					cb (tree);
				}
			};
			var t = {};
			t.items = [];
			y (x, t.items, function (node) { 
				if (
					node.nodeName == "TR" && node.parentNode.nodeName == "TBODY"
					&& (node.className.indexOf ("rule") !== -1 || node.className.indexOf ("group") !== -1) 
				) { 
					return true;
				}
				return false;
			}, function (t) { 
				console.log (t);
				console.log (JSON.stringify (t));
				document.getElementById ("filter" + (optsSize !== undefined ? "_" + optsSize : "")).value = JSON.stringify (t); 
			});

		},
		add_rule: function (arg) {
			if (arg.cont) {
				var cont = d3.select (arg.cont), items = cont.node ().childNodes.length, tr = cont.append ("tr");
				if (arg.idx == undefined) arg.idx = cont.attr ("id");

				arg.idx = arg.idx + "_" + items;
				tr.attr ("id", arg.idx).classed (arg.type, true);
				
				if (arg.type == "group") {
					var tbl = tr.append ("td").attr ("colspan", "5").append ("table").classed ("group", true), 
						tClone = document.getElementById ("filter_control").cloneNode (true),
						tId = arg.idx + "_" + items;

					tbl.node ().appendChild (tClone);

					
					var idx = arg.idx + "_" + items; //this idx is the one that gets transmited to the groups 

					tbl.select (".rule")
						.attr ("data-callback_args", JSON.stringify ({"type": "rule", "cont": "#" + tId, "idx": idx }));
					tbl.select (".group")
						.attr ("data-callback_args", JSON.stringify ({"type": "group", "cont": "#" + tId, "idx": idx }));



					var tbody = tbl.append ("tbody")
							.attr ("id", tId)
					/* redefine tr to the new tbody so the filters are filled there */
					tr = tbody.append ("tr")
						.classed ("rule", true)
						.attr ("id", idx);
					d3.select (tClone).selectAll ("input[type='radio']").attr ("name", tr.attr ("id") + ":group_op") 

					items = 0;


					this.initControl (tbl.select (".rule").node ());
					this.initControl (tbl.select (".group").node ());
				}

				var opD = tr.append ("td")
				opD.append ("input").attrs ({"type": "radio", "name": tr.attr ("id") + ":operator", "value": "AND", "checked": true})
				opD.append ("span").html ("AND");
				opD.append ("input").attrs ({"type": "radio", "name": tr.attr ("id") + ":operator", "value": "OR"})
				opD.append ("span").html ("OR");


				var dtx = tr.append ("td"), fieldCont = dtx.append ("table").append ("tbody").attr ("id", tr.attr ("id")+"_rules");
				var ruleCont = fieldCont.append ("tr")

				var sel = ruleCont.append ("td")
						.append ("select")
						.attr ("name", "rule_column")
						.attr ("data-control", true)
						.classed ("input", true)
				this.initControl (sel.node ());

				dtx.append ("a")
					.attrs ({
						"id": tr.attr ("id") + "_refresh",
						"data-catalog": tr.attr ("id"),
						"data-create_element": "option",
						"data-element_container": "#" + tr.attr ("id") + "_value",
						"data-element_attrs": JSON.stringify ({"value": "unique"}),
						"data-element_html": "unique"
					});
				
				dtx.append ("a")
					.attrs ( {
						"id": tr.attr ("id") + "_download",
						"data-debug": "Downloaded",
						"data-control_element": "#"+tr.attr ("id") + "_value",
						"data-element_remove_children": true,
						"data-parse": "#"+tr.attr ("id") + "_refresh"
					});

				dtx.append ("a")
					.attr ("id", "update_unique_" + tr.attr ("id") )
					.attr ("data-download", "/table/unique.csv")
					.attr ("data-download_id", tr.attr ("id"))
					.attr ("data-download_parse", "#" + tr.attr ("id") + "_download")
					.attr ("data-query_string_elements", "#" + tr.attr ("id") + "_rules .input")

				sel.append ("option").attr ("value", "").html ("- pick a column -");
				for (var c in this.data.columns) { 
					sel.append ("option")
						.attr ("value", this.data.columns [c].table_name+"."+this.data.columns [c].column_name)
						.attr ("data-callback", "set_data_type")
						.attr ("data-parse", "#update_unique_" + tr.attr ("id"))
						.attr ("data-callback_args", JSON.stringify ({
							"element": "#" + tr.attr ("id") + " .data_type",
							"data_type": this.data.columns [c].data_type
						}))
						.html (this.data.columns [c].column_name)
				}

				var dType = ruleCont.append ("td").append ("select")
						.attr ("data-control", true)
						.attr ("name", "rule_data_type")

				this.initControl (dType.node ());

				dType.classed ("data_type", true);
				dType.classed ("input", true);
				dType.append ("option").attr ("value", "").html ("- data type -");
				for (var d in this.data.data_types) {
					dType.append ("option")
						.attr ("value", this.data.data_types [d].value)
						.attr ("data-parse", "#update_unique_" + tr.attr ("id"))
						.html (this.data.data_types [d].value);
				}
				fieldCont.append ("tr").append ("td").attr ("colspan", "2").append ("input")
						.attr ("size", "50")
						.attr ("name", "rule_input")
						.classed ("input", true);

				var opSel = tr.append ("td").append ("select").attr ("name", "rule_operator");
				opSel.append ("option").attr ("value", "").html ("- operator -");
				for (var o in this.data.operators) {
					opSel.append ("option").attr ("value", this.data.operators [o].key).html (this.data.operators [o].value);
				}
				var valSel = tr.append ("td").append ("select").attr ("id", tr.attr("id") + "_value").attr ("name", "rule_value");
				valSel.append ("option").attr ("value", "").html ("- value -");

			}
		}
	}
};
