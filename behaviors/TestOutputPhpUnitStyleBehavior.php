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
		$skipped = array();
		$longestSkippedName = 0;
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
				case $test->skipped:
					$skipped[] = array('test' => $test->name, 'message' => $test->skippedMessage);
					if ($longestSkippedName < strlen($test->name)) {
						$longestSkippedName = strlen($test->name);
					}
				break;
			}
		}

		$this->listResults('Errors', $errors, $longestErrorName);
		$this->listResults('Failures', $failures, $longestFailureName);
		$this->listResults('Skipped', $skipped, $longestSkippedName);

		if (empty($errors) AND empty($failures)) {
			exit(0);
		} else {
			exit(1);
		}
	}

	public function listResults($type, $results, $longest)
	{
		if (!empty($results)) {
			echo "\n\n$type: \n\n";
			foreach($results as $result) {
				echo $result['test'] . ':' .
				     str_repeat(' ', $longest + 2 - strlen($result['test'])) .
				     str_replace("\n", "\n" . str_repeat(' ', $longest + 3), $result['message'])  . "\n";
			}
		}

	}
}
