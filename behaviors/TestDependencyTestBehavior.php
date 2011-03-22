<?php

/**
 * dependency behavior for tests
 *
 * @package Behaviors
 */
class TestDependencyTestBehavior extends TestBehaviorAbstract
{
	/**
	 * register eventhandlers
	 *
	 * @return array
	 */
	public function events()
	{
		return array(
			'onMarkError'      => 'skipConsumers',
			'onMarkFailed'     => 'skipConsumers',
			'onMarkSkipped'    => 'skipConsumers',
			'onMarkIncomplete' => 'skipConsumers',
			//'onMarkPassed'     => 'markPassed',
		);
	}

	/**
	 * skip all consumers for a test since the test itself did not run
	 *
	 * @param TestEvent $event
	 * @return void
	 */
	public function skipConsumers(TestEvent $event)
	{
		if ($event->sender->hasAttribute('dependsConsumer')) {
			foreach($event->sender->dependsConsumer as $consumer)
			{
				$message = '';
				if ($consumer->skipped) {
					$message = $consumer->skippedMessage . (($consumer->skippedMessage != '') ? "\n" : '');
				}
				$consumer->markSkipped($message . 'Depends on ' . $event->sender->name . ' which did not run.');
			}
		}
	}

}
