<?php

return array(

	'default' => array(
		'adapter' => 'mysql',
		'persistent' => true,
		'host' => 'localhost',
		'database' => 'cms',
		'user' => 'root',
		'pass' => 'root',
		'port' => '3306',
		'prefix' => '',
		'fetch' => PDO::FETCH_ASSOC,
		'options' => array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		)
	),

);