<?php

/**
 * abstract class for all TestRunners
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestRunner
 */
abstract class TestRunnerAbstract extends TestComponentAbstract
{
	/**
	 * The TestCollection of test to be run
	 *
	 * @var TestCollectionAbstract|null
	 */
	public $collection = null;

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
	 * Event that is raised after all tests ran
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

