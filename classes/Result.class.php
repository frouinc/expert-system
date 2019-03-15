<?php

class Result {
	public $values = [];

	function __construct($str = "") {
		$arr = str_split($str);

		if (!$this->check($str)) {
			throw new Exception("Syntax error in '$str'");
		}

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

	private function check($str) {
		$array = str_split($str);
		$i = 0;
		$size = count($array);

		$variableCount = 0;
		$signCount = 0;

		foreach ($array as $key => $char) {
			// echo $key . " => " . $char . PHP_EOL;
			if ($char == "+") {
				if ($key + 1 >= $size) {
					throw new Exception("Syntax error sign at the end of expression");
				}
				if ($signCount != $variableCount - 1) {
					throw new Exception("Syntax error too many signs");
				}
				$signCount++;
			} else if (ctype_upper($char)) {
				if ($variableCount > $signCount) {
					throw new Exception("Syntax error too many variables");
				}
				$variableCount++;
			} else if ($char == "(") {
				if ($key < $size - 1 && $array[$key + 1] == "+") {
					throw new Exception("Syntax error invalid parenthesis");
				}
			} else if ($char == ")") {
				if ($key > 0 && $array[$key - 1] == "+") {
					throw new Exception("Syntax error invalid parenthesis");
				}
			}
		}
		return (true);
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
