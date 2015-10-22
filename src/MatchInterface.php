<?php

namespace Openbuildings\Swiftmailer;

/**
 * @author     Ivan Kerin <ikerin@gmail.com>
 * @copyright  (c) 2015 Clippings Ltd.
 * @license    http://spdx.org/licenses/BSD-3-Clause
 */
interface MatchInterface
{
	/**
	 * @param  string $email
	 * @return boolean
	 */
	public function execute($email);
}
