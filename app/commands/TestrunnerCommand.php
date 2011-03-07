<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 */
class TestrunnerCommand extends CConsoleCommand
{
	function actionHelp() {
		echo <<<EOF
yii app testrunner by Carsten Brandt (c) 2011
---------------------------------------------

HELP

	usage: testrunner ... ...


	coming soon ;-)


EOF;
	}

	function actionIndex()
	{
		echo "running tests...";

		$tests = TestCollector();





	}


}
