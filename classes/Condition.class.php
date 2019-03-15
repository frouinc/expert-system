<?php

class Condition {
	public $rpn = [];

	private $priorities = [
		"+" => 1,
		"|" => 2,
		"^" => 0
	];

	function __construct($str = "") {
		$result = [];
		$side = [];

		if (!$this->check($str)) {
			throw new Exception("Syntax error in '$str'");
		}

		$arr = str_split($str);
		$i = 0;
		$size = count($arr);

		while ($i < $size) {
			$token = "";

			// Check for NOT
			if ($arr[$i] == "!") {
				$i++;
				$token = "!";
			}

			// Check if is big letter
			if (ctype_upper($arr[$i])) {
				$token .= $arr[$i];

				// Add token to the result
				$result[] = $token;
			} else if ($arr[$i] == "+" || $arr[$i] == "|" || $arr[$i] == "^" || $arr[$i] == "(" || $arr[$i] == ")") {

				// If parenthesis, force push on the side
				if ($arr[$i] == "(") {
					$side[] = $arr[$i];
				} else if ($arr[$i] == ")") {

					// while it's not the parenthesis, move everything to result
					while (end($side) !== false && end($side) != "(") {
						$result[] = array_pop($side);
					}
					// If side is empty, error
					if (end($side) === false) {
						throw new Exception("SYNTAX ERROR PARENTHESIS");
					} else {
						array_pop($side);
					}
				} else {
					// while (end($side) !== false && $this->priorities[end($side)] > $this->priorities[$arr[$i]]) {
					// 	$result[] = array_pop($side);
					// }
					$side[] = $arr[$i];
				}
			} else {
				throw new Exception("SYNTAX ERROR");
			}

			$i++;
		}
		while (end($side) !== false) {
			$result[] = array_pop($side);
		}

		$this->rpn = $result;
	}

	private function check($str) {
		$array = str_split($str);
		$i = 0;
		$size = count($array);

		$variableCount = 0;
		$signCount = 0;

		foreach ($array as $key => $char) {
			// echo $key . " => " . $char . PHP_EOL;
			if (array_key_exists($char, $this->priorities)) {
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
				if ($key < $size - 1 && array_key_exists($array[$key + 1], $this->priorities)) {
					throw new Exception("Syntax error invalid parenthesis");
				}
			} else if ($char == ")") {
				if ($key > 0 && array_key_exists($array[$key - 1], $this->priorities)) {
					throw new Exception("Syntax error invalid parenthesis");
				}
			}
		}
		return (true);
	}

	public function execute() {
		$stack = [];

		foreach ($this->rpn as $element) {
			if ($element == "+" || $element == "|" || $element == "^") {
				// Pop and calculate
				$val1 = array_pop($stack);
				$val2 = array_pop($stack);

				if ($element == "+") {
					$stack[] = ($val1 && $val2);
				} else if ($element == "|") {
					$stack[] = ($val1 || $val2);
				} else {
					$stack[] = ($val1 xor $val2);
				}
			} else {
				// Push on stack
				$stack[] = isTrue($element);
			}
		}

		return ($stack[0]);
	}
}
