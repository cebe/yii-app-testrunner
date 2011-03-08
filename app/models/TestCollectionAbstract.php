<?php

/**
 * The test collection represents a collection of tests
 *
 * @todo better description
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package TestCollector
 */
abstract class TestCollectionAbstract extends CComponent implements Iterator
{
	private $_position = 0;

	protected $tests = array();

	/**
	 * current scope for filtering which tests are run
	 *
	 * @var string
	 */
	protected $scope = null;

	/**
	 * You can set the scope by calling $object->scopename()
	 * set the first parameter true to return cloned object(to use both scopes)
	 *
	 * @param  $functionName
	 * @return
	 */
	public function __call($name,$parameters)
	{
		// handle scope
		if (Yii::app()->scopeHandler->scopeExists($name))
		{
			if (isset($parameters[0]) AND $parameters[0]) {
				$obj = clone $this;
			}
			$obj->scope = Yii::app()->scopeHandler->getScope($name);
			return $this;
		}

		return parent::__call($name, $parameters);
	}

	public function __construct()
	{
		// set scope to all
		$this->scope = new ScopeAll();

		// reset the iterator position
	    $this->rewind();
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
    }

    public function valid()
    {
        return isset($this->tests[$this->_position]);
    }
}
