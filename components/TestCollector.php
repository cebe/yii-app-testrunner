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
		$basePath = $this->getBasePath() . DIRECTORY_SEPARATOR;

		$directories = glob($basePath . '*', GLOB_ONLYDIR);
		$tests = glob($basePath . '*Test.php');

		for($i=0; $i < count($directories); ++$i)
		{
			if (is_dir($directories[$i])) {
				$subDirectories = glob($directories[$i] . DIRECTORY_SEPARATOR . '*');
				$directories = array_merge($directories, $subDirectories);
				$tests = array_merge($tests, glob($directories[$i] . DIRECTORY_SEPARATOR . '*Test.php'));
			}
		}

		$collection = new TestCollection();
		foreach($tests as $path)
		{
			$className = substr($path, strrpos($path, DIRECTORY_SEPARATOR) + 1, -4);

			$this->command->p("\n".$path, 3);
			require_once($path);
			if (!class_exists($className, false)) {
				throw new Exception('File "' . $path .  '" did not define class "' . $className . '".');
			}
			$testClass = new $className;

			$reflectionClass = new ReflectionClass($testClass);

			foreach($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
				if (substr($method->name, 0, 4) == 'test') {
					$this->command->p("\n    ".$method->name, 3);
					$collection->addTest(new TestBase($testClass->getName(), clone $testClass, $method->name));
				}
			}
		}

		return $collection;
	}
}
