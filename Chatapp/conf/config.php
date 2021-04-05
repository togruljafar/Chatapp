<?php
	// connect to local DB
	$host		  = 'localhost';
	$username	  = 'root';
	$password 	  = '';
	$database	  = 'texlab';
	$charset = 'utf8mb4';

  	try {
		$db = new PDO("mysql:host=$host;dbname=$database;charset=$charset","$username","$password", array(
	        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	    ));
	} catch (\PDOException $e) {
		throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}

  	session_start();

?>