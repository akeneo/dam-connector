<?php

declare(strict_types=1);

namespace AkeneoDAMConnector\Domain;

/**
 * @author Willy Mesnage <willy.mesnage@akeneo.com>
 */
class Locale
{
    private $locale;

    public function __construct(string $locale)
    {
        if (1 !== preg_match('/^[a-z]{2}_[A-Z]{2}$/', $locale)) {
            throw new \Exception('Incorrect locale format!');
        }

        $this->locale = $locale;
    }

    public function __toString(): string
    {
        return $this->locale;
    }
}
