<?php

/**
 * The scope manager is the Yii-Application Component that manages test scopes
 *
 * It lists available scopes, creates scope classes etc...
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Scopes
 */
class ScopeManager extends CComponent
{
	/**
	 * the singleton instance of ScopeManager
	 *
	 * @var null|ScopeManager
	 */
	static private $_instance = null;

	/**
	 * get the singleton instance of ScopeManager
	 *
	 * @static
	 * @return ScopeManager
	 */
	static public function getInstance($config = null)
	{
		if (null === static::$_instance)
		{
			throw new CException('Instance of ScopeManager can only be created once!');
		}

		return static::$_instance;
	}

	/**
	 * creates and configure the singleton instance of ScopeManager
	 *
	 * @static
	 */
	static public function setInstance($config)
	{
		if (null === static::$_instance) {
			static::$_instance = new ScopeManager();
			static::$_instance->configure($config);
		} else {
			throw new CException('Instance of ScopeManager can only be created once!');
		}
	}

	private function __construct(){}
	private function __clone(){}


	/**
	 * list of paths to search for scope classes
	 *
	 * default path is application.models.scopes
	 * priority is: top value will be searched first
	 *
	 * @var array
	 */
	public $scopePath = array('application.models.scopes');


	public function init()
	{
		// nothing
	}

	/**
	 * Configures the class with the specified configuration.
	 * @param array $config the configuration array
	 */
	public function configure($config)
	{
		if (is_array($config))
		{
			foreach($config as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * a list of all available scopes
	 *
	 * @return void
	 */
	public function listScopes()
	{
		// list scopes
		$list = array();
		foreach ($this->scopePath as $alias)
		{
			$path = Yii::getPathOfAlias($alias) . '/Scope';
			$scopes = glob($path . '*.php');
			foreach($scopes as $scope)
			{
				$name = lcfirst(substr($scope, strlen($path), -4));
				$list[$name] = $alias;
			}
		}

		// add description
		foreach($list as $name => $alias)
		{
			Yii::import($alias . '.*');
			$list[$name] = $this->getScope($name)->description;
		}

		Yii::trace(print_r($list,1));
		return $list;
	}

	/**
	 * return a scope class
	 *
	 * @return ScopeAbstract
	 */
	public function getScope($name)
	{
		// return class if it's already instantiated
		if ($name instanceof ScopeAbstract) {
			return $name;
		}

		$className = 'Scope' . ucfirst($name);
		foreach ($this->scopePath as $alias)
		{
			$path = Yii::getPathOfAlias($alias);
			if (file_exists($path . '/' . $className . '.php')) {
				Yii::import($alias . '.*');
				return new $className;
			}
		}
		return null;
	}

	/**
	 * check if a scope is available
	 *
	 * @return boolean
	 */
	public function isScopeAvailable($name)
	{
		return ($this->getScope($name) !== null);
	}

}
