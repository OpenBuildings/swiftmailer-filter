<?php

namespace Openbuildings\Swiftmailer\Test;

use PHPUnit_Framework_TestCase;
use Openbuildings\Swiftmailer\Matches;
use Openbuildings\Swiftmailer\IsEqual;
use Openbuildings\Swiftmailer\IsDomainEqual;

/**
 * @coversDefaultClass Openbuildings\Swiftmailer\Matches
 */
class MatchesTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers ::__construct
	 * @covers ::getMatch
	 * @covers ::getMatches
	 */
	public function testConstructor()
	{
		$isEqual = new Matches(['test@example.com', 'example.com', 'other.example.com']);

		$this->assertEquals(
			[
				new IsEqual('test@example.com'),
				new IsDomainEqual('example.com'),
				new IsDomainEqual('other.example.com'),
			],
			$isEqual->getMatches(),
			'Should load the appropriate matchers'
		);
	}

	public function dataEquals()
	{
		return [
			// With correct email
			[
				['test@example.com'],
				'test@example.com',
				true,
			],

			// With wrong domain
			[
				['testexample.com'],
				'test2@example.com',
				false,
			],

			// With correct domain
			[
				['example.com'],
				'test@example.com',
				true,
			],

			// With wrong domain
			[
				['other.example.com'],
				'test@example.com',
				false,
			],

			// With no matchers
			[
				[],
				'test@example.com',
				false,
			],

			// With email and domain, correct domain
			[
				['test@other.example.com', 'example.com'],
				'test@example.com',
				true,
			],

			// With email and domain, correct email
			[
				['test@example.com', 'other.example.com'],
				'test@example.com',
				true,
			],

			// With email and domain, both correct
			[
				['test@example.com', 'example.com'],
				'test@example.com',
				true,
			],

			// With email and domain, no correct
			[
				['test@other.example.com', 'other.example.com'],
				'test@example.com',
				false,
			],
		];
	}

	/**
	 * @dataProvider dataEquals
	 * @covers ::equals
	 */
	public function testEquals($matchers, $email, $expected)
	{
		$matches = new Matches($matchers);

		$this->assertSame($expected, $matches->equals($email));
	}
}
