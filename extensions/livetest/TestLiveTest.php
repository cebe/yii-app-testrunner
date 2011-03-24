<?php

/**
 * @author Carsten Brandt <mail@cebe.cc>
 * @package Tests
 */
class TestLiveTest extends TestAbstract
{
	public $liveTestConfig = null;

	public $liveTestConfigFile = null;

	public $liveTestPath = null;

	/**
	 * will be called after construct
	 *
	 * @return void
	 */
	public function init()
	{
		if (is_null($this->liveTestPath)) {
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

		$fp = fopen($tmpFile, 'w');
		fwrite($fp, $this->liveTestConfig);
		fclose($fp);

		$command = $this->liveTestPath . ' --testsuite ' . addcslashes($tmpFile, ' !'); // todo: make shure to escape properly

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
}
