<?php

require_once("utilities.php");
require_once("classes/Parser.class.php");
require_once("classes/Rule.class.php");

if ($argc != 2) {
	echo "Usage: execute.php filename" . PHP_EOL;
	exit(-1);
}

// PARSING
try {
	// PARSE
	$parser = new Parser();
	$parser->parseFile($argv[1]);

	$rules = $parser->getRules();
	$display = $parser->getQueries();
	$GLOBALS = $parser->getValues();

	
} catch (Exception $e) {
	echo "Error during parsing" . PHP_EOL;
	echo $e->getMessage() . PHP_EOL;
	exit(-1);
}

// EXECUTION
try {
	$hasChanged = true;
	while ($hasChanged == true) {
		$hasChanged = false;

		foreach ($rules as $rule) {
			if ($rule->execute()) {
				$hasChanged = true;
			}
		}
	}

	foreach ($display as $key) {
		$globalValue = $GLOBALS[$key];
		echo $key . ": " . (($globalValue < 1) ? "FALSE" : "TRUE") . PHP_EOL;
	}

} catch (Exception $e) {
	echo "Error during execution" . PHP_EOL;
	echo $e->getMessage() . PHP_EOL;
	exit(-1);
}

?>