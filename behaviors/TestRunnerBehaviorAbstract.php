<?php

/**
 * abstract behavior class for testRunner classes
 *
 * let testRunner behaviors extend this class
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Behaviors
 */
abstract class TestRunnerBehaviorAbstract extends CBehavior
{
	/**
	 * register eventhandlers
	 *
	 * @return array
	 */
	public function events()
	{
		return array(
			'onBeforeRun' => 'beforeRun',
			'onBeforeTest' => 'beforeTest',
			'onAfterTest' => 'afterTest',
			'onAfterRun' => 'afterRun',
			'onExit' => 'handleExit',
		);
	}

	/**
	 * Called before running a test sequence
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function beforeRun(TestRunnerEvent $event)
	{

	}

	/**
	 * Called before every single test run
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection and the test that will be run
	 * @return void
	 */
	public function beforeTest(TestRunnerEvent $event)
	{

	}

	/**
	 * Called after every single test run
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection and the test that has been run
	 * @return void
	 */
	public function afterTest(TestRunnerEvent $event)
	{

	}

	/**
	 * Called after running a test sequence
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function afterRun(TestRunnerEvent $event)
	{

	}

	/**
	 * Called after running a test sequence
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function handleExit(TestRunnerEvent $event)
	{

	}
}
