<?php

function sum($val1, $val2, $op){
	if(empty($val1) | empty($val2 | empty($op)) { exit(0); }

	$result = null;
	switch ($op) {
		case 'adicao':
			$result = $val1 + $val2;
			break;
		case 'subtracao':
			$result = $val1 - $val2;
			break;
		case 'multiplicacao':
			$result = $val1 * $val2;
			break;
		case 'divisao':
			$result = $val1 / $val2;
			break;
		default:
			break;
	}

	return $result;
}