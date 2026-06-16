<?php

declare(strict_types=1);

namespace App\Exceptions;

class ApiException extends \RuntimeException
{
    public static function notFound(): never
    {
        throw new self('Character not found.');
    }

    public static function rateLimited(): never
    {
        throw new self('API rate limit exceeded. Please try again later.');
    }

    public static function unavailable(): never
    {
        throw new self('The Rick and Morty API is currently unavailable. Please try again later.');
    }
}
