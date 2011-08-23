<?php

/**
 * This is the base class for a scope
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Scopes
 */
abstract class ScopeAbstract extends CComponent
{
	/**
	 * short description about the scopes criteria
	 *
	 * overwrite this in your concrete class
	 *
	 * @var string
	 */
	public $description = 'no description available';

	/**
	 * This function is to determine if a test matches this scope
	 *
	 * @param TestAbstract $test the test class to match
	 * @return boolean true if it matches, false if not
	 */
	abstract public function matches($test);
}
