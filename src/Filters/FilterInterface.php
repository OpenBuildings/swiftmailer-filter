<?php

namespace Openbuildings\Swiftmailer\Filters;

interface FilterInterface
{
    public function checkEmail(string $email): bool;
}

