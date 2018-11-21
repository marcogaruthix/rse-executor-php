<?php

class Request{
	public $method;
	public $query;
	public $path;
	public $host;
	public $user_agent;
	public $accept;
	public $accept_language;
	public $accept_encoding;
	public $cookie;
	public $connection;
	public $params = [];
	public $post_params = [];
	//public $upgrade_insecure_requests;
	//public $cache_control;

	public function __construct($plain){
		$this->parseGET_POST($plain);
	}

	public function parseGET_POST($plain){
		$lines = explode(PHP_EOL, $plain);
		$this->method = explode(' ', $lines[0] )[0];
		$this->path = explode('?', explode(' ', $lines[0])[1])[0];

		if(count( explode('?', explode(' ', $lines[0])[1]) ) > 1)
			$this->query = explode('?', explode(' ', $lines[0])[1])[1];
		else
			$this->query = '';

		if($this->query){
			$queries = explode('&', $this->query);
			foreach ($queries as $query) {
				$values = explode('=', $query);
				$key = $values[0];
				$val = $values[1];
				$this->params[ $key ] = $val;
			}
		}

		if(strtolower($this->method) == 'post'){
			//raw
			if( count( explode(PHP_EOL.'{', $plain) ) == 2 )
				$this->post_params = json_decode('{' . trim(explode(PHP_EOL.'{', $plain)[1]), true);
			else //form-data (from navigator)
				foreach ($lines as $i => $line) {
					if(strpos($line, 'Content-Disposition:') === false)
						continue;
					preg_match('/".*"/', $line, $matches);
					$param_key = str_replace('"', '', $matches[0]);
					$param_value = $lines[ $i + 2 ];
					$param_value = substr($param_value, 0, -1);
					$this->post_params[ $param_key ] = $param_value;
				}
		}
	}

}