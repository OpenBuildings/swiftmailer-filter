<?php

namespace Openbuildings\Swiftmailer;

/**
 * @author     Ivan Kerin <ikerin@gmail.com>
 * @copyright  (c) 2015 Clippings Ltd.
 * @license    http://spdx.org/licenses/BSD-3-Clause
 */
class IsEqual implements MatchInterface
{
	/**
	 * @var string
	 */
	private $email;

	/**
	 * @param string $email
	 */
	public function __construct($email)
	{
		$this->email = $email;
	}

	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * @param  string  $email
	 * @return boolean
	 */
	public function execute($email)
	{
		return $this->email === $email;
	}
}
