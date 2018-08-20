<?php

namespace Openbuildings\Swiftmailer;

interface ExtraChecksInterface
{
    public function shouldReceiveEmails(string $email): bool;
}

