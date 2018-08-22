<?php

namespace Openbuildings\Swiftmailer;

use Openbuildings\Swiftmailer\Filters\FilterInterface;
use Swift_Events_SendEvent;
use Swift_Events_SendListener;

/**
 * @author     Ivan Kerin <ikerin@gmail.com>
 * @copyright  (c) 2015 Clippings Ltd.
 * @license    http://spdx.org/licenses/BSD-3-Clause
 */
class FilterPlugin implements Swift_Events_SendListener
{
    /**
     * @var FilterInterface[]
     */
    private $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function filterEmail($email)
    {
        foreach ($this->filters as $filter) {
            $shouldKeepEmail = $filter->checkEmail($email);
            if (!$shouldKeepEmail) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $emails
     *
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
     * Apply whitelist and blacklist to "to", "cc" and "bcc".
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

        if (empty($all)) {
            $event->cancelBubble();
        }
    }

    /**
     * Do nothing.
     *
     * @param Swift_Events_SendEvent $event
     */
    public function sendPerformed(Swift_Events_SendEvent $event)
    {
        // Do Nothing
    }
}
