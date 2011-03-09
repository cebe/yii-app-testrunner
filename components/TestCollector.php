<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestCollector
 */
class TestCollector extends CComponent
{
	/**
	 * base Path for tests
	 *
	 * @var null|string
	 */
	private $_basePath = null;

	/**
	 * the correcsponding command
	 *
	 * @var TestrunnerCommand
	 */
	public $command = null;

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

	public function init()
	{
		// nothing
	}

	public function __construct($command=null)
	{
		$this->command = $command;
	}

	public function collectTests()
	{
		$basePath = $this->getBasePath() . '/';

		$directories = glob($basePath . '*', GLOB_ONLYDIR);
		$tests = glob($basePath . '*Test.php');

		for($i=0; $i < count($directories); ++$i)
		{
			if (is_dir($directories[$i])) {
				$subDirectories = glob($directories[$i] . '/*');
				$directories = array_merge($directories, $subDirectories);
				$tests = array_merge($tests, glob($directories[$i] . '/*Test.php'));
			}
		}

		$collection = new TestCollection();
		foreach($tests as $path)
		{
			if (preg_match('/\/(.*Test)\.php$/i', $path, $matches)) {
				$className = $matches[1];
				require_once($path);
				$testClass = new $className;
				$collection->addTest(new TestBase($testClass->getName(), $testClass));
			}
		}

		return $collection;
	}

	public function collectTestsByPath()
	{

		return new TestCollection();
	}

	public function collectTestsByTag()
	{

		return new TestCollection();
	}

	public function collectTestsByWheaterAndTemper()
	{

		return new TestCollection();
	}
}
