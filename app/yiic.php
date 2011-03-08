<?php
/**
 * Yii command line script file.
 *
 * This script is meant to be run on command line to execute
 * one of the pre-defined console commands.
 */

// find bootstrap in arguments
$bootstrap = false;
$basePath = dirname(__FILE__);
foreach ($argv as $arg)
{
	$arg = explode('=', $arg);
	if ($arg[0] == '--bootstrap') {
		$bootstrap = $arg[1];
	}
	if ($arg[0] == '--path') {
		$basePath = $arg[1];
	}
}

if ($bootstrap) {
	if ($bootstrap{0} == '/') {
		include($bootstrap);
	} else {
		include($basePath . '/' . $bootstrap);
	}
}

// if YiiBase was not loaded by bootstrap load our own
if (!class_exists('YiiBase', false)) {
	require(dirname(dirname(__FILE__)) . '/yii/framework/YiiBase.php');
}

$yii = dirname(__FILE__).'/TestYii.php';
$appPath = dirname(__FILE__).'/components/TestApplication.php';
$config = dirname(__FILE__).'/config/main.php';

require_once($yii);
require_once($appPath);

defined('YII_DEBUG') or define('YII_DEBUG',true);


$app = TestYii::createApplication('TestApplication', $config);
$app->commandRunner->addCommands(YII_PATH.'/cli/commands');
$env = @getenv('YII_CONSOLE_COMMANDS');

if(!empty($env)) {
	$app->commandRunner->addCommands($env);
}

$app->run();
