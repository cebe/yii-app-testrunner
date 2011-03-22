<?php

/**
 * behavoir that handles test dependencies
 *
 * run tests in
 *
 * @package Behaviors
 */
class TestDependencyBehavior extends TestRunnerBehaviorAbstract
{
	/**
	 * If true, all tests needed to run due to dependencies
	 * are forced to run even if they where exclued by scope
	 *
	 * @var bool
	 */
	public $autoEnableTests = true;

	/**
	 * Called before running a test sequence
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function beforeRun(TestRunnerEvent $event)
	{
		$collection = $event->collection;

/*		echo "reordering tests by name...";
		$collection->orderTests(function($testA, $testB) {
			return $testA->name < $testB->name;
		});
		echo " done.\n";*/

		echo "resolving test-dependencies...";
		$this->resolveCollectionDependencies($collection);
		echo " done.\n";

		echo "reordering tests by dependency...";

		$collection->orderTests(
			/**
			 * the lower-equal-function for re-ordering tests
			 *
			 * @param TestAbstract a test
			 * @param TestAbstract the compared test
			 * @return boolean
			 */
			function($testA, $testB) {

				// if A is in the dependecies of B it must be run before B, so it's lower-equal
				if (in_array($testA->name, TestDependencyBehavior::getDependencies($testB))) {
					return true;
				}
				// otherwise run it after B, just in case B is dependend on A
				return false;
			}
		);
		echo " done.\n";
	}

	/**
	 * return array of dependend tests
	 *
	 * @param TestAbstract $test
	 * @return array
	 */
	public static function getDependencies(TestAbstract $test)
	{
		if (isset($test->depends)) {
			$depends = explode(',', $test->depends);

			foreach($depends as &$dependency) {
				$dependency = trim($dependency);
				if (strpos($dependency, '::') === FALSE) {
					$dependency = get_class($test->testClass) . '::' . $dependency;
				}
			}
			return $depends;
		}
		return array();
	}

	public function resolveCollectionDependencies(TestCollectionAbstract $collection)
	{
		foreach($collection as $test)
		{
			$this->resolveTestDependencies($test, $collection);
		}

	}

	/**
	 * see http://www.phpunit.de/manual/current/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.test-dependencies
	 * for explaination on phrases "consumer" and "producer"
	 *
	 *
	 * @throws Exception
	 * @param TestAbstract $test
	 * @param TestCollection $collection
	 * @return void
	 */
	public function resolveTestDependencies(TestAbstract $test, TestCollection $collection)
	{
		if (count($depends = static::getDependencies($test))) {
			foreach($depends as $dependency) {echo '.';
				if (!count($dependsOnTests = $collection->getTestsByName($dependency))) {
					throw new Exception('Test "' . $dependency . '" does not exist, but "' . $test->name . '" depends on it.');
				}
				// activate tests
				if ($this->autoEnableTests) {
					foreach($dependsOnTests as $dpTest) {
						if (!$collection->isIncluded($dpTest)) {
							// producer is not included, enable it
							$collection->includeTest($dpTest);
							// resolve dependencies of the producer
							$this->resolveTestDependencies($dpTest, $collection);
						}
					}
				} else {
					foreach($dependsOnTests as $dpTest) {
						if (!$collection->isIncluded($dpTest)) {
							// producer is not included, disable myself
							$collection->excludeTest($test);
						}
					}
				}
			}
		}
	}
}
