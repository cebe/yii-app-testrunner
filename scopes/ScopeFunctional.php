<?php

/**
 * The Scope to run only functional tests
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Scopes
 */
class ScopeFunctional extends ScopeAbstract
{
	/**
	 * scopes description
	 *
	 * @var string
	 */
	public $description = 'run only functional tests';

	/**
	 * all tests match here
	 *
	 * @see ScopeAbstract::matches()
	 */
	public function matches($test)
	{
		return ($test->testClass instanceof PHPUnit_Extensions_SeleniumTestCase);
	}
}
