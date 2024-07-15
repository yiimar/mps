<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type\Core;

/**
 * @author Yiimar
 */
interface ValueIsNotValid
{
    public static function create(string $value): static;
}