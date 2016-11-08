<?php
require_once MAIN_DIR."/modules/base.php";
class table extends Base
{
	var $table;
	var $field;
	/**
	* @param_desc table Table Name 
	*/
	private function _getColumns ($table) { 
		$r = pg_select ($this->_connection, "information_schema.columns", array ("table_name" => $table));
		$columns = array ();	
		foreach ($r as $col) {
			$columns [] = $col;
		}

		return $r;
	}
	function init ($table) {
		$this->_listTables ();

		$this->antDefinition = "js/matrix.js";
		$this->table = $table;

		return array ("columns" => $this->_getColumns ($table));
	}
	function columns ($table) {
		return $this->_getColumns ($table);
	}
	function field ($table, $field) { 
		$this->table = $table;	
		$this->field = $field;
		$r = pg_select ($this->_connection, "information_schema.columns", array ("table_name" => $table, "column_name" => $field));
		$info = $r [0];	
		return array ("field_info" => $info);

	}
	private function _parseFilter ($tree) {
		$fn = function ($t)  use (&$fn) { 
			if (!is_array ($t) || count ($t) == 0) return;
			$sql = " (";
			foreach ($t as $k => $i) {
				foreach ($i ["input"] as $key => $v) {
					$cs = explode (":", $key);
					$i ["input"][$key] = pg_escape_string ($v);
					if (count ($cs) > 1) $i ["input"] [$cs [1]] = pg_escape_string ($v);

				}
				if (array_key_exists ("items", $i) && !empty ($i ["items"])) {
					$op = isset ($i ["input"]["group_op"]) ? $i ["input"] ["group_op"] : "";
					$sql .=  " ". $op . " " . $fn ($i ["items"]);
				} else {
					
					list ($tbl, $col) = explode (".", $i ["input"] ["rule_column"]);
					@list ($dt, $dfn) = explode (":", $i ["input"]["rule_data_type"]);

					$col = $tbl.'."'.$col.'"::' . $dt;
					if (!empty ($dfn) && array_key_exists ($dfn, $this->Functions)) {
						$col = str_replace (":val:", $col, $this->Functions [$dfn]);
					}
					if (array_key_exists ("operator", $i ["input"]) && $k > 0) {
						$sql .= " ". $i ["input"]["operator"]." ";
					}
					$val = in_array ($dt, $this->UnquotedTypes) ? $i ["input"]["rule_value"] : "'" . $i ["input"] ["rule_value"] . "'";
					$sql .= $col." ". $i ["input"] ["rule_operator"]. " " . $val;
				}
			}
			$sql .= " )";

			return $sql;
		};

		return $fn ($tree);
	}
	/**
	* @allow_method POST
	*/
	function analyze ($table, ...$joins) {
		if (empty ($_REQUEST)) {	
			$input = array ();
			parse_str (urldecode (file_get_contents ("php://input")), $input);
		} else {
			$input = $_REQUEST;
		}
		$columns = array ();
		$distinct = false;
		if (array_key_exists ("groups", $input)) {
			foreach ($input ["groups"] as $i => $g) {
				$columns [] = array (
						"group" => $g, 
						"aggregate" => $input ["aggregate"][$i], 
						"input_funct" => $input ["input_funct"][$i],
						"data_type" => $input ["data_type"][$i],
						"distinct" => isset ($input ["distinct"][$i]) ? $input ["distinct"][$i] : "",
						"filter" => isset ($input ["filter_col"][$i]) ? $input ["filter_col"][$i] : ""
						);
				if (isset ($input ["distinct"][$i]) && !empty ($input ["aggregate"][$i])) {
					$distinct = true;
				}
			}
			$columns [] = array ("group" => "*", "aggregate" => "count");
		}
		$i = 0;
		$selGrps = array_map (function ($a) use (&$i) {
			@list ($tbl, $column) = explode (".", $a ["group"]);
			@list ($dType,$cmd) = explode (":", $a ["data_type"]);

			$colName = $column;

			if (array_key_exists ($a ["aggregate"], $this->AggregateFunctions)) {
				if (!empty ($column)) {
					$colName = $a["aggregate"] . "_" . $column."_". $i;
				} else {
					$colName = $a ["aggregate"];
				}
			}

			$i++;
			return array ("column_name" => $colName, "aggregate" => $a ["aggregate"]);  
		}, $columns);


		return $selGrps;
	}
	/**
	* @allow_method POST
	*/
	function sets ($table, ...$joins) {
		if (empty ($_REQUEST)) {	
			$input = array ();
			parse_str (urldecode (file_get_contents ("php://input")), $input);
		} else {
			$input = $_REQUEST;
		}
		$columns = array ();
		$distinct = false;
		if (array_key_exists ("groups", $input)) {
			foreach ($input ["groups"] as $i => $g) {
				$columns [] = array (
						"group" => $g, 
						"aggregate" => $input ["aggregate"][$i], 
						"input_funct" => $input ["input_funct"][$i],
						"data_type" => $input ["data_type"][$i],
						"distinct" => isset ($input ["distinct"][$i]) ? $input ["distinct"][$i] : "",
						"filter" => isset ($input ["filter_col"][$i]) ? $input ["filter_col"][$i] : ""
						);
				if (isset ($input ["distinct"][$i]) && !empty ($input ["aggregate"][$i])) {
					$distinct = true;
				}
			}
		}
		$universe = array ();
		$q = pg_query ("SELECT distinct ogc_fid FROM \"".pg_escape_string ($table)."\"");
		while ($f = pg_fetch_assoc ($q)) {
			$universe [] = $f ["ogc_fid"];
		}
		$where = $this->_parseFilter (json_decode ($input ["filter"], true));
		$groups = array_map (function ($a) use ($where, $table, $universe) { 
			list ($tbl, $column) = explode (".", $a ["group"]);
			@list ($dType,$cmd) = explode (":", $a ["data_type"]);

			$col = $tbl .".\"".$column."\"::".$dType;
			if (!empty ($cmd) && array_key_exists ($cmd, $this->Functions)) {
				$col = str_replace (":val:", $col, $this->Functions [$cmd]);
			}
			if (!empty ($a ["input_funct"])) {
				//TODO Fix security flaw. (analyze function arguments? sanitize them?)
				$col = str_replace (":val:", $col, $a ["input_funct"]);
			}

			if (array_key_exists ($a ["aggregate"], $this->AggregateFunctions)) {
				$col = str_replace (":val:", ($a ["distinct"] == "Y" ? "distinct " : "") . $col, $this->AggregateFunctions [$a ["aggregate"]]);
				$sql = "SELECT distinct ogc_fid FROM \"".pg_escape_String ($table) ."\""; 
				if (!empty ($where)) {
					$sql .= " WHERE (" . $where .")";
				}
				$items = array ();
				$isUniverse = true;
				$filter = "";
				if (!empty ($a [ "filter"])) {
					$fWhere = $this->_parseFilter (json_decode ($a ["filter"], true));
					$filter = $fWhere;
					if (!empty ($where) && !empty ($fWhere)) {
						$sql .= " AND (". $fWhere .")";
						$isUniverse = false;
					} elseif (!empty ($fWhere)) {
						$sql .= " WHERE " .$fWhere;
						$isUniverse = false;
					}
					if (!$isUniverse) { 
						$sql .= " ORDER BY ogc_fid";
						$q = pg_query ($sql);
						while ($f = pg_fetch_assoc ($q)) {
							$items [] = $f ["ogc_fid"];
						}
					}
				}

				return array (
					"aggregate" => $col,
					"universe" => $isUniverse,
					"filter" => $fWhere,
					"children" => array (),
					"items" => ($isUniverse) ? $universe : $items
				);
			}
		}, $columns);
		$grps = array ();
		foreach ($groups as $g) {
			if (!array_key_exists ($g ["aggregate"], $grps)) {
				$grps [$g ["aggregate"]] = array();
			}

			$grps [$g ["aggregate"]][] = $g; 
		}
		
		foreach ($grps as $agg => $cols) {
			foreach ($cols as $colA) {
				foreach ($cols as $colB) { 
					if (is_array ($colA) && is_array ($colB) 
						&& array_key_exists ("items",  $colA) && array_key_exists ("items", $colB)) {
						if ($colA ["items"] != $colB ["items"]) {
							$int = array_intersect ($colA ["items"], $colB ["items"]);
							echo "Intersection: " . count ($int). "<br>";
							if (!empty ($int)) {
								if ($int == $colB ["items"]) {
									$colA ["children"] [] = $colB;
								}
								if ($int == $colA ["items"]) {
									echo "Col A is a subset of Col B<br>";
									echo "Col B Universe: " .$colB ["universe"]."<br>";
									$colB ["children"] [] = $colA;
									unset ($colA [ "items" ]);
								}
							}
						}
					}
				}
			}

			//echo "<pre>".print_r ($cols, true)."</pre>";
		}
		echo "<pre>".print_r ($grps, true)."</pre>";
		
	}
	/**
	* @allow_method POST
	*/
	function matrix ($table, ...$joins) {
		if (empty ($joins)) $joins = array ();
		$tables = array ($table) + $joins;
		$input = array ();
		parse_str (urldecode (file_get_contents ("php://input")), $input);
		$sql = "SELECT ";
		$parts = array ();
		$columns = array ();
		$distinct = false;
		if (array_key_exists ("groups", $input)) {
			foreach ($input ["groups"] as $i => $g) {
				$columns [] = array (
						"group" => $g, 
						"aggregate" => $input ["aggregate"][$i], 
						"input_funct" => $input ["input_funct"][$i],
						"data_type" => $input ["data_type"][$i],
						"distinct" => isset ($input ["distinct"][$i]) ? $input ["distinct"][$i] : "",
						"filter" => isset ($input ["filter_col"][$i]) ? $input ["filter_col"][$i] : ""
						);
				if (isset ($input ["distinct"][$i]) && !empty ($input ["aggregate"][$i])) {
					$distinct = true;
				}
			}
		}
		if ($distinct) { 
			$sql .= " distinct ";
		}
		$i = 0;
		$selGrps = array_map (function ($a) use (&$i) {
			list ($tbl, $column) = explode (".", $a ["group"]);
			@list ($dType,$cmd) = explode (":", $a ["data_type"]);

			$col = $tbl .".\"".$column."\"::".$dType;
			if (!empty ($cmd) && array_key_exists ($cmd, $this->Functions)) {
				$col = str_replace (":val:", $col, $this->Functions [$cmd]);
			}
			if (!empty ($a ["input_funct"])) {
				//TODO Fix security flaw. (analyze function arguments? sanitize them?)
				$col = str_replace (":val:", $col, $a ["input_funct"]);
			}

			if (array_key_exists ($a ["aggregate"], $this->AggregateFunctions)) {
				$col = str_replace (":val:", ($a ["distinct"] == "Y" ? "distinct " : "") . $col, $this->AggregateFunctions [$a ["aggregate"]]);

				if (!empty ($a [ "filter"])) {
					$fWhere = $this->_parseFilter (json_decode ($a ["filter"], true));
					if (!empty ($fWhere)) {
						$col .= " filter (WHERE " . $fWhere . ")";
					}
				}

				$col .= " as \"" . $a["aggregate"] . "_" . $column."_". $i .'"';
			}
			$i++;
			return $col;
		}, $columns);
		$parts += $selGrps;
		$parts [] = "count (*)";

		$grpParts = array_map (function ($a) {
			if (!array_key_exists ($a ["aggregate"], $this->AggregateFunctions)) {
				list ($tbl, $column) = explode (".", $a ["group"]);
				@list ($dType,$cmd) = explode (":", $a ["data_type"]);

				$col = $tbl .".\"".$column."\"::".$dType;
				if (!empty ($cmd) && array_key_exists ($cmd, $this->Functions)) {
					$col = str_replace (":val:", $col, $this->Functions [$cmd]);
				}
				if (!empty ($a ["input_funct"])) {
					//TODO Fix security flaw. (analyze function arguments? sanitize them?)
					$col = str_replace (":val:", $col, $a ["input_funct"]);
				}

				return $col;
			}
		}, $columns);

		$groupBy = implode (array_filter ($grpParts), ", ");


		$sql .= implode ($parts, ", "). " FROM " .implode ($tables, ", "); 

		$where = $this->_parseFilter (json_decode ($input ["filter"], true));
		if (!empty ($where)) {
			$sql .= " WHERE " . $where;
		}

		if (!empty ($groupBy)) {
			$sql .= " GROUP BY " . $groupBy;
			$sql .= " ORDER BY " . $groupBy;
		}

		$ret = array ();
		$r = pg_query ($sql);
		if (!$r) echo $sql;
		while ($f = pg_fetch_assoc ($r)) {
			$ret [] = $f;
		}
		return $ret;
	}
	function unique () {
		$s = array ();
		foreach ($_REQUEST as $k => $v) {
			$s [$k] = pg_escape_string ($v);
		}
		$ret = array ();
		list ($tbl, $cl) = explode (".", $s ["rule_column"]);
		if (!empty ($tbl) && !empty ($cl) && in_array ($s ["rule_data_type"], $this->DataTypes)) {
			@list ($dt, $cmd) = explode (":", $s ["rule_data_type"]);
			$col = '"'.$tbl.'"."'.$cl.'"::'.$dt;
			if (!empty ($cmd) && array_key_exists ($cmd, $this->Functions)) {
				$col = str_replace (":val:", $col, $this->Functions [$cmd]);
			}
			if (!empty ($s ["rule_input"])) {
				$col = str_replace (":val:", $col, $s["rule_input"]);
			}
			$q = pg_query ("SELECT distinct $col as unique FROM \"$tbl\" ORDER BY \"unique\"");
			if (!$q) return;

			while ($f = pg_fetch_assoc ($q)) {
				$ret [] = $f;	
			}

		}
		return $ret;
	}
	function import () {
	}
	var $message;
	/**
	* @allow_method POST
	* @request_required_param url
	* @request_required_param alias
	*/
	function import_save () { 
		if (empty ($_REQUEST ["url"])) { $this->message = "URL should not be empty"; return; }
		if (empty ($_REQUEST ["alias"])) { $this->message = "Alias should not be empty"; return; }

		$pUrl = parse_url ($_REQUEST ["url"]);
		$pInfo = pathinfo ($pUrl ["path"]); 
		$contentType = "csv";
		if (!empty ($pInfo ["extension"])) {
			$contentType = "csv";
		}
		$baseName = $pInfo ["basename"];
		$headers = get_headers ($_REQUEST ["url"]);
		foreach ($headers as $header) {
			@list ($h, $v) = explode (":", $header);
			if (trim ($h) != "Content-Type") continue;
			list ($t, $e) = explode ("/", $v);
			if (!empty ($e)) { @list ($contentType) = explode (";", $e); }
		}
		$allowedTypes = array ("csv", "json", "html", "octet-stream", "shp");
		$output = null;
		if (in_array ($contentType, $allowedTypes)) {
			if ($contentType == "octet-stream") $contentType = "csv";
			$fName = "/tmp/".$_REQUEST ["alias"]. ".".$contentType;
			$f = fopen ($fName, "w");
			if (!$f)  { $this->message = "Could not open file for writing: $fName"; return;  }
			$data = file_get_contents ($_REQUEST ["url"]);
			fwrite ($f, $data);
			fclose ($f);
			$rName = $_REQUEST ["alias"];
			$dSQL = "/tmp/{$_REQUEST ["alias"]}.sql";
			$p = popen ('/usr/local/bin/ogr2ogr -f "PGDump" -nln "'.$rName.'" "'.$dSQL.'" "'.$fName.'" 2>&1', 'r');
			$output .= stream_get_contents ($p);
			fclose ($p);
			if (file_exists ($dSQL)) {
				$p = popen ('/usr/local/bin/psql -U paw -d sandbox -f '.$dSQL.' 2>&1', 'r');
				$output .= stream_get_contents ($p);
				fclose ($p);

				$this->message = nl2br ($output);
				return; 
			}
		} else {
			$this->message = "Content type ($contentType) not supported";
			return;
		}
	}
	function info ($type) { 
		$types = array ("data_types" => $this->DataTypes, "aggregate_functions" => $this->AggregateFunctions, "operators" => $this->Operators, "functions" => $this->Functions);
		$ret = array ();
		if (array_key_exists ($type, $types)) {
			foreach ($types [$type] as $k => $v) {
				$ret [] = array ("key" => $k, "value" => $v);
			}
		}
		return $ret;
	}
}
?>
