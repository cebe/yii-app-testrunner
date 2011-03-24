<?php

/**
 * The Scope to run only LiveTest tests
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Scopes
 */
class ScopeLiveTest extends ScopeAbstract
{
	/**
	 * scopes description
	 *
	 * @var string
	 */
	public $description = 'run only LiveTest tests';

	/**
	 * all tests match here
	 *
	 * @see ScopeAbstract::matches()
	 */
	public function matches($test)
	{
		return ($test instanceof TestLiveTest);
	}
}
