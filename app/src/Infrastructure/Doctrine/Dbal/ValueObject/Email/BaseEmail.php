<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Dbal\ValueObject\Email;

use App\Domain\Core\Exception\EmailIsNotValid;
use Webmozart\Assert\Assert;
use function mb_strtolower;

/**
 * @author Dmitry S
 */
class BaseEmail
{
    public function __construct(private string $value)
    {
        Assert::notEmpty($value);
        Assert::email($value);
        $this->value = mb_strtolower($value);
    }

    /**
     * @throws \App\Domain\Core\Exception\EmailIsNotValid
     */
    public static function create(string $email): static
    {
        self::validate($email);
        $email = mb_strtolower(trim($email));
        return new static($email);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @throws \App\Domain\Core\Exception\EmailIsNotValid
     */
    private static function validate(string $email): void
    {
        Assert::notEmpty($email, "email can't be empty");
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw EmailIsNotValid::create($email);
        }
    }
}