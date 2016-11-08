<?php
require_once MAIN_DIR."/modules/base.php";
class home extends Base {
	/*
	public function __construct () { 
		Base::__construct ();
	}
	*/
	public function test ( ) {
		echo "Hola";
	}
	public function init (...$page) {
		require_once MAIN_DIR . "/libs/wika.inc.php"; 
		$wk = new Wika ();
		$input = array_key_exists ("input", $_REQUEST) ? $_REQUEST ["input"] : "";
		return array (
			"content" => $wk->parse ($input),
			"input" => $input
		);
	}
}
?>
