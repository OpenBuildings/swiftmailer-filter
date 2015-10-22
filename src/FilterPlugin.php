<?php

namespace Openbuildings\Swiftmailer;

use Swift_Events_SendListener;
use Swift_Events_SendEvent;

/**
 * @author     Ivan Kerin <ikerin@gmail.com>
 * @copyright  (c) 2015 Clippings Ltd.
 * @license    http://spdx.org/licenses/BSD-3-Clause
 */
class FilterPlugin implements Swift_Events_SendListener
{
	/**
	 * @var Matches
	 */
	private $whitelist;

	/**
	 * @var Matches
	 */
	private $blacklist;

	/**
	 * @param array $whitelist
	 * @param array $blacklist
	 */
	public function __construct(array $whitelist = [], array $blacklist = [])
	{
		$this->whitelist = new Matches($whitelist, Matches::TRUE_EMPTY);
		$this->blacklist = new Matches($blacklist, Matches::FALSE_EMPTY);
	}

	/**
	 * @param  string $email
	 * @return boolean
	 */
	public function filterEmail($email)
	{
		return ! $this->whitelist->equals($email) || $this->blacklist->equals($email);
	}

	/**
	 * @param  array  $emails
	 * @return array
	 */
	public function filterEmailArray(array $emails)
	{
		foreach ($emails as $email => $name) {
			if ($this->filterEmail($email)) {
				unset($emails[$email]);
			}
		}

		return $emails;
	}

	/**
	 * @return Matches
	 */
	public function getWhitelist()
	{
		return $this->whitelist;
	}

	/**
	 * @return Matches
	 */
	public function getBlacklist()
	{
		return $this->blacklist;
	}

	/**
	 * Apply whitelist and blacklist to "to", "cc" and "bcc"
	 *
	 * @param Swift_Events_SendEvent $event
	 */
	public function beforeSendPerformed(Swift_Events_SendEvent $event)
	{
		$message = $event->getMessage();

		$to = $this->filterEmailArray((array) $message->getTo());
		$cc = $this->filterEmailArray((array) $message->getCc());
		$bcc = $this->filterEmailArray((array) $message->getBcc());

		$message->setTo($to);
		$message->setCc($cc);
		$message->setBcc($bcc);

		$all = $to + $cc + $bcc;

		if (empty($all))
		{
			$event->cancelBubble();
		}
	}

	/**
	 * Do nothing
	 *
	 * @param Swift_Events_SendEvent $event
	 */
	public function sendPerformed(Swift_Events_SendEvent $event)
	{
		// Do Nothing
	}
}
