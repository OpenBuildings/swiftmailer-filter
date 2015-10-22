<?php

namespace Openbuildings\Swiftmailer\Test;

use PHPUnit_Framework_TestCase;
use Openbuildings\Swiftmailer\IsDomainEqual;

/**
 * @coversDefaultClass Openbuildings\Swiftmailer\IsDomainEqual
 */
class IsDomainEqualTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers ::__construct
	 * @covers ::getDomain
	 */
	public function testConstructor()
	{
		$isDomainEqual = new IsDomainEqual('example.com');

		$this->assertEquals(
			'example.com',
			$isDomainEqual->getDomain(),
			'Should be the same value as set in constructor'
		);
	}

	/**
	 * @covers ::execute
	 */
	public function testExecute()
	{
		$isDomainEqual = new IsDomainEqual('example.com');

		$this->assertTrue($isDomainEqual->execute('test@example.com'));
		$this->assertTrue($isDomainEqual->execute('test2@example.com'));
		$this->assertFalse($isDomainEqual->execute('test@other.example.com'));
	}
}
