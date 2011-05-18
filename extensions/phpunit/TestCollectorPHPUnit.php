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

			foreach($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
			{
				if (substr($method->name, 0, 4) == 'test')
				{
					$event->sender->command->p("\n    " . $method->name, 3);

					// creates as many test classes as datasets are needed
					$dataSets = $this->getDataSets($testClass, $method->name);

					// add this test marked as skipped if no valid data is available
					if (!is_array($dataSets) OR empty($dataSets))
					{
						$test = new TestPHPUnit(
							$method->name,
							new $testClass($method->name, array(), ''),
							$method->name
						);
						$test->markSkipped('dataProvider has no valid data.');
						$event->collection->addTest($test);
					}
					else
					{
						foreach($dataSets as $dataName => $dataSet) {
							$event->collection->addTest(new TestPHPUnit(
								$method->name,
								new $testClass($method->name, $dataSet, $dataName),
								$method->name
							));
						}
					}
				}
			}
		}
	}

	/**
	 * get all data if a dataprovider is present
	 *
	 * @return array|boolean
	 */
	public function getDataSets($className, $methodName)
	{
		try {
		    $dataSets = PHPUnit_Util_Test::getProvidedData($className, $methodName);
			// no dataprovider exists
			if (is_null($dataSets)) {
				$dataSets = array(array());
			} // false on error
			elseif (!$dataSets) {
				return false;
			}
		}
		catch (Exception $e) {
			return false;
		}
		
		return $dataSets;
	}
}
