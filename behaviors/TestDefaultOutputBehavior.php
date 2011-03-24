<?php

/**
 * behavior for testrunner
 *
 * @package Behaviors
 */
class TestDefaultOutputBehavior extends TestRunnerBehaviorAbstract
{
	/**
	 * application exit code
	 *
	 * @var int
	 */
	protected $exitCode = 0;

	/**
	 * Called before every single test run
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection and the test that will be run
	 * @return void
	 */
	public function beforeTest(TestRunnerEvent $event)
	{
		if ($this->owner->command->verbose > 1) {
			echo "running " . $event->currentTest->testClass->toString() . " ";
		}
	}

	/**
	 * Called after every single test run
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection and the test that has been run
	 * @return void
	 */
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

	/**
	 * Called after running a test sequence
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function afterRun(TestRunnerEvent $event)
	{
		$countAll = 0;
		$countError = 0;
		$countFailed = 0;
		$countSkipped = 0;
		$countIncomplete = 0;
		$countPassed = 0;

		$errors = array();
		$failures = array();
		$skipped = array();
		foreach($event->collection as $test)
		{
			++$countAll;
			switch(true)
			{
				case $test->error:
					$errors[$test->name] = $test->errorMessage;
					++$countError;
				break;
				case $test->failed:
					$failures[$test->name] = $test->failureMessage;
					++$countFailed;
				break;
				case $test->skipped:
					$skipped[$test->name] = $test->skippedMessage;
					++$countSkipped;
				break;
				case $test->incomplete:
					++$countIncomplete;
				break;
				case $test->passed:
					++$countPassed;
				break;
			}
		}

		TestRunnerPrintHelper::listResults('Errors', $errors);
		TestRunnerPrintHelper::listResults('Failures', $failures);
		TestRunnerPrintHelper::listResults('Skipped', $skipped);

		echo $countAll . ' Tests run' . "\n";
		echo $countError . ' errors' . "\n";
		echo $countFailed . ' failures' . "\n";
		echo $countSkipped . ' skipped' . "\n";
		echo $countIncomplete . ' incomplete' . "\n";
		echo $countPassed . ' passed' . "\n";

		if (empty($errors) AND empty($failures)) {
			$this->exitCode = 0;
		} else {
			$this->exitCode = 1;
		}
	}

	/**
	 * Called after running a test sequence
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function handleExit(TestRunnerEvent $event)
	{
		exit($this->exitCode);
	}
}
