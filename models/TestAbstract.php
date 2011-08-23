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
	 * @var string
	 */
	public $docBlock = '';


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
		$reflectionThis = new ReflectionClass($this);
		$this->docBlock .= $reflectionThis->getDocComment();
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
	 * create a new instance with name, testclass and method name
	 *
	 * @param  $name
	 */
	public function __construct($name)
	{
		$this->_name = $name;
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
	 * reset current state to "not run"
	 *
	 * @param string $exclude
	 * @return void
	 */
	protected function resetState($exclude='')
	{
		$exclude == 'error'      OR $this->_error = false;
		$exclude == 'failed'     OR $this->_failed = false;
		$exclude == 'skipped'    OR $this->_skipped = false;
		$exclude == 'incomplete' OR $this->_incomplete = false;
		$exclude == 'passed'     OR $this->_passed = false;
	}

	/**
	 * mark this test as "failed due to an error"
	 *
	 * @param string|boolean $message
	 * @return void
	 */
	public function markError($message = 'Test failed due to an error.')
	{
		$message = $this->onMarkError($message);
		$this->_error = ($message === false OR is_string($message)) ? $message : '';
	}

	/**
	 * Event that is raised when a test is marked "failed due to an error"
	 *
	 * message can be accessed over $event->message
	 *
	 * @return string
	 */
	public function onMarkError($message)
	{
		$event = new TestEvent($this, $message);
		$this->raiseEvent('onMarkError', $event);

		if ($event->resetState) {
			$this->resetState('error');
		}
		return $event->message;
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
		$message = $this->onMarkFailed($message);
		$this->_failed = ($message === false OR is_string($message)) ? $message : '';
	}

	/**
	 * Event that is raised when a test is marked "failed"
	 *
	 * message can be accessed over $event->message
	 *
	 * @return string
	 */
	public function onMarkFailed($message)
	{
		$event = new TestEvent($this, $message);
		$this->raiseEvent('onMarkFailed', $event);

		if ($event->resetState) {
			$this->resetState('failed');
		}

		return $event->message;
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
		$message = $this->onMarkSkipped($message);
		$this->_skipped = ($message === false OR is_string($message)) ? $message : '';
	}

	/**
	 * Event that is raised when a test is marked "skipped"
	 *
	 * message can be accessed over $event->message
	 *
	 * @return string
	 */
	public function onMarkSkipped($message)
	{
		$event = new TestEvent($this, $message);
		$this->raiseEvent('onMarkSkipped', $event);

		if ($event->resetState) {
			$this->resetState('skipped');
		}

		return $event->message;
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
	public function markIncomplete($message = 'Test is incomplete.')
	{
		$message = $this->onMarkIncomplete($message);
		$this->_incomplete = ($message === false OR is_string($message)) ? $message : '';
	}

	/**
	 * Event that is raised when a test is marked "incomplete"
	 *
	 * message can be accessed over $event->message
	 *
	 * @return string
	 */
	public function onMarkIncomplete($message)
	{
		$event = new TestEvent($this, $message);
		$this->raiseEvent('onMarkIncomplete', $event);

		if ($event->resetState) {
			$this->resetState('incomplete');
		}

		return $event->message;
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
		$result = $this->onMarkPassed(true) ? true : false;
		$this->_passed = $result;
	}

	/**
	 * Event that is raised when a test is marked "passed"
	 *
	 * passed value (true|false) can be accessed over $event->message
	 *
	 * @return string
	 */
	public function onMarkPassed($message)
	{
		$event = new TestEvent($this, $message);
		$this->raiseEvent('onMarkPassed', $event);

		if ($event->resetState) {
			$this->resetState('passed');
		}

		return $event->message;
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


/**
 * Events that are raised from TestAbstract
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Tests
 */
class TestEvent extends CEvent
{
	/**
	 * The message related to a Mark-Event or null on other events
	 *
	 * @var null|boolean|string
	 */
	public $message = null;

	/**
	 * whether to reset state after Mark-Event is handled
	 *
	 * defaults to true
	 *
	 * @var boolean
	 */
	public $resetState = true;

	/**
	 *
	 * @param TestAbstract $sender
	 * @param string       $message
	 */
	public function __construct(TestAbstract $sender, $message=null)
	{
		$this->message = $message;
		parent::__construct($sender);
	}
}

