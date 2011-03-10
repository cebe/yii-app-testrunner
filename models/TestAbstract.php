<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Tests
 */
abstract class TestAbstract extends CComponent
{
	public $testClass = null;

	public $reflectionClass = null;

	public $testMethod = null;

	public $docBlock = '';

	private $_name = '';

	/**
	 * will be called after construct
	 *
	 * @return void
	 */
	public function init()
	{

	}

	/**
	 * return the name of this test
	 *
	 * @return string
	 */
	public function getName()
	{
		return get_class($this->testClass) . '::' . $this->_name;
	}

	public function __construct($name, $testClass, $testMethod)
	{
		$this->_name = $name;
		$this->testClass = $testClass;
		$this->reflectionClass = new ReflectionClass($testClass);
		$this->testMethod = $this->reflectionClass->getMethod($testMethod);
		$this->docBlock = $this->testMethod->getDocComment();

		$this->init();
	}

	abstract public function run();
}
