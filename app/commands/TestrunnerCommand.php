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

		--includePath   comma seperated list of paths to add to include_path

		--bootstrap     a php file to run before tests are collected


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
	public function actionRunTests($bootstrap='',$includePath='', $path=null, $scope='all', $verbose=1, $quiet=false)
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

		$this->handleIncludePath($includePath);

		if (!empty($bootstrap)) {
			include($bootstrap);
		}

		$this->p("collecting tests...");

		$collection = Yii::app()->testCollector->collectTests();

		$this->p(" - " . count($collection) . " tests found\n", 1);

		$this->p("filtering tests... ");

		$collection->applyScope($scope);
		$this->p(" - " . ($count = count($collection)) . " tests to run\n", 1);
		if ($count < 1) {
			$this->p("no tests to run -> exiting\n", 1);
			exit(0);
		}

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


	protected function handleIncludePath($includePath)
	{
		if (!empty($includePath)) {
			$includePaths = explode(',', $includePath);

			$phpIncludePaths = array_unique(explode(PATH_SEPARATOR, get_include_path()));
			if (($pos = array_search('.', $phpIncludePaths, true)) !== false) {
				unset($phpIncludePaths[$pos]);
			}

			if (set_include_path('.' . PATH_SEPARATOR . implode(PATH_SEPARATOR, array_merge($includePaths, $phpIncludePaths))) === false) {
				throw new CException(Yii::t('yii','Unable to import "{alias}". Please check your server configuration to make sure you are allowed to change PHP include_path.',array('{alias}'=>$alias)));
			}

			foreach($includePaths as $path) {
				$this->p("added $path to include_path\n", 2);
			}
			$this->p("\nphp include_path is now " . get_include_path() . "\n\n", 3);
		}
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
