<?php

/**
 * The Scope for all existing Tests
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Scopes
 */
class ScopeAll extends ScopeAbstract
{
	/**
	 * scopes description
	 *
	 * @var string
	 */
	public $description = 'all available tests';

	/**
	 * all tests match here
	 *
	 * @see ScopeAbstract::matches()
	 */
	public function matches($test)
	{
		return true;
	}
}
