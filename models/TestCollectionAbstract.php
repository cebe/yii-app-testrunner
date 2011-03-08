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

	public $scopeManager = null;

	/**
	 * current scope for filtering which tests are run
	 *
	 * @var string
	 */
	protected $scope = 'all';

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
	 *
	 */
	public function applyScope($scope)
	{
		if (is_string($scope) AND !is_null($this->scopeManager)) {
			$scope = $this->scopeManager->getScope($scope);
		}
		if (!($scope instanceof ScopeAbstract)) {
			throw new Exception('Scope class ' . get_class($scope) . ' does not extend ScopeAbstract.');
		}
		$this->scope = $scope;
	}

	/**
	 *
	 */
	public function __construct($scopeManager)
	{
		$this->scopeManager = $scopeManager;
		if (!($this->scopeManager instanceof ScopeManager)) {
			throw new Exception('ScopeManager is required by TestCollection but was not set.');
		}

		// set scope to all
		$this->scope = $this->scopeManager->getScope('all');

		if (is_null($this->scope)) {
			throw new Exception('ScopeAll is required by TestCollection but was not found.');
		}

		// reset the iterator position
	    $this->rewind();
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
        $this->_position = 0;
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
	    while(isset($this->tests[$this->_position]) AND
		      !$this->scope->matches($this->tests[$this->_position]))
	    {
		    ++$this->_position;
	    }
    }

    public function valid()
    {
        return (isset($this->tests[$this->_position]) AND
		        $this->scope->matches($this->tests[$this->_position]));
    }
}
