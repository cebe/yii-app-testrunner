<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestRunner
 */
class TestRunner extends TestRunnerAbstract
{
	/**
	 * run tests
	 *
	 * all further functionality should be added as behaviors
	 *
	 * @return void
	 */
	public function run()
	{
		// raise event
		$this->onBeforeRun();

		foreach($this->collection as $test)
		{
			// raise event
			$this->onBeforeTest($test);

			if ($test->run()) {
				echo '.';
			} else {
				echo 'E';
			}
			// raise event
			$this->onAfterTest($test);
		}

		// raise event
		$this->onAfterRun();
	}

}
