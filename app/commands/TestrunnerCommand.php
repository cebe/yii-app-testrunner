<?php

/**
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package commands
 */
class TestrunnerCommand extends CConsoleCommand
{
	/**
	 * verbosity value
	 *
	 * 0 = quiet
	 *
	 * @var
	 */
	public $verbose = 1;

	/**
	 * returns help string for command
	 *
	 * @return string
	 */
	public function getHelp()
	{
		return <<<EOF
yii app testrunner by Carsten Brandt (c) 2011
---------------------------------------------

HELP

	usage: testrunner ... ...


	coming soon ;-)



	listScopes  list of available scopes for --scope

	runTests    run tests

		--path          set TestCollector.basePath
						default is the path defined in config/main.php

		--scope         choose a scope to filter tests (default is all)

		--verbose=x     x=0 quiet
						x=1 (default)
						x>1 print more information

		--quiet         like --verbose=0


EOF;
	}

	/**
	 * testrunner help
	 *
	 * @return void
	 */
	public function actionHelp()
	{
		echo $this->getHelp();
	}

	/**
	 * testrunner default action
	 *
	 * @return void
	 */
	public function actionIndex()
	{
		echo $this->getHelp();
	}

	/**
	 * runTests
	 *
	 * @return void
	 */
	public function actionRunTests($path=null, $scope='all', $verbose=1, $quiet=false)
	{
		// handle verbosity first
		if ($quiet) {
			$this->verbose = 0;
		} else {
			$this->verbose = $verbose;
			if ($this->verbose > 1) {
				$this->p('verbosity is ' . $this->verbose . "\n");
			}
		}

		$this->p("preparing...\n", 2);

		// scope
		$this->p(" - scope is '$scope'\n", 2);

		// basePath
		if (!is_null($path)) {
			if ($path{0} != '/') {
				$path = $_SERVER['PWD'] . '/' . $path;
			}
			Yii::app()->testCollector->basePath = $path;
		}
		$this->p(" - base Path is '" . Yii::app()->testCollector->basePath . "'\n", 2);

		$this->p("collecting tests...\n\n");

		$collection = Yii::app()->testCollector->collectTests();

		$this->p(" - found " . count($collection) /*->getCount()*/ . " tests\n", 1);

		$this->p("filtering tests... \n\n");

		$collection->applyScope($scope);

		$this->p("running tests...\n\n");

		$this->p("finished.\n");

		$this->p("summary:");
	}

	/**
     * list of available scopes
	 *
	 * @return void
	 */
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

	/**
	 * print text based on verbosity
	 *
	 * @return void
	 */
	public function p($text, $verbose = 1)
	{
		if ($this->verbose >= $verbose) {
			echo $text;
		}

	}

}
