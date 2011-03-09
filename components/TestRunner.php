<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestRunner
 */
class TestRunner extends CComponent
{

	public function __construct()
	{

	}


	public function prepareRunning()
	{

	}

	public function run($collection)
	{
		$this->prepareRunning();

		foreach($collection as $test)
		{
			try {
				$test->run();
				echo '.';
			} catch (Exception $e) {
				echo 'E';
			}
		}

		$this->afterRunning();
	}

	public function afterRunning()
	{

	}
}
