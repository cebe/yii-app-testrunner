<?php

/**
 * The Scope to run only unit tests
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Scopes
 */
class ScopeUnit extends ScopeAbstract
{
	/**
	 * scopes description
	 *
	 * @var string
	 */
	public $description = 'run only unit tests';

	/**
	 * all tests match here
	 *
	 * @see ScopeAbstract::matches()
	 */
	public function matches($test)
	{
		return ($test->testClass instanceof PHPUnit_Framework_TestCase);
	}
}
