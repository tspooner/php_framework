<?php
/**
 * ij PHP Framework
 *
 * @package ij
 * @author Tom Spooner <spooner10000@gmail.com>
 */
define('START_TIME', microtime(true));
define('ENVIRONMENT', 'development');
/*
 |--------------------------------------------------------------------------
 | Define Global Constants
 |--------------------------------------------------------------------------
 |
 | [Description]
 |
 */

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap' . DIRECTORY_SEPARATOR . 'constants.php';

if (defined('ENVIRONMENT')) {
	ini_set('display_errors', 1);
	switch (ENVIRONMENT) {
		case 'development':
			error_reporting(-1);
		break;
		case 'testing':
		case 'production':
			error_reporting(0);
		break;
		default:
			exit('The application environment is not set correctly.');
	}
}

/*
 |--------------------------------------------------------------------------
 | Prepare Autoloader
 |--------------------------------------------------------------------------
 |
 | [Description]
 |
 */

require BOOTSTRAP . 'autoload.php';

/*
 |--------------------------------------------------------------------------
 | Load Common Functions
 |--------------------------------------------------------------------------
 |
 | [Description]
 |
 */

require BOOTSTRAP . 'common.php';

/*
 |--------------------------------------------------------------------------
 | Run Application
 |--------------------------------------------------------------------------
 |
 | [Description]
 |
 */

$app = new Tranquil\App();

$app->run();

$app->end();