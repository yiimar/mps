<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Doctrine\Dbal\Type;

use App\Core\Infrastructure\Doctrine\Dbal\Type\Collection\CollectionType;
use App\Core\Infrastructure\Doctrine\Dbal\Type\Email\EmailType;
use App\Core\Infrastructure\Doctrine\Dbal\Type\Money\MoneyAmountType;
use App\Core\Infrastructure\Doctrine\Dbal\Type\Money\MoneyCurrencyType;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\TypeRegistry;
use InvalidArgumentException;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use function call_user_func;
use function is_a;
use function sprintf;

final class Types
{
    private const DOCTRINE_TYPES = [
        CollectionType::class,
        PasswordType::class,
        MoneyAmountType::class,
        MoneyCurrencyType::class,
        EmailType::class,
    ];

    private array $types;

    /**
     * @param array<string, class-string> $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function register(TypeRegistry $types): void
    {
        foreach ($this->types as $doctrineTypeName => $valueObjectType) {
            if (!$types->has($doctrineTypeName)) {
                $types->register($doctrineTypeName, $this->type($valueObjectType, $doctrineTypeName));
            }
        }
    }

    private function type(string $valueObjectType, string $doctrineTypeName): Type
    {
        foreach (self::DOCTRINE_TYPES as $doctrineTypeClass) {
            $supportValueObjectType = call_user_func([$doctrineTypeClass, 'getSupportedValueObjectType']);
            if (is_a($valueObjectType, $supportValueObjectType, true)) {
                return call_user_func([$doctrineTypeClass, 'create'], $valueObjectType, $doctrineTypeName);
            }
        }

        throw new InvalidArgumentException(
            sprintf(
                'Value object named "%s" associated to type "%s" has no related doctrine type',
                $doctrineTypeName,
                $valueObjectType
            )
        );
    }
}