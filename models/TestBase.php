<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Tests
 *
 */
class TestBase extends TestAbstract
{
	/**
	 * @var string
	 */
	public $docBlock = '';

	/**
	 * results
	 *
	 * @var PHPUnit_Framework_TestResult
	 */
	public $results = null;

	/**
	 * initialize the test object
	 *
	 * @return void
	 */
	public function init()
	{
		parent::init();

		$this->docBlock = $this->testMethod->getDocComment();
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
	 * return the name of this test
	 *
	 * @return string
	 */
	public function getName()
	{
		return get_class($this->testClass) . '::' . parent::getName();
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
		if ($this->getError() ||
			$this->getFailed() ||
			$this->getSkipped() ||
			$this->getIncomplete() ||
			$this->getPassed())
		{
			return $this->getPassed(); // @todo: think about senselessness
		}

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

		// evaluate results
		if (count($this->results->errors()) > 0)
		{
			$message = '';
			foreach($this->results->errors() as $error) {
				$message .= $error->getExceptionAsString() . "\n";
			}
			$this->markError(trim($message));
		}
		elseif (count($this->results->failures()) > 0)
		{
			$message = '';
			foreach($this->results->failures() as $failure) {
				$message .= $failure->getExceptionAsString() . "\n";
			}
			$this->markFailed(trim($message));
		}
		elseif (count($this->results->skipped()) > 0)
		{
			$message = '';
			foreach($this->results->skipped() as $skip) {
				$message .= $skip->getExceptionAsString() . "\n";
			}
			$this->markSkipped(trim($message));
		}
		elseif (count($this->results->notImplemented()) > 0)
		{
			$message = '';
			foreach($this->results->notImplemented() as $incomplete) {
				$message .= $incomplete->getExceptionAsString() . "\n";
			}
			$this->markIncomplete(trim($message));
		}
		elseif (count($this->results->passed()) > 0) {
			$this->markPassed();
		}
		else {
			throw new Exception('Unable to determine tests result of ' . $this->getName());
		}

		return $this->getPassed(); // @todo: think about senselessness
	}
}
