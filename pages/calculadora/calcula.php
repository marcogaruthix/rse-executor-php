<?php

$val1 = $_GET['val1'];
$val2 = $_GET['val2'];
$op = $_GET['op'];

if(!$val1 | !$val2 | !$op) { }

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
}

$template = file_get_contents(getcwd().'/pages/calculadora/index.html');
$template = str_replace("id='val1'", "id='val1' value='$val1'", $template);
$template = str_replace("id='val2'", "id='val2' value='$val2'", $template);
$template = str_replace("id='op'", "id='op' value='$op'", $template);
$template = str_replace("id='result'", "id='result' value='$result'", $template);

echo $template;