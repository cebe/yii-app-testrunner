<?php
/*
	This is an example config file for your yii console


*/

return array(
	'commandMap' => array(
		'tests'=>array(
			/*
			 * this is the command class of testrunner
			 * you have to change it if you extended TestrunnerCommand
			 */
			'class'=>'application.commands.testRunner.TestrunnerCommand',
			/*
			 * here you can define all properties of the testRunner even which class to use as testrunner
			 */
			'testRunner' => array(
				/*
				 * Yii alias of the class to use as testrunner
				 * if you give a name only, make shure it can be accessed without autoloading or
				 * already is accessable by include_path
				 */
				'class' => 'TestRunner',
				/*
				 * attach behaviors to testRunner class
				 */
				'behaviors'=>array(
					'phpunitStyle' => array(
						'class' => 'application.commands.testRunner.behaviors.TestOutputPhpUnitStyleBehavior',
					),

					'dependencies' => array(
						'class' => 'application.commands.testRunner.behaviors.TestDependencyBehavior',
						//'depends' => array('countryName'),
					),
					'codeCoverage' => array(
						'class' => 'application.commands.testRunner.behaviors.TestCodeCoverageBehavior',
						'cloverXml' => true,
						'cloverXmlPath' => null,
						'cloverXmlFile' => 'clover.xml',
						'coverageHtml' => true,
						'coverageHtmlPath' => null,
						'coverageHtmlConfig' => array(
							'title'          => 'Unit Test Code Coverage HTML',
							'charset'        => 'UTF-8',
							'yui'            => true,
							'highlight'      => false,
							'lowUpperBound'  => 35,
							'highLowerBound' => 70,
						),
					),
				),
			),
		),
	),
);
