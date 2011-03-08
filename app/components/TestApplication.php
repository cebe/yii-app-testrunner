<?php

/**
 *
 */
class TestApplication extends CConsoleApplication
{
	public function __construct($config=null)
	{
		TestYii::setApplication($this);

		// set basePath at early as possible to avoid trouble
		if(is_string($config))
			$config=require($config);
		if(isset($config['basePath']))
		{
			$this->setBasePath($config['basePath']);
			unset($config['basePath']);
		}
		else
			$this->setBasePath('protected');
		TestYii::setPathOfAlias('application',$this->getBasePath());
		TestYii::setPathOfAlias('webroot',dirname($_SERVER['SCRIPT_FILENAME']));
		TestYii::setPathOfAlias('ext',$this->getBasePath().DIRECTORY_SEPARATOR.'extensions');

		$this->preinit();

		$this->initSystemHandlers();
		$this->registerCoreComponents();

		$this->configure($config);
		$this->attachBehaviors($this->behaviors);
		$this->preloadComponents();

		$this->init();
	}
}
