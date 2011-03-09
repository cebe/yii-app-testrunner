<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestRunner
 */
class TestRunner extends CComponent
{

	public function __construct()
	{

	}


	public function prepareRunning()
	{

	}

	public function run($collection)
	{
		$this->raiseEvent('onBeforeRun', new TestRunnerEvent($this, $collection));

		$this->prepareRunning();

		foreach($collection as $test)
		{
			$this->raiseEvent('onBeforeTest', new TestRunnerEvent($this, $collection, $test));
			if ($test->run()) {
				echo '.';
			} else {
				echo 'E';
			}
			$this->raiseEvent('onAfterTest', new TestRunnerEvent($this, $collection, $test));
		}

		$this->afterRunning();

		$this->raiseEvent('onAfterRun', new TestRunnerEvent($this, $collection));
	}

	public function afterRunning()
	{

	}
}

/**
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

