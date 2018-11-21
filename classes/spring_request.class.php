<?php
/*
	input:
	{
		"id" : 1,
        "type": "javascript",
        "howManyArguments" : 2,
        "content" : "function sum(p1, p2) { return p1 + p2; }",
        "args" : [10,40]
    }

    output:
    {
        "id" : 10,
        "result" : "50",
        "timeElapsed" : 4568
    }
*/
class SpringRequest{
	static function request_is_valid($request){
		if( strtolower($request->method) != 'post' ) return false;
		if( !array_key_exists('id', $request->post_params) ) return false;
		if( !array_key_exists('type', $request->post_params) ) return false;
		if( !array_key_exists('howManyArguments', $request->post_params) ) return false;
		if( !array_key_exists('content', $request->post_params) ) return false;
		if( !array_key_exists('args', $request->post_params) ) return false;
		//if( count($request->post_params['args']) < $request->post_params['howManyArguments'] ) return false;

		return true;
	}
}