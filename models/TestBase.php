<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Tests
 *
 * @property-read PHPUnit_Framework_TestResult results
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
	private $_results = null;

	/**
	 *
	 * @return void
	 */
	public function getResults()
	{
		if (is_null($this->_results)) {
			$this->_results = new PHPUnit_Framework_TestResult(null);
		}

		return $this->_results;
	}

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
		$this->setAttribute('time', 0);
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

				if (in_array($key, array('error', 'failed', 'skipped', 'incomplete'))) {
					$function = 'mark' . ucfirst($key);
					$this->$function($value);
				}

				if (isset($this->attributes[$key]) AND !is_array($this->attributes[$key])) {
					$this->attributes[$key] = array($this->attributes[$key]);
				}
				if(isset($this->attributes[$key]) AND is_array($this->attributes[$key])) {
					$this->attributes[$key][] = trim($value);
				} else {
					$this->attributes[$key] = trim($value);
				}
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

		//$this->testClass->setDependencyInput(array('bla'));
		// will call testMethod with arguments: array_merge($this->data, $this->dependencyInput)
		// setDependencyInput
		// data and dataName are set to array() on testCase object creation
		$this->testClass->run($this->results);

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

		$this->setAttribute('time', $this->results->time());

		return $this->getPassed(); // @todo: think about senselessness
	}
}
