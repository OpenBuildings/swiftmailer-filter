<?php

namespace Openbuildings\Swiftmailer;

use Swift_Events_SendListener;
use Swift_Events_SendEvent;
use InvalidArgumentException;

/**
 * @author     Ivan Kerin <ikerin@gmail.com>
 * @copyright  (c) 2015 Clippings Ltd.
 * @license    http://spdx.org/licenses/BSD-3-Clause
 */
class Matches
{
	const TRUE_EMPTY = true;
	const FALSE_EMPTY = false;

	/**
	 * @var MatchInterface[]
	 */
	private $matches;

	/**
	 * @var boolean
	 */
	private $emptyValue;

	/**
	 * @param array $list
	 */
	public function __construct(array $list, $emptyValue = false)
	{
		$this->matches = array_map([$this, 'getMatch'], $list);
		$this->emptyValue = $emptyValue;
	}

	/**
	 * @return MatchInterface[]
	 */
	public function getMatches()
	{
		return $this->matches;
	}

	/**
	 * @param  string $item
	 * @return MatchInterface
	 */
	public function getMatch($item)
	{
		return filter_var($item, FILTER_VALIDATE_EMAIL)
			? new IsEqual($item)
			: new IsDomainEqual($item);
	}

	/**
	 * @param  string $email
	 * @return boolean
	 */
	public function equals($email)
	{
		if (empty($this->matches)) {
			return $this->emptyValue;
		}

		foreach ($this->matches as $match) {
			if ($match->execute($email)) {
				return true;
			}
		}

		return false;
	}
}
