<?php

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TestPHPUnit.php';

/**
 * behavior for testCollector
 *
 * @package Behaviors
 */
class TestCollectorPHPUnit extends TestCollectorBehaviorAbstract
{
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
		$event->sender->registerPattern('*Test.php', 'phpunit');
	}

	/**
	 * Event that is raised before collecting tests
	 *
	 * @param TestCollectorEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function foundTest(TestCollectorEvent  $event)
	{
		if (in_array('phpunit', $event->descriptions))
		{
			$className = substr($event->testPath, strrpos($event->testPath, DIRECTORY_SEPARATOR) + 1, -4);

			$event->sender->command->p("\nincluding " . $event->testPath . '...', 3);
			require_once($event->testPath);

			if (!class_exists($className, false)) {
				throw new Exception('File "' . $event->testPath .  '" did not define class "' . $className . '".');
			}

			$testClass = new $className;

			$reflectionClass = new ReflectionClass($testClass);

			foreach($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
				if (substr($method->name, 0, 4) == 'test') {
					$event->sender->command->p("\n    " . $method->name, 3);
					$event->collection->addTest(
						new TestPHPUnit(
							$method->name,
							new $testClass($method->name, array(), ''),
							$method->name
						)
					);
				}
			}
		}
	}
}