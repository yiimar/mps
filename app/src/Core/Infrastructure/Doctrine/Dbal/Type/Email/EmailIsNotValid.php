<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Email;

use Exception;

/**
 * @author Yiimar
 */
class EmailIsNotValid extends Exception
{
    public static function create(string $email): self
    {
        return new self('Невалидный Email: ' . $email);
    }
}