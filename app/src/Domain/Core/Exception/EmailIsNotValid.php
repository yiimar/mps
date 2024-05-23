<?php declare(strict_types=1);

namespace App\Domain\Core\Exception;

use Exception;

/**
 * @author Dmitry S
 */
class EmailIsNotValid extends Exception
{
    public static function create(string $email): self
    {
        return new self('Невалидный EMAIL: ' . $email);
    }
}