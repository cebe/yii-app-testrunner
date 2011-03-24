<?php

/**
 * behavior for testrunner
 *
 * @todo extend to support LiveTest and others
 *
 * @package Behaviors
 */
class TestLoggerJUnitBehavior extends TestRunnerBehaviorAbstract
{
	/**
	 * @var PHPUnit_Framework_TestResult|null
	 */
	protected $logger = null;

	/**
	 * @var PHPUnit_Framework_TestSuite|null
	 */
	protected $suite = null;

	public $logPath = null;

	public $logFile = 'junit.xml';

	/**
	 * Called before running a test sequence
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function beforeRun(TestRunnerEvent $event)
	{
		$reportPath = $this->logPath;
		if (is_null($reportPath)) {
			$reportPath = Yii::getPathOfAlias($this->owner->command->testPath) . '/report';
		}
		if (!file_exists($reportPath)) {
			mkdir($reportPath, 0775, true);
		}

		$this->logger = new PHPUnit_Framework_TestResult(null);
		$this->logger->addListener(
			new PHPUnit_Util_Log_JUnit($reportPath . DIRECTORY_SEPARATOR . $this->logFile, true)
		);

		$this->suite = new PHPUnit_Framework_TestSuite('', 'all tests');
		$this->logger->startTestSuite($this->suite);
	}

	/**
	 * Called before every single test run
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection and the test that will be run
	 * @return void
	 */
	public function beforeTest(TestRunnerEvent $event)
	{
		if ($event->currentTest instanceof TestPHPUnit) {
			$this->logger->startTest($event->currentTest->testClass);
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
		if ($event->currentTest instanceof TestPHPUnit) {
			$this->logger->endTest($event->currentTest->testClass, $event->currentTest->time);
		}

		//$this->logger->addError($event->currentTest->testClass, 'wtf', 0.12);
	}

	/**
	 * Called after running a test sequence
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function afterRun(TestRunnerEvent $event)
	{
		$this->logger->endTestSuite($this->suite);
		$this->logger->flushListeners();
	}
}
