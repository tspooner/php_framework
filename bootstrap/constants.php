<?php

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

if (!defined('ROOT')) define('ROOT', dirname(__DIR__) . DS);

if (!defined('APP')) define('APP', ROOT . 'app' . DS);

if (!defined('CONFIG')) define('CONFIG', APP . 'Config' . DS);

if (!defined('BOOTSTRAP')) define('BOOTSTRAP', ROOT . 'bootstrap' . DS);

if (!defined('SYSTEM')) define ('SYSTEM', ROOT . 'system' . DS);

if (!defined('TRANQUIL')) define('TRANQUIL', SYSTEM . 'Tranquil' . DS);

if (!defined('PUBLIC')) define('PUBLIC', ROOT . 'public' . DS);

if (!defined('TMP')) define('TMP', APP . 'tmp' . DS);