<?php

/**
 * behavior for testrunner
 *
 * @package Behaviors
 */
class TestOutputPhpUnitStyleBehavior extends TestRunnerBehaviorAbstract
{
	public function beforeTest(TestRunnerEvent $event)
	{
		if ($this->owner->command->verbose > 1) {
			echo "running " . $event->currentTest->testClass->toString() . " ";
		}
	}

	public function afterTest(TestRunnerEvent $event)
	{
		$v = ($this->owner->command->verbose > 1);

		switch(true)
		{
			case $event->currentTest->error:
				echo $v ? 'error' . "\n" : 'E';
			break;
			case $event->currentTest->failed:
				echo $v ? 'failed' . "\n" : 'F';
			break;
			case $event->currentTest->skipped:
				echo $v ? 'skipped' . "\n" : 'S';
			break;
			case $event->currentTest->incomplete:
				echo $v ? 'incomplete' . "\n" : 'I';
			break;
			case $event->currentTest->passed:
				echo $v ? 'ok' . "\n" : '.';
			break;
		}
	}

	public function afterRun(TestRunnerEvent $event)
	{
		$errors = array();
		$longestErrorName = 0;
		$failures = array();
		$longestFailureName = 0;
		foreach($event->collection as $test)
		{
			switch(true)
			{
				case $test->error:
					$errors[] = array('test' => $test->name, 'message' => $test->errorMessage);
					if ($longestErrorName < strlen($test->name)) {
						$longestErrorName = strlen($test->name);
					}
				break;
				case $test->failed:
					$failures[] = array('test' => $test->name, 'message' => $test->failureMessage);
					if ($longestFailureName < strlen($test->name)) {
						$longestFailureName = strlen($test->name);
					}
				break;
			}
		}
		if (!empty($errors)) {
			echo "\n\nErrors: \n\n";
			foreach($errors as $error) {
				echo $error['test'] . ':' .
				     str_repeat(' ', $longestErrorName + 2 - strlen($error['test'])) .
				     $error['message']  . "\n";
			}
		}

		if (!empty($failures)) {
			echo "\n\nFailures: \n\n";
			foreach($failures as $failure) {
				echo $failure['test'] . ':' .
				     str_repeat(' ', $longestFailureName + 2 - strlen($failure['test'])) .
				     $failure['message']  . "\n";
			}
		}
		if (empty($errors) AND empty($failures)) {
			exit(0);
		} else {
			exit(1);
		}
	}
}
