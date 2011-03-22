<?php

/**
 * behavior for tests
 *
 * @package Behaviors
 */
class TestBehaviorAbstract extends CBehavior
{
	/**
	 * register eventhandlers
	 *
	 * @return array
	 */
	public function events()
	{
		return array(
			'onMarkError'      => 'markError',
			'onMarkFailed'     => 'markFailed',
			'onMarkSkipped'    => 'markSkipped',
			'onMarkIncomplete' => 'markIncomplete',
			'onMarkPassed'     => 'markPassed',
		);
	}

	/**
	 * Called when a test is marked as "failed due to an error"
	 *
	 * @param TestEvent the raised event holding the current test and message
	 * @return void
	 */
	public function markError(TestEvent $event)
	{

	}

	/**
	 * Called when a test is marked as "failed"
	 *
	 * @param TestEvent the raised event holding the current test and message
	 * @return void
	 */
	public function markFailed(TestEvent $event)
	{

	}

	/**
	 * Called when a test is marked as "skipped"
	 *
	 * @param TestEvent the raised event holding the current test and message
	 * @return void
	 */
	public function markSkipped(TestEvent $event)
	{

	}

	/**
	 * Called when a test is marked as "incomplete"
	 *
	 * @param TestEvent the raised event holding the current test and message
	 * @return void
	 */
	public function markIncomplete(TestEvent $event)
	{

	}

	/**
	 * Called when a test is marked as "passed"
	 *
	 * @param TestEvent the raised event holding the current test and message
	 * @return void
	 */
	public function markPassed(TestEvent $event)
	{

	}

}
