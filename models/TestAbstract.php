<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Tests
 */
abstract class TestAbstract extends CComponent
{
	protected $testClass = null;

	protected $reflectionClass = null;

	protected $testMethod = null;

	protected $docBlock = '';


	private $_name = null;

	/**
	 * should return an array of scopes with criteria
	 *
	 * @abstract
	 * @return array
	 */
	//abstract public function scopes();


	public function getName()
	{
		return $this->_name;
	}

	public function __construct($name, $testClass, $testMethod)
	{
		$this->_name = $name;
		$this->testClass = $testClass;
		$this->reflectionClass = new ReflectionClass($testClass);
		$this->testMethod = $this->reflectionClass->getMethod($testMethod);
		$this->docBlock = $this->testMethod->getDocComment();
	}

	/**
	 * Check if a Test matches a given scope
	 *
	 * @abstract
	 * @return boolean
	 */
	//abstract public function matchesScope();

}
