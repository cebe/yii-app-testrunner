<?php

/**
 * The Scope for all existing Tests
 */
class ScopeAll extends ScopeAbstract
{
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
