<?php

/**
 * This class contains some helpers for displaying results on console
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestRunner
 */
class TestRunnerPrintHelper
{
	/**
	 * prints output for a list of tests
	 *
	 * @param  $type
	 * @param  $results
	 * @return void
	 */
	public static function listResults($type, $results)
	{
		$longest = 0;
		foreach($results as $test => $result)
		{
			if (($len = strlen($test)) > $longest) {
				$longest = $len;
			}
		}

		echo "\n\n$type: \n\n";
		foreach($results as $test => $result) {
			echo $test . ':' .
				 str_repeat(' ', $longest + 2 - strlen($test)) .
				 str_replace("\n", "\n" . str_repeat(' ', $longest + 3), $result)  . "\n";
		}
		echo "\n";
	}

}
