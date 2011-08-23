<?php

/**
 * Events that are raised from TestRunner
 *
 * @link http://www.yiiframework.com/doc/guide/1.1/en/basics.component#component-event
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
