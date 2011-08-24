<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package extensions.livetest
 */
class TestLiveTest extends TestAbstract
{
    /**
     * @var string the livetest config to use for this test
     */
	public $liveTestConfig = null;

    /**
     * @var string filename of the livetest config-file to use for this test
     * $liveTestConfig must be null if this is set
     */
	public $liveTestConfigFile = null;

    /**
     * @var string filename of the livetest config-file to use for this test
     * $liveTestConfig must be null if this is set
     */
	public $liveTestPath = null;

	public $baseUrl = '';

	/**
	 * will be called after construct
	 *
	 * @return void
	 */
	public function init()
	{
		if (is_null($this->liveTestPath)) {
            // @todo: fix this to work on every system
			$this->liveTestPath =
				dirname(dirname(dirname(__FILE__))) .
				DIRECTORY_SEPARATOR . 'vendors' .
				DIRECTORY_SEPARATOR . 'livetest';
			// on mac
			$this->liveTestPath = 'php ' . $this->liveTestPath .
				DIRECTORY_SEPARATOR . 'src' .
				DIRECTORY_SEPARATOR . 'runner.php';

			// other systems
/*			$this->liveTestPath .=
				DIRECTORY_SEPARATOR . 'bin' .
				DIRECTORY_SEPARATOR . 'LiveTest';*/
		}
		parent::init();
	}


	public function run()
	{
		$tmpPath = Yii::app()->runtimePath . DIRECTORY_SEPARATOR . 'livetest';

		if (!file_exists($tmpPath)) {
			mkdir($tmpPath, 0775, true);
		}

		$tmpFile = $tmpPath . DIRECTORY_SEPARATOR . $this->getName() . '.yml';

		if (is_null($this->liveTestConfig)) {
			if (is_null($this->liveTestConfigFile)) {
				$this->markIncomplete('LiveTestConfig is not specified');
				return;
			}
			if (!is_file($this->liveTestConfigFile)) {
				$this->markError('LiveTestConfigFile does not exist: ' . $this->liveTestConfigFile);
				return;
			}
			$this->liveTestConfig = file_get_contents($this->liveTestConfigFile);
		}

		$this->prepareConfig();

		$fp = fopen($tmpFile, 'w');
		fwrite($fp, $this->liveTestConfig);
		fclose($fp);

		$command = $this->liveTestPath /*. ' --feedback'*/ . ' --testsuite ' . addcslashes($tmpFile, ' !'); // todo: make shure to escape properly

		$returnVar = 0;
		passthru($command, $returnVar);

		// todo: better evaluation
		if ($returnVar > 0) {
			$this->markFailed('status from LiveTest: failed.');
		} else {
			$this->markPassed();
		}


		unlink($tmpFile);
	}

	public function prepareConfig()
	{
		$this->liveTestConfig = str_replace(
			'{$baseUrl}', $this->baseUrl,
			$this->liveTestConfig
		);


	}
}
