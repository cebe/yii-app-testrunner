<?php

/**
 * behavior for testCollector
 *
 * @package Behaviors
 */
class TestCollectorBehaviorAbstract extends CBehavior
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
	public function beforeCollect(TestCollectorEvent  $event)
	{

	}

	/**
	 * Event that is raised before collecting tests
	 *
	 * @param TestCollectorEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function foundTest(TestCollectorEvent  $event)
	{

	}
}
