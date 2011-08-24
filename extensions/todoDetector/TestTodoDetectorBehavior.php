<?php

/**
 * behavior for testrunner
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package extensions.todoDetector
 */
class TestTodoDetectorBehavior extends TestRunnerBehaviorAbstract
{
	/**
	 * @var array
	 */
	protected $todos = array();

	/**
	 * Called before every single test run
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection and the test that will be run
	 * @return void
	 */
	public function beforeTest(TestRunnerEvent $event)
	{
		if ($event->currentTest->hasAttribute('todo')) {
			if (is_array($event->currentTest->todo)) {
				$todo = implode("\n", $event->currentTest->todo);
			} else {
				$todo = $event->currentTest->todo;
			}
			$this->todos[$event->currentTest->name] = $todo;
		}
	}

	/**
	 * Called after running a test sequence
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function afterRun(TestRunnerEvent $event)
	{
		if (!empty($this->todos))
		{
			TestRunnerPrintHelper::listResults('Todos', $this->todos);
		}
	}

}
