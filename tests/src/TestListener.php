<?php

namespace Openbuildings\Swiftmailer\Test;

use Swift_Events_SendListener;
use Swift_Events_SendEvent;

/**
 * @package    openbuildings\swiftmailer-filter
 * @author     Ivan Kerin <ikerin@gmail.com>
 * @copyright  (c) 2013 OpenBuildings Ltd.
 * @license    http://spdx.org/licenses/BSD-3-Clause
 */
class TestListener implements Swift_Events_SendListener
{
	private $event;

	public function event()
	{
		return $this->event;
	}

	public function beforeSendPerformed(Swift_Events_SendEvent $evt)
	{
		$this->event = $evt;
	}

	/**
	 * Do nothing
	 *
	 * @param Swift_Events_SendEvent $evt
	 */
	public function sendPerformed(Swift_Events_SendEvent $evt)
	{

	}
}
