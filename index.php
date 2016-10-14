<?php
$host=isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:"localhost";
switch ($host)
{
	case "localhost":
		require_once '../framework/lib/php.init.php';
	break;
}
?>