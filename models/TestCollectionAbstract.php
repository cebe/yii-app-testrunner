<?php

/**
 * The test collection represents a collection of tests
 *
 * @todo better description
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestCollector
 */
abstract class TestCollectionAbstract extends CComponent implements Iterator, Countable
{
	private $_position = 0;

	protected $tests = array();

	protected $includedTests = array();

	protected $excludedTests = array();

	/**
	 * current scope for filtering which tests are run
	 *
	 * @var string
	 */
	protected $scope = 'all';

	private $scopeCache = array();

	/**
	 * You can set the scope by calling $object->scopename()
	 * set the first parameter true to return cloned object(to use both scopes)
	 *
	 * @param  $functionName
	 * @return
	 * /
	public function __call($name,$parameters)
	{
		// handle scope
		if (Yii::app()->scopeHandler->isScopeAvailable($name))
		{
			if (isset($parameters[0]) AND $parameters[0]) {
				$obj = clone $this;
			}
			$obj->scope = Yii::app()->scopeHandler->getScope($name);
			return $this;
		}

		return parent::__call($name, $parameters);
	}
	*/

	public function addTest($test)
	{
		$this->tests[] = $test;
	}

	/**
	 * with this function you can explicitly enable tests (ignoring scope)
	 *
	 * @param  $name
	 * @return void
	 */
	public function includeTest($name)
	{
		if (isset($this->excludedTests[$name])) {
			unset($this->excludedTests[$name]);
		}
		$this->includedTests[$name] = $name;
	}

	/**
	 * with this function you can explicitly disable tests
	 *
	 * @param  $name
	 * @return void
	 */
	public function excludeTest($name)
	{
		if (isset($this->includedTests[$name])) {
			unset($this->includedTests[$name]);
		}
		$this->excludedTests[$name] = $name;
	}

	/**
	 *
	 */
	public function applyScope($scope)
	{
		$scope = ScopeManager::getInstance()->getScope($scope);

		if (!($scope instanceof ScopeAbstract)) {
			throw new Exception('Scope class ' . get_class($scope) . ' does not extend ScopeAbstract.');
		}
		$this->scope = $scope;
		$this->clearScopeCache();
	}

	protected function clearScopeCache()
	{
		$this->scopeCache = array();
	}

	/**
	 * check if a tests matches current scope
	 *
	 * @param TestAbstract $test
	 * @return bool
	 */
	protected function matchesScope(TestAbstract $test)
	{
		$name = $test->name;
		if (!isset($this->scopeCache[$name])) {
			$this->scopeCache[$name] = $this->scope->matches($test);
		}

		return $this->scopeCache[$name];
	}

	/**
	 * Reorder tests by using the function lowerEqual to get the right order
	 *
	 * @param callback $lowerEqual function(TestAbstract $testA, TestAbstract $testB) { return true|false; }
	 * @return void
	 */
	abstract public function orderTests($lowerEqual);

	/**
	 *
	 */
	public function __construct()
	{
		// set scope to all
		$this->scope = ScopeManager::getInstance()->getScope('all');

		if (is_null($this->scope)) {
			throw new Exception('ScopeAll is required by TestCollection but was not found.');
		}

		// reset the iterator position
	    $this->rewind();
	}

	/**
	 * check if a Test is included in this collection
	 *
	 * @param TestAbstract $test
	 * @return bool
	 */
	public function isIncluded(TestAbstract $test)
	{
		if (isset($this->excludedTests[$test->name])) {
			return false;
		}
		if (isset($this->includedTests[$test->name])) {
			return true;
		}
		return $this->matchesScope($test);
	}

	/**
	 * countable functionality
	 *
	 * @return int
	 */
	public function count()
	{
		$count = 0;
		foreach($this as $test) {
			++$count;
		}
		return $count;
	}

	/**
	 * iterator functionallity
	 *
	 * @return void
	 */
    public function rewind()
    {
	    // walk to first valid item
        $this->_position = -1;
	    $this->next();
    }

    public function current()
    {
        return $this->tests[$this->_position];
    }

    public function key()
    {
        return $this->_position;
    }

    public function next()
    {
	    ++$this->_position;
	    // skip tests that do not match scope
	    if (isset($this->tests[$this->_position]) AND
		    !$this->isIncluded($this->tests[$this->_position]))
	    {
		    $this->next();
	    }
    }

    public function valid()
    {
        return (isset($this->tests[$this->_position]) AND
		        $this->isIncluded($this->tests[$this->_position]));
    }
}
