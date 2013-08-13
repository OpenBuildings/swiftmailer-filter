<?php

use Openbuildings\Swiftmailer\FilterPlugin;

/**
 * @group   filter-plugin
 */
class FilterPluginTest extends PHPUnit_Framework_TestCase {

	public function test_integration()
	{
		$mailer = Swift_Mailer::newInstance(Swift_NullTransport::newInstance());

		$mailer->registerPLugin(new FilterPlugin('example.com', 'test4@example.com'));

		$message = Swift_Message::newInstance();

		$message->setFrom('test@example.com');
		$message->setTo('test2@example.com');
		$message->setCc(array('test2@example.com', 'test3@example-public.com'));
		$message->setBcc(array('test4@example.com', 'test5@example-public.com'));
		$message->setSubject('Test');
		$message->setBody('Test Email');

		$mailer->send($message);

		$this->assertEquals(array('test2@example.com' => ''), $message->getTo());
		$this->assertEquals(array('test2@example.com' => ''), $message->getCc());
		$this->assertEquals(array(), $message->getBcc());
	}

	public function data_emailMatches()
	{
		return array(
			array('test@example.com', 'test@example.com', TRUE, NULL),
			array('test@example.com', 'test2@example.com', FALSE, NULL),
			array('testexample.com', 'test2@example.com', FALSE, "Cannot match with 'test2@example.com': 'testexample.com' is not a valid email"),
			array('test@example.com', 'example.com', TRUE, NULL),
			array('test2@example.com', 'example.com', TRUE, NULL),
			array('test@not-example.com', 'example.com', FALSE, NULL),
		);
	}

	/**
	 * @dataProvider data_emailMatches
	 * @covers Openbuildings\Swiftmailer\FilterPlugin::emailMatches
	 */
	public function test_emailMatches($email, $match, $expected, $exception)
	{
		if ($exception) 
		{
			$this->setExpectedException('Exception', $exception);
			FilterPlugin::emailMatches($email, $match);
		}
		else
		{
			$this->assertEquals($expected, FilterPlugin::emailMatches($email, $match));
		}
	}

	public function data_emailMatchesArray()
	{
		return array(
			array('test@example.com', array('test@example.com'), TRUE),
			array('test@example.com', array('test2@example.com'), FALSE),
			array('test@example.com', array('example.com'), TRUE),
			array('test2@example.com', array('example.com'), TRUE),
			array('test@not-example.com', array('example.com'), FALSE),
			array('test@not-example.com', array('example.com', 'test@not-example.com'), TRUE),
			array('test@not-example.com', array('example.com', 'not-example.com'), TRUE),
		);
	}

	/**
	 * @dataProvider data_emailMatchesArray
	 * @covers Openbuildings\Swiftmailer\FilterPlugin::emailMatchesArray
	 */
	public function test_emailMatchesArray($email, $match, $expected)
	{
		$this->assertEquals($expected, FilterPlugin::emailMatchesArray($email, $match));
	}

	public function test_construct_getters_and_setters()
	{
		$filter = new FilterPlugin(array('whitelist@example.com', 'whitelist.example.com'), 'blacklist@example.com');

		$this->assertEquals(array('whitelist@example.com', 'whitelist.example.com'), $filter->getWhitelist());
		$this->assertEquals(array('blacklist@example.com'), $filter->getBlacklist());

		$filter->setWhitelist('test@example.com');

		$this->assertEquals(array('test@example.com'), $filter->getWhitelist());

		$filter->setBlacklist(array('test2@example.com'));

		$this->assertEquals(array('test2@example.com'), $filter->getBlacklist());
	}

	public function data_filterEmailArray()
	{
		return array(
			// Whitelist
			array(array('test@example.com'), array(), array('test@example.com' => ''), array('test@example.com' => '')),
			array(array('example.com'), array(), array('test@example.com' => '', 'test2@example.com' => 'Test'), array('test@example.com' => '', 'test2@example.com' => 'Test')),
			array(array('example.com'), array(), array('test@example.com' => '', 'test2@example2.com' => 'Test'), array('test@example.com' => '')),
			array(array('example.com', 'test2@example2.com'), array(), array('test@example.com' => '', 'test3@example2.com' => '', 'test2@example2.com' => 'Test'), array('test@example.com' => '', 'test2@example2.com' => 'Test')),
			array(array('test@example2.com'), array(), array('test@example.com' => ''), array()),

			// Blacklist
			array(array(), array('test@example2.com'), array('test@example.com' => ''), array('test@example.com' => '')),
			array(array(), array('test@example.com'), array('test@example.com' => ''), array()),
			array(array(), array('example.com'), array('test@example.com' => ''), array()),
			array(array(), array('example.com'), array('test@example.com' => '', 'test2@example.com' => 'Test', 'test3@example2.com' => 'Test'), array('test3@example2.com' => 'Test')),

			// Mixed
			array(array('test@example.com'), array('test@example2.com'), array('test@example.com' => ''), array('test@example.com' => '')),
			array(array('example.com'), array('test@example.com'), array('test@example.com' => '', 'test2@example.com' => '', 'test3@example.com' => ''), array('test2@example.com' => '', 'test3@example.com' => '')),
		);
	}

	/**
	 * @dataProvider data_filterEmailArray
	 * @covers Openbuildings\Swiftmailer\FilterPlugin::filterEmailArray
	 */
	public function test_filterEmailArray($whitelist, $blacklist, $array, $expected)
	{
		$this->assertEquals($expected, FilterPlugin::filterEmailArray($whitelist, $blacklist, $array));
	}
}