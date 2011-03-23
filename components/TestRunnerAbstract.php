<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestRunner
 */
abstract class TestRunnerAbstract extends CApplicationComponent
{
	/**
	 * The TestCollection of test to be run
	 *
	 * @var TestCollectionAbstract|null
	 */
	public $collection = null;

	/**
	 * the correcsponding command
	 *
	 * @var TestrunnerCommand
	 */
	public $command = null;

	/**
	 *
	 * @param null|TestrunnerCommand $command
	 */
	public function __construct($command=null)
	{
		$this->command = $command;
	}

	/**
	 * Configures the class with the specified configuration.
	 * @param array $config the configuration array
	 */
	public function configure($config)
	{
		if (is_array($config))
		{
			foreach($config as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * run tests
	 *
	 * all further functionality should be added as behaviors
	 *
	 * @return void
	 */
	abstract public function run();

	/**
	 * Event that is raised before running a test sequence
	 *
	 * @return void
	 */
	public function onBeforeRun()
	{
		$this->raiseEvent('onBeforeRun', new TestRunnerEvent($this, $this->collection));
	}

	/**
	 * Event that is raised before every single test run
	 *
	 * @return void
	 */
	public function onBeforeTest($test)
	{
		$this->raiseEvent('onBeforeTest', new TestRunnerEvent($this, $this->collection, $test));
	}

	/**
	 * Event that is raised after every single test run
	 *
	 * @return void
	 */
	public function onAfterTest($test)
	{
		$this->raiseEvent('onAfterTest', new TestRunnerEvent($this, $this->collection, $test));
	}

	/**
	 * Event that is raised after every tests ran
	 *
	 * @return void
	 */
	public function onAfterRun()
	{
		$this->raiseEvent('onAfterRun', new TestRunnerEvent($this, $this->collection));
	}

	/**
	 * Event that is raised just before the application exits
	 *
	 * allows you to implement own exit codes
	 *
	 * @return void
	 */
	public function onExit()
	{
		$this->raiseEvent('onExit', new TestRunnerEvent($this, $this->collection));
	}
}

/**
 * Events that are raised from TestRunner
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestRunner
 */
class TestRunnerEvent extends CEvent
{
	/**
	 * the current collection beeing run
	 *
	 * @var TestCollection
	 */
	public $collection = null;

	/**
	 * The current test, null if none
	 *
	 * @var null|TestAbstract
	 */
	public $currentTest = null;

	/**
	 *
	 * @param mixed $sender
	 * @param TestCollection $collection
	 * @param TestAbstract $currentTest
	 */
	public function __construct($sender=null, $collection=null, $currentTest=null)
	{
		$this->collection = $collection;
		$this->currentTest = $currentTest;
		parent::__construct($sender);
	}
}

