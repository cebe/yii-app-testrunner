<?php

/**
 * This Test Class represents a Single Test Method
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Tests
 *
 * @property-read string|boolean name
 * @property-read boolean        error             true if error occured
 * @property-read string|boolean errorMessage      the error Message
 * @property-read boolean        failed            true if failure occured
 * @property-read string|boolean failureMessage    the failure Message
 * @property-read boolean        skipped           true if test is skipped
 * @property-read string|boolean skippedMessage    the skipped Message
 * @property-read boolean        incomplete        true if test is marked as incomplete
 * @property-read string|boolean incompleteMessage the incomplete Message
 * @property-read boolean        passed            true if test passed
 */
abstract class TestAbstract extends CComponent
{
	/**
	 * @var null|PHPUnit_Framework_TestCase
	 */
	public $testClass = null;

	/**
	 * @var null|ReflectionClass
	 */
	public $reflectionClass = null;

	/**
	 * @var null|ReflectionMethod
	 */
	public $testMethod = null;


	/**
	 * @var string
	 */
	private $_name = '';


	/**
	 * @var bool|string
	 */
	private $_error = false;

	/**
	 * @var bool|string
	 */
	private $_failed = false;

	/**
	 * @var bool|string
	 */
	private $_skipped = false;

	/**
	 * @var bool|string
	 */
	private $_incomplete = false;

	/**
	 * @var bool
	 */
	private $_passed = false;


	/**
	 * list of attributes assigned to this test
	 *
	 * @var array
	 */
	protected $attributes = array();


	/**
	 * return the name of this test
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}


	/**
	 * will be called after construct
	 *
	 * overide this in your concrete class to do initializing stuff
	 *
	 * @return void
	 */
	public function init()
	{

	}

	/**
	 * create a new instance with name, testclass and method name
	 *
	 * @param  $name
	 * @param  $testClass
	 * @param  $testMethod
	 */
	public function __construct($name, $testClass, $testMethod)
	{
		$this->_name = $name;
		$this->testClass = $testClass;
		$this->reflectionClass = new ReflectionClass($testClass);
		$this->testMethod = $this->reflectionClass->getMethod($testMethod);

		$this->init();
	}


	/**
	 * get the value of an attribute or call corresponding getter method
	 *
	 * @return mixed
	 */
	public function __get($name)
	{
		if (isset($this->attributes[$name])) {
			return $this->attributes[$name];
		}

		return parent::__get($name);
	}

	/**
	 * check whether an attribute or getter Method is existing
	 *
	 * @return boolean
	 */
	public function __isset($name)
	{
		return $this->hasAttribute($name) OR parent::__isset($name);
	}

	/**
	 * set the value of an (already existing) attribute or call corresponding setter method
	 *
	 * @return void
	 */
	public function __set($name, $value)
	{
		if (isset($this->attributes[$name])) {
			$this->attributes[$name] = $value;
		} else {
			parent::__set($name, $value);
		}
	}

	/**
	 * add a new attribute, if it does not exist and
	 * sets the value of this attribute
	 *
	 * @return void
	 */
	public function setAttribute($name, $value)
	{
		$this->attributes[$name] = $value;
	}

	/**
	 * check whether this test has an attribute set
	 *
	 * @return boolean
	 */
	public function hasAttribute($name)
	{
		return isset($this->attributes[$name]);
	}


	/**
	 * run the test
	 *
	 * returns true on success, false on failure or error
	 *
	 * @return bool
	 */
	abstract public function run();


	/**
	 * mark this test as "failed due to an error"
	 *
	 * @param string|boolean $message
	 * @return void
	 */
	public function markError($message = 'Test failed due to an error.')
	{
		$this->_error = ($message === false OR is_string($message)) ? $message : '';
	}

	/**
	 * true if test failed due to an error
	 *
	 * @return boolean
	 */
	public function getError()
	{
		return ($this->_error !== false);
	}

	/**
	 * returns the error message if tests failed due to an error
	 *
	 * returns false, if test did not fail due to an error
	 *
	 * @return string|boolean
	 */
	public function getErrorMessage()
	{
		return $this->_error;
	}


	/**
	 * mark this test as "failed"
	 *
	 * @param string|boolean $message
	 * @return void
	 */
	public function markFailed($message = 'Test failed.')
	{
		$this->_failed = ($message === false OR is_string($message)) ? $message : '';
	}

	/**
	 * true if test failed
	 *
	 * @return boolean
	 */
	public function getFailed()
	{
		return ($this->_failed !== false);
	}

	/**
	 * returns the failure message if tests failed
	 *
	 * returns false, if test did not fail
	 *
	 * @return string|boolean
	 */
	public function getFailureMessage()
	{
		return $this->_failed;
	}


	/**
	 * mark test as "skipped"
	 *
	 * @param string|boolean $message
	 * @return void
	 */
	public function markSkipped($message = 'Test has been skipped.')
	{
		$this->_skipped = ($message === false OR is_string($message)) ? $message : '';
	}

	/**
	 * true if test skipped
	 *
	 * @return boolean
	 */
	public function getSkipped()
	{
		return ($this->_skipped !== false);
	}

	/**
	 * returns the skipped message if tests skipped
	 *
	 * returns false, if test has not been skipped
	 *
	 * @return string|boolean
	 */
	public function getSkippedMessage()
	{
		return $this->_skipped;
	}


	/**
	 * mark test as "incomplete"
	 *
	 * @param string|boolean $message
	 * @return void
	 */
	public function markIncomplete($message = 'Test has been skipped.')
	{
		$this->_incomplete = ($message === false OR is_string($message)) ? $message : '';
	}

	/**
	 * true if test is incomplete
	 *
	 * @return boolean
	 */
	public function getIncomplete()
	{
		return ($this->_incomplete !== false);
	}

	/**
	 * returns the incomplete message if test is incomplete
	 *
	 * returns false, if test is not incomplete
	 *
	 * @return string|boolean
	 */
	public function getIncompleteMessage()
	{
		return $this->_incomplete;
	}


	/**
	 * mark this test as "passed"
	 *
	 * @return void
	 */
	public function markPassed()
	{
		$this->_passed = true;
	}

	/**
	 * true if test passed
	 *
	 * @return boolean
	 */
	public function getPassed()
	{
		return ($this->_passed !== false);
	}
}
