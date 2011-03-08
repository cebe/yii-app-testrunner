<?php

/**
 * @todo
 *
 * @author Carsten Brandt <mail@cebe.cc>
 */
abstract class ScopeAbstract extends CComponent
{
	public $description = 'all available tests';

	/**
	 *
	 *
	 * @abstract
	 * @param  $test
	 * @return boolean
	 */
	abstract public function matches($test);
}
