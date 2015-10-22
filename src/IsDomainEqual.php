<?php

namespace Openbuildings\Swiftmailer;

/**
 * @author     Ivan Kerin <ikerin@gmail.com>
 * @copyright  (c) 2015 Clippings Ltd.
 * @license    http://spdx.org/licenses/BSD-3-Clause
 */
class IsDomainEqual implements MatchInterface
{
	/**
	 * @var string
	 */
	private $domain;

	/**
	 * @param string $domain
	 */
	public function __construct($domain)
	{
		$this->domain = $domain;
	}

	/**
	 * @return string
	 */
	public function getDomain()
	{
		return $this->domain;
	}

	/**
	 * @param  string $email
	 * @return boolean
	 */
	public function execute($email)
	{
		list( , $domain) = explode('@', $email);

		return $this->domain === $domain;
	}
}
