<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Tests
 *
 * @property-read boolean error true if error occured
 * @property-read string  errorMessage
 * @property-read boolean failure true if failure occured
 * @property-read string  failureMessage
 * @property-read boolean passed true if test passed
 */
class TestBase extends TestAbstract
{
	/**
	 * list of attributes
	 *
	 * these attributes will be fetched from docbock annotations
	 *
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * results
	 *
	 * @var PHPUnit_Framework_TestResult
	 */
	public $results = null;

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
	 * initialize the test object
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		$this->parseDocBlock();
	}

	/**
	 * adds all values of docblock comments starting with @ to $this->attributes
	 *
	 * @return void
	 */
	protected function parseDocBlock()
	{
		$lines = explode("\n", $this->docBlock);
		foreach($lines as $line)
		{
			$line = ltrim($line, " \t\n\r\0\x0B*");
			if (!empty($line) AND $line{0} == '@') {
				$key = substr($line, 1, strpos($line, ' ') - 1);
				$value = substr($line, strpos($line, ' ') + 1);
				$this->attributes[$key] = $value;
			}
		}
	}

	/**
	 * run the test
	 *
	 * returns true on success, false on failure or error
	 *
	 * @return bool
	 */
	public function run()
	{
		// dependencies are handled by own behavior
		$this->testClass->setDependencies(array());

		// @todo: implement code coverage here
		$codeCoverage = null;

		$result = new PHPUnit_Framework_TestResult($codeCoverage);

		$this->testClass->setDependencyInput(array('bla'));
		// will call testMethod with arguments: array_merge($this->data, $this->dependencyInput)
		// setDependencyInput
		// data and dataName are set to array() on testCase object creation
		$this->testClass->run($result);

		$this->results = $result;

		return $result->wasSuccessful();
	}

	/**
	 * true if test failed due to an error
	 *
	 * @return boolean
	 */
	public function getError()
	{
		return (count($this->results->errors()) > 0);
	}

	/**
	 * returns the error message if tests failed due to an error
	 *
	 * @return string
	 */
	public function getErrorMessage()
	{
		$message = '';
		foreach($this->results->errors() as $error) {
			$message .= $error->getExceptionAsString() . "\n";
		}
		return $message;
	}

	/**
	 * true if test failed
	 *
	 * @return boolean
	 */
	public function getFailed()
	{
		return (count($this->results->failures()) > 0);
	}

	/**
	 * returns the failure message if tests failed
	 *
	 * @return string
	 */
	public function getFailureMessage()
	{
		$message = '';
		foreach($this->results->failures() as $failure) {
			$message .= $failure->getExceptionAsString() . "\n";
		}
		return $message;
	}

	/**
	 * true if test passed
	 *
	 * @return boolean
	 */
	public function getPassed()
	{
		return !$this->getError() && !$this->getFailed();
	}
}
