<?php

/**
 * @todo create abstract class
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestCollector
 */
class TestCollector extends TestComponentAbstract
{
	/**
	 * base Path for tests
	 *
	 * @var null|string
	 */
	private $_basePath = null;

	/**
	 * list of patterns which files to match
	 *
	 * pattern => description
	 *
	 * example: array('*Test.php' => 'phpunit');
	 *
	 * @var array
	 */
	public $patterns = array();


	public function registerPattern($pattern, $description)
	{
		$this->patterns[$pattern][] = $description;
	}

	/**
	 * sets the base path for tests
	 *
	 * @param  $path
	 * @return void
	 */
	public function setBasePath($path)
	{
		$this->_basePath = $path;
	}

	/**
	 * gets the base path for tests
	 *
	 * default is application.tests
	 *
	 * @return string
	 */
	public function getBasePath()
	{
		if (is_null($this->_basePath)) {
			$this->_basePath = dirname(Yii::app()->basePath) . DS . 'tests';
		}
		return $this->_basePath;
	}

	public function __construct($command=null)
	{
		$this->command = $command;
	}

	/**
	 * collect tests
	 *
	 * @throws Exception
	 * @return TestCollection
	 */
	public function collectTests()
	{
		$this->onBeforeCollect();

		$basePath = $this->getBasePath() . DIRECTORY_SEPARATOR;

		$tests = array();

		// find all files
		foreach($this->patterns as $pattern => $descriptions)
		{
			$directories = glob($basePath . '*', GLOB_ONLYDIR);
			$tests = $this->globMergeTests($tests, $basePath . $pattern, $descriptions);

			for($i=0; $i < count($directories); ++$i)
			{
				if (is_dir($directories[$i])) {
					$subDirectories = glob($directories[$i] . DIRECTORY_SEPARATOR . '*');
					$directories = array_merge($directories, $subDirectories);
					$tests = $this->globMergeTests($tests, $directories[$i] . DIRECTORY_SEPARATOR . $pattern, $descriptions);
				}
			}
		}

		$collection = new TestCollection();
		foreach($tests as $path => $descriptions)
		{
			$this->onFoundTest($path, $descriptions, $collection);
		}

		return $collection;
	}

	/**
	 * find tests by glob pattern and add them to the array
	 *
	 * @return array
	 */
	public function globMergeTests($tests, $globPattern, $addDescriptions=array())
	{
		if (!is_array($addDescriptions)) {
			$addDescriptions = array($addDescriptions);
		}

		$newTests = glob($globPattern);

		foreach($newTests as $test)
		{
			if (isset($tests[$test])) {
				$tests[$test] = array_merge($tests[$test], $addDescriptions);
			} else {
				$tests[$test] = $addDescriptions;
			}
		}

		return $tests;
	}

	/**
	 * Event that is raised before collecting tests
	 *
	 * you can add file patterns and
	 *
	 * @return void
	 */
	public function onBeforeCollect()
	{
		$this->raiseEvent('onBeforeCollect', new TestCollectorEvent($this));
	}

	/**
	 * Event that is raised when a test is found
	 *
	 * you are given the collection, so you can call addTest() on it
	 *
	 * @return void
	 */
	public function onFoundTest($path, $descriptions, $collection)
	{
		$event = new TestCollectorEvent($this);
		$event->testPath = $path;
		$event->descriptions = $descriptions;
		$event->collection = $collection;

		$this->raiseEvent('onFoundTest', $event);
	}
}

