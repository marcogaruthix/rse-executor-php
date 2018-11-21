<?php

class PhpThread{
	private $socket;

	public function __construct($socket){
		$this->socket = $socket;
		socket_set_nonblock($this->socket);
	}

	public function run(){
	    $buf = $this->read_socket();
	    if (!$buf = trim($buf))
	        return;


	    $server_path = getcwd();
	    $php_cgi_path = $server_path."/php-cgi.php";
	    $buf_b64 = base64_encode($buf);

	    $cgi_request = new Request($buf); //Parse a CGI Request
    	$start_at = microtime(true);
	    if(SpringRequest::request_is_valid($cgi_request)){ //Spring contract
	    	$content = shell_exec("php $php_cgi_path $buf_b64 $server_path");
	    	$end_at = microtime(true);
	    	$response = json_encode( [ 'timeElapsed' => ($end_at - $start_at) * 1000, 'result' => $content, 'id' => $cgi_request->post_params['id'] ] );

		    $this->answer_socket($response);
		    socket_close($this->socket);
		}
		else{
	    	$end_at = microtime(true);
	    	$response = json_encode( [ 'timeElapsed' => $end_at - $start_at, 'result' => null, 'id' => null ] );
		    $this->answer_socket($response, "422 Unprocessable Entity");
		    socket_close($this->socket);
		}
	}


	private function read_socket(){
		$buf = '';
        /*if (false === ($buf = socket_read($this->socket, 2048))) {
            echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($this->socket)) . "\n";
            exit(0);
        }*/

        while($buf == ''){
       		$buf_aux = socket_read($this->socket, 10000);
       		//var_dump($buf_aux);
       		if($buf)
       			if(!$buf_aux || $buf_aux == '' || $buf_aux == false)
       				break;
       		$buf .= $buf_aux;
       		sleep(1);
        }

        print_r( PHP_EOL.PHP_EOL."Recebido: ".PHP_EOL.$buf.PHP_EOL.PHP_EOL );

        return $buf;
	}

	private function answer_socket($body, $http_status = "200 OK"){
		$response = "HTTP/1.1 $http_status".PHP_EOL;
		$response .= 'Content-type: application/json'.PHP_EOL.PHP_EOL;
		$response .= $body;

		print_r( PHP_EOL.PHP_EOL."Resposta: ".PHP_EOL.$response.PHP_EOL.PHP_EOL );

		return socket_write($this->socket, $response, strlen($response));
	}
}