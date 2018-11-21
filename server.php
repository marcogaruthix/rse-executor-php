<?php
require_once 'classes/server.class.php';
require_once 'classes/request.class.php';
require_once 'classes/spring_request.class.php';

error_reporting(E_ALL);
set_time_limit(0);
/* Turn on implicit output flushing so we see what we're getting
 * as it comes in. */
ob_implicit_flush();


$server = new Server();
$server->listen('127.0.0.1', 4020);