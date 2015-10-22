<?php

namespace Openbuildings\Swiftmailer\Test;

use PHPUnit_Framework_TestCase;
use Openbuildings\Swiftmailer\IsEqual;

/**
 * @coversDefaultClass Openbuildings\Swiftmailer\IsEqual
 */
class IsEqualTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers ::__construct
	 * @covers ::getEmail
	 */
	public function testConstructor()
	{
		$isEqual = new IsEqual('test@example.com');

		$this->assertEquals(
			'test@example.com',
			$isEqual->getEmail(),
			'Should be the same value as set in constructor'
		);
	}

	/**
	 * @covers ::execute
	 */
	public function testExecute()
	{
		$isEqual = new IsEqual('test@example.com');

		$this->assertTrue($isEqual->execute('test@example.com'));
		$this->assertFalse($isEqual->execute('test2@example.com'));
	}
}
