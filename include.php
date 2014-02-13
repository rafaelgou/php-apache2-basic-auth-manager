<?php

// Include config
require_once 'config.php'; 

// Include Manager Classes
require_once('lib/PHPHttp/Passwd.php');
require_once('lib/PHPHttp/Group.php');
require_once('lib/PHPHttp/Security.php');

$passwdHandler = new PHPHttp\Passwd($CONFIG['htpasswd']);
$groupHandler = new PHPHttp\Group($CONFIG['htgroup']);

PHPHttp\Security::checkAdmin($groupHandler);
