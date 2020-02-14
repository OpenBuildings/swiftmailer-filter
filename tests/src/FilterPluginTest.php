<?php

namespace Openbuildings\Swiftmailer\Test;

use Openbuildings\Swiftmailer\FilterPlugin;
use Openbuildings\Swiftmailer\Filters\BlacklistFilter;
use Openbuildings\Swiftmailer\Filters\WhiteListFilter;
use PHPUnit\Framework\TestCase;
use Swift_Mailer;
use Swift_NullTransport;
use Swift_Message;
use Swift_Events_SendEvent;

/**
 * @coversDefaultClass \Openbuildings\Swiftmailer\FilterPlugin
 */
class FilterPluginTest extends TestCase
{
	/**
	 * @covers ::beforeSendPerformed
	 * @covers ::sendPerformed
	 */
	public function testIntegration()
	{
		$mailer = Swift_Mailer::newInstance(Swift_NullTransport::newInstance());

		$mailer->registerPLugin(new FilterPlugin([
			new WhiteListFilter(['example.com']),
			new BlacklistFilter(['test4@example.com'])
		]));

		$message = Swift_Message::newInstance();

		$message->setFrom('test@example.com');
		$message->setTo('test2@example.com');
		$message->setCc(['test2@example.com', 'test3@example-public.com']);
		$message->setBcc(['test4@example.com', 'test5@example-public.com']);
		$message->setSubject('Test');
		$message->setBody('Test Email');

		$mailer->send($message);

		$this->assertEquals(['test2@example.com' => null], $message->getTo());
		$this->assertEquals(['test2@example.com' => null], $message->getCc());
		$this->assertEquals([], $message->getBcc());
	}

	/**
	 * @covers ::beforeSendPerformed
	 * @covers ::sendPerformed
	 */
	public function testIntegrationWithEmptyCc()
	{
		$mailer = Swift_Mailer::newInstance(Swift_NullTransport::newInstance());

		$mailer->registerPLugin(new FilterPlugin([
			new WhiteListFilter(['example.com']),
			new BlacklistFilter(['test4@example.com'])
		]));

		$message = Swift_Message::newInstance();

		$message->setFrom('test@example.com');
		$message->setTo('test2@example.com');
		$message->setBcc(['test4@example.com', 'test5@example.com', 'test6@example-public.com']);
		$message->setSubject('Test');
		$message->setBody('Test Email');

		$mailer->send($message);

		$this->assertEquals(['test2@example.com' => ''], $message->getTo());
		$this->assertEquals([], $message->getCc());
		$this->assertEquals(['test5@example.com' => ''], $message->getBcc());
	}

	/**
	 * @covers ::beforeSendPerformed
	 * @covers ::sendPerformed
	 */
	public function testIntegrationWithEmptyCcAndBcc()
	{
		$mailer = Swift_Mailer::newInstance(Swift_NullTransport::newInstance());

		$listener = new TestListener();

		$mailer->registerPLugin($listener);
		$mailer->registerPLugin(new FilterPlugin([
			new WhiteListFilter(['example.com']),
			new BlacklistFilter(['test4@example.com'])
		]));

		$message = Swift_Message::newInstance();

		$message->setFrom('test@example.com');
		$message->setTo('test2@example.com');
		$message->setSubject('Test');
		$message->setBody('Test Email');

		$mailer->send($message);

		$this->assertEquals(['test2@example.com' => ''], $message->getTo());
		$this->assertEquals([], $message->getCc());
		$this->assertEquals([], $message->getBcc());

		$this->assertInstanceOf('Swift_Events_SendEvent', $listener->event());
		$this->assertEquals(Swift_Events_SendEvent::RESULT_SUCCESS, $listener->event()->getResult());
	}

	/**
	 * @covers ::beforeSendPerformed
	 * @covers ::sendPerformed
	 */
	public function testIntegrationFiltered()
	{
		$mailer = Swift_Mailer::newInstance(Swift_NullTransport::newInstance());
		$listener = new TestListener();

		$mailer->registerPLugin($listener);
		$mailer->registerPLugin(new FilterPlugin([
			new WhiteListFilter(['example.com']),
			new BlacklistFilter(['test2@example.com'])
		]));

		$message = Swift_Message::newInstance();

		$message->setFrom('test@example.com');
		$message->setTo('test2@example.com');
		$message->setSubject('Test');
		$message->setBody('Test Email');

		$mailer->send($message);

		$this->assertEquals([], $message->getTo());
		$this->assertEquals([], $message->getCc());
		$this->assertEquals([], $message->getBcc());

		$this->assertInstanceOf('Swift_Events_SendEvent', $listener->event());
		$this->assertEquals(Swift_Events_SendEvent::RESULT_PENDING, $listener->event()->getResult());
	}

	public function dataFilterEmailArray()
	{
		return [
			// Whitelist
			[
				['test@example.com'],
				[],
				['test@example.com' => ''],
				['test@example.com' => '']
			],
			[
				['example.com'],
				[],
				['test@example.com' => '', 'test2@example.com' => 'Test'],
				['test@example.com' => '', 'test2@example.com' => 'Test']
			],
			[
				['example.com'],
				[],
				['test@example.com' => '', 'test2@example2.com' => 'Test'],
				['test@example.com' => '']
			],
			[
				['example.com', 'test2@example2.com'],
				[],
				['test@example.com' => '', 'test3@example2.com' => '', 'test2@example2.com' => 'Test'],
				['test@example.com' => '', 'test2@example2.com' => 'Test']
			],
			[
				['test@example2.com'],
				[],
				['test@example.com' => ''],
				[]
			],

			// Blacklist
			[
				[],
				['test@example2.com'],
				['test@example.com' => ''],
				['test@example.com' => '']
			],
			[
				[],
				['test@example.com'],
				['test@example.com' => ''],
				[]
			],
			[
				[],
				['example.com'],
				['test@example.com' => ''],
				[]
			],
			[
				[],
				['example.com'],
				['test@example.com' => '', 'test2@example.com' => 'Test', 'test3@example2.com' => 'Test'],
				['test3@example2.com' => 'Test']
			],

			// Mixed
			[
				['test@example.com'],
				['test@example2.com'],
				['test@example.com' => ''],
				['test@example.com' => '']
			],
			[
				['example.com'],
				['test@example.com'],
				['test@example.com' => '', 'test2@example.com' => '', 'test3@example.com' => ''],
				['test2@example.com' => '', 'test3@example.com' => '']
			],
		];
	}

	/**
	 * @dataProvider dataFilterEmailArray
	 * @covers ::filterEmail
	 * @covers ::filterEmailArray
	 */
	public function testFilterEmailArray($whitelist, $blacklist, $array, $expected)
	{
		$filter = new FilterPlugin([
			new WhiteListFilter($whitelist),
			new BlacklistFilter($blacklist)
		]);

		$this->assertSame($expected, $filter->filterEmailArray($array));
	}
}
