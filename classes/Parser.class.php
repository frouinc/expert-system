<?php

require_once("classes/Rule.class.php");

class Parser {

	private $values = [
		"A" => -1,
		"B" => -1,
		"C" => -1,
		"D" => -1,
		"E" => -1,
		"F" => -1,
		"G" => -1,
		"H" => -1,
		"I" => -1,
		"J" => -1,
		"K" => -1,
		"L" => -1,
		"M" => -1,
		"N" => -1,
		"O" => -1,
		"P" => -1,
		"Q" => -1,
		"R" => -1,
		"S" => -1,
		"T" => -1,
		"U" => -1,
		"V" => -1,
		"W" => -1,
		"X" => -1,
		"Y" => -1,
		"Z" => -1
	];
	private $rules = [];
	private $display = [];

	private $valuesParsed = false;
	private $displayParsed = false;

	public function parseFile($filename) {
		$this->valuesParsed = false;
		$this->displayParsed = false;

		$handle = fopen($filename, "r");
		if ($handle) {
			$lineN = 1;

			try {
				while (($line = fgets($handle)) !== false) {
					$this->parseLine($line);
					$lineN++;
				}
			} catch (Exception $e) {
				echo "Line " . $lineN . PHP_EOL;
				throw $e;
			}

			fclose($handle);
		} else {
			throw new Exception("ERROR WHILE OPENING THE FILE");
		}
	}

	public function parseText($text) {
		$this->valuesParsed = false;
		$this->displayParsed = false;

		$lines = preg_split('/\r\n|\r|\n/', $string);
		$lineN = 1;
		try {
			foreach ($lines as $line) {
				parseLine($line);
				$lineN++;
			}
		} catch (Exception $e) {
			echo "Line " . $lineN . PHP_EOL;
			throw $e;
		}
	}

	private function parseLine($line) {
		$line = explode("#", $line)[0];
		$line = preg_replace('/\s+/', '', $line);

		// Check if line is empty
		if (strlen($line) == 0) {
			return;
		}

		// Check what kind of line it is
		if ($line[0] == "=") {
			$this->parseValues($line);
		} else if ($line[0] == "?") {
			$this->parseDisplay($line);
		} else {
			$this->parseRule($line);
		}
	}

	private function parseRule($line) {
		// echo "parsing a line of rule : " . $line . PHP_EOL;
		if (substr_count($line, "=>") == 1) {
			$split = explode("=>", $line);
			$rule = new Rule($split[0], $split[1]);
			$this->rules[] = $rule;

		} else {
			throw new Exception("SYNTAX ERROR IN RULE");
		}
	}

	private function parseValues($line) {
		if ($this->valuesParsed == true) {
			throw new Exception("Values have already been initialized");
		}
		// echo "parsing a line of values : " . $line . PHP_EOL;
		$line = substr($line, 1);
		$arr = str_split($line);

		$i = 0;
		$count = count($arr);
		while ($i < $count) {
			$value = 1;

			if ($arr[$i] == "!") {
				$value = 0;
				$i++;
			}

			if (ctype_upper($arr[$i])) {
				$this->values[$arr[$i]] = $value;
			} else {
				throw new Exception("SYNTAX ERROR IN VALUES");
			}

			$i++;
		}

		$this->valuesParsed = true;
	}

	private function parseDisplay($line) {
		if ($this->displayParsed == true) {
			throw new Exception("Values have already been initialized");
		}
		// echo "parsing a line of display : " . $line . PHP_EOL;
		$line = substr($line, 1);
		$arr = str_split($line);

		$i = 0;
		$count = count($arr);
		while ($i < $count) {
			if (ctype_upper($arr[$i])) {
				$this->display[] = $arr[$i];
			} else {
				throw new Exception("SYNTAX ERROR IN QUERY");
			}
			
			$i++;
		}

		if (count($this->display) == 0) {
			throw new Exception("You need to enter at least one query.");
		}

		$this->valuesParsed = true;
	}

	public function getRules() {
		return ($this->rules);
	}

	public function getQueries() {
		return ($this->display);
	}

	public function getValues() {
		return ($this->values);
	}
}