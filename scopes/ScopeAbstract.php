<?php

/**
 * @todo
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
	 *
	 *
	 * @abstract
	 * @param  $test
	 * @return boolean
	 */
	abstract public function matches($test);
}
