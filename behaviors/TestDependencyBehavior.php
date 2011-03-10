<?php

/**
 * behavoir that handles test dependencies
 *
 */
class TestDependencyBehavior extends TestRunnerBehaviorAbstract
{
	/**
	 * Called before running a test sequence
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function beforeRun($event)
	{
		$collection = $event->collection;
	}
}
