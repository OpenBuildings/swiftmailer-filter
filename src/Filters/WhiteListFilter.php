<?php

namespace Openbuildings\Swiftmailer\Filters;

use Openbuildings\Swiftmailer\Matches;

class WhiteListFilter implements FilterInterface
{
    private $emails;

    public function __construct($emails)
    {
        $this->emails = new Matches($emails, Matches::TRUE_EMPTY);
    }

    public function checkEmail(string $email): bool
    {
        return $this->emails->equals($email);
    }
}

