<?php

class Result {
	public $values = [];

	function __construct($str = "") {
		$arr = str_split($str);

		$i = 0;
		$and = false;
		$count = count($arr);

		while ($i < $count) {
			$token = "";

			if ($arr[$i] == "!") {
				if ((count($this->values) > 0 && $and == true) || count($this->values) == 0) {
					$i++;
					$token = "!";
				} else {
					throw new Exception("SYNTAX ERROR: expected \"+\" between operands.");
				}
			}

			// Check if is big letter
			if (ctype_upper($arr[$i])) {

				if ((count($this->values) > 0 && $and == true) || count($this->values) == 0) {
					$token .= $arr[$i];
					$this->values[] = $token;
					$and = false;

				} else {
					throw new Exception("SYNTAX ERROR: expected \"+\" between operands.");
				}

			} else if ($arr[$i] == "+") {
				$and = true;
			} else {
				echo chr($arr[$i]);
				throw new Exception("SYNTAX ERROR: in result of a rule");
			}

			$i++;
		}
	}

	// Work for multiple elements without AND
	public function execute() {
		$hasChanged = false;

		foreach ($this->values as $key) {
			$value = 1;
			if (strpos($key, '!') !== false) {
				$value = 0;
				$key = substr($key, 1);
			}

			if ($GLOBALS[$key] != $value
				&& $GLOBALS[$key] != -1) {
				throw new Exception("ERROR CONFLICT : $key");
			} else if ($GLOBALS[$key] != $value) {
				$hasChanged = true;
				$GLOBALS[$key] = $value;
			}
		}
		return ($hasChanged);
	}
}
