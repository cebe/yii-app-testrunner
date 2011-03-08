<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Tests
 */
abstract class TestAbstract extends CComponent
{
	protected $unitTest = null;

	private $_name = null;

	/**
	 * should return an array of scopes with criteria
	 *
	 * @abstract
	 * @return array
	 */
	abstract public function scopes();


	public function getName()
	{
		return $this->_name;
	}

	public function __construct($name, $unitTest=null)
	{
		$this->_name = $name;
		$this->unitTest = $unitTest;
	}

	/**
	 * Check if a Test matches a given scope
	 *
	 * @abstract
	 * @return boolean
	 */
	abstract public function matchesScope();

}
