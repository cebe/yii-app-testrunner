<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TestLiveTest.php';

/**
 * behavior for testCollector
 *
 * @package Behaviors
 */
class TestCollectorLiveTest extends TestCollectorBehaviorAbstract
{
	public $baseUrl = '';

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
		$event->sender->registerPattern('*Test.php', 'maybeLiveTest');
		$event->sender->registerPattern('*.yml', 'liveTestYaml');
	}

	/**
	 * Event that is raised before collecting tests
	 *
	 * @param TestCollectorEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function foundTest(TestCollectorEvent  $event)
	{
		if (in_array('maybeLiveTest', $event->descriptions))
		{
			$className = substr($event->testPath, strrpos($event->testPath, DIRECTORY_SEPARATOR) + 1, -4);

			$event->sender->command->p("\nincluding " . $event->testPath . '...', 3);
			require_once($event->testPath);

			if (!class_exists($className, false)) {
				throw new Exception('File "' . $event->testPath .  '" did not define class "' . $className . '".');
			}

			$testClass = new $className;

			if ($testClass instanceof TestLiveTest)
			{
				$test->baseUrl = $this->baseUrl;
				$event->sender->command->p("\n    " . $testClass->name, 3);
				$event->collection->addTest($testClass);
			}
		}
		if (in_array('liveTestYaml', $event->descriptions))
		{
			$testName = substr($event->testPath, strrpos($event->testPath, DIRECTORY_SEPARATOR) + 1, -4);

			$event->sender->command->p("\nincluding " . $event->testPath . '...', 3);

			$test = new TestLiveTest('LiveTestYaml::' . $testName);
			$test->baseUrl = $this->baseUrl;
			$test->liveTestConfigFile = $event->testPath;

			$event->collection->addTest($test);
		}
	}
}
