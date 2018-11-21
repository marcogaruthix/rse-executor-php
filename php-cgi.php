<?php

require_once 'classes/request.class.php';

$request_plain = $argv[1];
$server_path = $argv[2];

$request = new Request( base64_decode( $request_plain ) );

foreach ($request->params as $key => $value) {
	$_GET[$key] = $value;
	$_REQUEST[$key] = $value;
}

foreach ($request->post_params as $key => $value) {
	$_POST[$key] = $value;
	$_REQUEST[$key] = $value;
}


$response = '';
$language = 'php';
$code = base64_decode($request->post_params['content']);
$args = $request->post_params['args'];

switch ($language) {
	case 'php':
		$function_call = explode('(', explode(' ', $code)[1])[0];
		if(count($args) == 0)
			$function_call .= "()";
		else{
			$function_call .= "(";
			foreach ($args as $key => $arg) {
				if(count($args) -1 == $key)
					$function_call .= "'$arg'";
				else
					$function_call .= "'$arg',";
			}
			$function_call .= ')';
		}

		$code_injectable = str_replace('<?php', '', $code);
		$code_injectable = str_replace('<?', '', $code_injectable);
		$code_injectable = "<?php " . $code_injectable . PHP_EOL . "echo $function_call;";
		$filename = sha1(microtime()) . '.php';
		file_put_contents($filename, $code_injectable);

		include $filename;
		unlink($filename);

		break;
	
	default:
		# code...
		break;
}
