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
	 * base path to search for tests
	 *
	 * @var string
	 */
	public $testPath = 'application.tests';

	/**
	 * configuration for testRunner
	 *
	 * @var array
	 */
	public $testRunner = array(
		'class' => 'TestRunner',
	);

	/**
	 * Base Yii-alias for testRunnerCommand
	 *
	 * set this to the directory where you added the command to your application
	 *
	 * @var string
	 */
	public $baseAlias = 'application.commands.testRunner';

	/**
	 * initialize Command, scopemanager and include aliases
	 *
	 * @return void
	 */
	public function init()
	{
		Yii::import($this->baseAlias . '.vendors.phpunit.*');
		Yii::import($this->baseAlias . '.behaviors.*');
		Yii::import($this->baseAlias . '.components.*');
		Yii::import($this->baseAlias . '.models.*');
		Yii::import($this->baseAlias . '.scopes.*');
		Yii::import($this->baseAlias . '.*');

		ScopeManager::setInstance(array(
			'scopePath' => array($this->baseAlias . '.scopes'),
		));
	}
	/**
	 *
	 * @return TestRunner
	 */
	public function getTestRunner()
	{
		$config = $this->testRunner;

		if (isset($config['class'])) {
			$class = $config['class'];
			unset($config['class']);
		} else {
			$class = 'TestRunner';
		}

		if (($pos = strripos($class, '.')) !== false) {
			Yii::import($class);
			$class = substr($class, $pos + 1);
		}
		$runner = new $class();
		$runner->configure($config);
		$runner->init();

		return $runner;
	}

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

/** standalone only **/

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
	public function actionRunTests($path='', $bootstrap='', $scope='all', $verbose=1, $quiet=false)
	{
		$this->handleVerbosity($verbose, $quiet);


		$this->p("preparing...\n", 2);

		$this->p(" - scope is '$scope'\n", 2);

		// loading phpunit
		require_once('PHPUnit/Autoload.php');
		$this->addAutoLoad('phpunit_autoload');
		$this->p(' - phpunit-version: ' . PHPUnit_Runner_Version::id() . "\n", 2);

		// select testPath
		$testPath = $this->selectTestPath($path);
		$this->p(" - testPath is '$testPath'\n", 2);

		$this->handleBootstrap($bootstrap, $testPath);



		$this->p("collecting tests...");

		$testCollector = new TestCollector($this);
		$testCollector->setBasePath($testPath);
		$collection = $testCollector->collectTests();

		$this->p(" - " . count($collection) . " tests found\n");



		$this->p("filtering tests... ");

		// @todo: add possibility to apply more scopes
		$collection->applyScope($scope);

		$this->p(" - " . ($count = count($collection)) . " tests to run\n");

		if ($count < 1) { // @todo: consider not to break here...
			$this->p("no tests to run -> exiting\n");
			exit(0);
		}



		$this->p("running tests...\n\n");

		$runner = $this->getTestRunner();
		$runner->collection = $collection;
		$runner->run();



		$this->p("finished.\n");
		$this->p("summary: (coming soon ;-) )\n");
	}

	/**
	 * check if verbosity was given as parameter and set it
	 *
	 * @param  $verbose
	 * @param bool $quiet
	 * @return void
	 */
	public function handleVerbosity($verbose, $quiet=false)
	{
		if ($quiet) {
			$this->verbose = 0;
		} else {
			$this->verbose = $verbose;
			if ($this->verbose > 1) {
				$this->p('verbosity is ' . $this->verbose . "\n");
			}
		}
	}

	/**
	 * find the path where tests are
	 *
	 * get as param or config/default value
	 *
	 * @param  $path
	 * @return mixed|string
	 */
	public function selectTestPath($path)
	{
		if (empty($path)) {
			$path = $this->testPath;
		}

		$testPath = Yii::getPathOfAlias($path);
		if ($testPath === false) {
			$testPath = $path;
		} else {
			Yii::import($path . '.*');
		}

		if (!file_exists($testPath)) {
			$this->p(' - ERROR: testPath does not exist: ' . $path . "\n");
			exit(1);
		}
		return $testPath;
	}

	/**
	 * find bootstrap file and run it if found
	 *
	 * @param  $bootstrap
	 * @param  $testPath
	 * @return void
	 */
	public function handleBootstrap($bootstrap, $testPath)
	{
		if (empty($bootstrap)) {
			$bootstrap = $testPath . '/bootstrap.php';
		} elseif (!file_exists($bootstrap)) {
			$bootstrap = $testPath . '/' . $bootstrap;
		}

		if (file_exists($bootstrap)) {
			$this->p("running bootstrap script...\n");
			include($bootstrap);
		} else {
			$this->p(" - no bootstrap file found\n", 2);
		}
	}

	/**
     * list of available scopes
	 *
	 * @return void
	 */
	public function actionListScopes()
	{
		echo 'list of available scopes:' . "\n\n";

		$list = ScopeManager::getInstance()->listScopes();

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
	 * adds an autoloader at first position
	 *
	 * Yii autoload should be the last because it tries to
	 * include files that do not exist if non-yii classes are loaded
	 *
	 * @param  $newFunction
	 * @return void
	 */
	protected function addAutoLoad($newFunction)
	{
		$functions = spl_autoload_functions();
		foreach($functions as $key => $function) {
			spl_autoload_unregister($function);
			// unset if already exists
			if ((is_array($function) AND
				$function[0] == $newFunction[0] AND
				$function[1] == $newFunction[1])
				OR $function == $newFunction)
			{
				unset($functions[$key]);
			}
		}
		array_unshift($functions, $newFunction);
		foreach($functions as $function) {
			spl_autoload_register($function);
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
