<?php

function isTrue($str) {
	if (strpos($str, '!') !== false) {
		$str = substr($str, 1);

		if ($GLOBALS[$str] == 1) {
			return (false);
		} else {
			return (true);
		}
	}

	if ($GLOBALS[$str] == 1) {
		return (true);
	} else {
		return (false);
	}
}

function isTrueMod(&$str) {
	if (strpos($str, '!') !== false) {
		$str = substr($str, 1);

		if ($GLOBALS[$str] == 1) {
			return (false);
		} else {
			return (true);
		}
	}
	if ($GLOBALS[$str] == 1) {
		return (true);
	} else {
		return (false);
	}
}
