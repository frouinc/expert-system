<?php

require_once("Condition.class.php");
require_once("Result.class.php");

class Rule {
	public $condition = null;
	public $result = null;

	function __construct($condition = "", $result = "") {
		$this->condition = new Condition($condition);
		$this->result = new Result($result);
	}

	public function execute() {
		if ($this->condition->execute()) {
			return ($this->result->execute());
		} else {
			return (false);
		}
	}
}
