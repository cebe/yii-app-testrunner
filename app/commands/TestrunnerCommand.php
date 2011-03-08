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



	listScopes  list of available scopes




	--scope     choose a scope to filter tests


EOF;
	}

	function actionIndex()
	{
		echo "running tests...";

		$tests = TestCollector();





	}

	public function actionListScopes()
	{
		echo 'list of available scopes:' . "\n\n";

		$list = Yii::app()->scopeManager->listScopes();

		$maxlen = 6;
		foreach ($list as $name => $description) {
			if ($maxlen < strlen($name)) {
				$maxlen = strlen($name) + 1;
			}
		}
		echo '   name ' . str_repeat(' ', $maxlen - 5) . 'description' . "\n\n";

		foreach ($list as $name => $description) {
			echo ' - ' . $name . str_repeat(' ', $maxlen - strlen($name)) . $description . "\n";
		}

		echo "\n";
		exit();
	}

}
