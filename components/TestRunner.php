<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestRunner
 */
class TestRunner extends CComponent
{
	/**
	 * The TestCollection of test to be run
	 *
	 * @var TestCollectionAbstract|null
	 */
	public $collection = null;

	/**
	 *
	 * @param TestCollectionAbstract|null $collection
	 */
	public function __construct($collection=null)
	{
		if (!is_null($collection)) {
			$this->collection = $collection;
		}
	}

	/**
	 * run tests
	 *
	 * all further functionality should be added as behaviors
	 *
	 * @return void
	 */
	public function run()
	{
		// raise event
		$this->onBeforeRun();

		foreach($this->collection as $test)
		{
			// raise event
			$this->onBeforeTest($test);

			if ($test->run()) {
				echo '.';
			} else {
				echo 'E';
			}
			// raise event
			$this->onAfterTest($test);
		}

		// raise event
		$this->onAfterRun();
	}

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

