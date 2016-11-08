<?php
class Base {
	var $_connection;
	var $_tables = array ();
	var $antDefinition = "js/explorer.js";
	var $page = array ("title" => "Main", "css" => array ("/css/style.css"), "js" => array ());
	var $DataTypes = array ("integer", "integer:sum", "double precision", "money", "character varying", "text", "timestamp", "timestamp:yyyy", "timestamp:yyyymm", "timestamp:yyyymmdd", "timestamp:yyyymmddHH", "timestamp:yyyymmddHHMM", "timestamp:dow", "timestamp:dowHH");
	var $UnquotedTypes = array ("integer", "double precision");
	var $Functions = array ("yyyy" => "to_char(:val:, 'YYYY')", "yyyymm" => "to_char(:val:, 'YYYYMM')", "yyyymmdd" => "to_char(:val:, 'YYYYMMDD')", "sum" => "sum(:val:)");
	var $AggregateFunctions = array ("sum" => "sum(:val:)", "count" => "count(:val:)", "min" => "min(:val:)", "max" => "max(:val:)", "stddev" => "stddev(:val:)", "avg" => "avg(:val:)", "stddev_pop" => "stddev_pop(:val:)", "variance" => "var_samp(:val:)", "var_pop" => "var_pop(:val:)");
	var $Operators = array ("=" => "is", "<>" => "is not", "<" => "less than", "<=" => "less than or equal to", ">" => "more than", ">=" => "more than or equal to");

	public function __construct () {
		$this->_connection = pg_connect ("dbname=sandbox user=paw");
	}
	function _listTables () { 
		$r = pg_select ($this->_connection, "information_schema.tables", array ("table_schema" => "public"));	
		foreach ($r as $t) {
			$this->_tables [] = $t ["table_name"];
		}
	}
}
?>
