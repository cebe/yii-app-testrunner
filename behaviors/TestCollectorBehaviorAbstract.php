<?php

/**
 * abstract behavior class for testCollector classes
 *
 * let testCollector behaviors extend this class
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Behaviors
 */
abstract class TestCollectorBehaviorAbstract extends CBehavior
{
	/**
	 * register eventhandlers
	 *
	 * @return array
	 */
	public function events()
	{
		return array(
			'onBeforeCollect' => 'beforeCollect',
			'onFoundTest' => 'foundTest',
		);
	}

	/**
	 * Event that is raised before collecting tests
	 *
	 * @param TestCollectorEvent the raised event
	 * @return void
	 */
	public function beforeCollect(TestCollectorEvent $event)
	{

	}

	/**
	 * Event that is raised when a test was found
	 *
	 * @param TestCollectorEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function foundTest(TestCollectorEvent $event)
	{

	}
}
