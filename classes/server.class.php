<?php
require_once 'request.class.php';
require_once 'php.thread.class.php';

class Server{

	public function listen($address, $port){

		//Instancia um objeto Socket
		if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
		    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
		}

		//Atribui IP e Porta para o Socket
		if (socket_bind($sock, $address, $port) === false) {
		    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
		}

		//Inicia o servidor com numero maximo de conexoes simultaneas
		if (socket_listen($sock, 5) === false) {
		    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
		}

		echo "Server: $address:$port".PHP_EOL;

		do {
			//Aceita conexões através do socket
		    if (($msgsock = socket_accept($sock)) === false) {
		        echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
		        break;
		    }

		    $thread = new PhpThread($msgsock);
		    $thread->run();
	    	
		    //$thread->start();
		} while (true);

		socket_close($sock);
	}
}