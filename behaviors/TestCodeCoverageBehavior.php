<?php

/**
 * behavoir that handles test dependencies
 *
 * run tests in
 *
 * @package Behaviors
 */
class TestCodeCoverageBehavior extends TestRunnerBehaviorAbstract
{
	/**
	 * whether to report clover xml
	 *
	 * @var bool
	 */
	public $cloverXml = false;

	/**
	 * report path for clover xml
	 *
	 * defaults to null means report to default path
	 *
	 * @var string|null
	 */
	public $cloverXmlPath = null;

	/**
	 * file for clover xml
	 *
	 * @var bool
	 */
	public $cloverXmlFile = 'clover.xml';

	/**
	 * whether to report coverage html
	 *
	 * @var bool
	 */
	public $coverageHtml = false;

	/**
	 * whether to report coverage html
	 *
	 * @var array
	 */
	public $coverageHtmlConfig = array();

	/**
	 * report path for clover xml
	 *
	 * defaults to null means report to default path
	 *
	 * @var string|null
	 */
	public $coverageHtmlPath = null;

	/**
	 * Called before every single test run
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection and the test that will be run
	 * @return void
	 */
	public function beforeTest(TestRunnerEvent $event)
	{
		$event->currentTest->results->collectCodeCoverageInformation(true);
		//$event->currentTest->results->collectRawCodeCoverageInformation(true);
	}

	/**
	 * Called after every single test run
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection and the test that has been run
	 * @return void
	 */
	public function afterTest(TestRunnerEvent $event)
	{
		// @todo: implement remote code coverage here
	}

	/**
	 * Called after running a test sequence
	 *
	 * @param TestRunnerEvent the raised event holding the current testcollection
	 * @return void
	 */
	public function afterRun(TestRunnerEvent $event)
	{
		$coverage = PHP_CodeCoverage::getInstance();

		if (extension_loaded('tokenizer') && extension_loaded('xdebug'))
		{
		    if ($this->cloverXml)
		    {
			    $this->owner->command->p(
			      "\nWriting code coverage data to XML file, this may take a moment."
			    );

			    // get base path
			    $reportPath = $this->cloverXmlPath;
			    if (is_null($reportPath)) {
				    $reportPath = Yii::getPathOfAlias($this->owner->command->testPath) . '/report';
			    }
			    if (!file_exists($reportPath)) {
				    mkdir($reportPath, 0775, true);
			    }

			    // include report generator
		        require_once 'PHP/CodeCoverage/Report/Clover.php';

		        $writer = new PHP_CodeCoverage_Report_Clover;
		        $writer->process($coverage, $reportPath . DIRECTORY_SEPARATOR . $this->cloverXmlFile);

		        $this->owner->command->p("\n");
		        unset($writer);
		    }

		    if ($this->coverageHtml)
		    {
			    $this->owner->command->p(
			      "\nGenerating code coverage report, this may take a moment."
			    );

			    // get base path
			    $reportPath = $this->coverageHtmlPath;
			    if (is_null($reportPath)) {
				    $reportPath = Yii::getPathOfAlias($this->owner->command->testPath) . '/report/coverage';
			    }
			    if (!file_exists($reportPath)) {
				    mkdir($reportPath, 0775, true);
			    }

			    $this->coverageHtmlConfig = array_merge(
				    $this->coverageHtmlConfig,
					array(
						'title'          => 'Unit Test Code Coverage HTML',
						'charset'        => 'UTF-8',
						'yui'            => true,
						'highlight'      => false,
						'lowUpperBound'  => 35,
						'highLowerBound' => 70,
						'generator'      => ' and Yii app-testrunner using PHPUnit ' . PHPUnit_Runner_Version::id()
				    )
			    );

		        require_once 'PHP/CodeCoverage/Report/HTML.php';

		        $writer = new PHP_CodeCoverage_Report_HTML($this->coverageHtmlConfig);
		        $writer->process($coverage, $reportPath);

		        $this->owner->command->p("\n");
		        unset($writer);
		    }
		}






	}

}
