<?php

declare(strict_types=1);

namespace App\Core\Application\Exception;

use App\Core\Application\Validation\TypeKey;
use App\Core\Application\Validation\ValidationErrorFormatter;
use App\Core\Domain\Exception\ApiException;
use App\Core\Domain\Exception\Enum\ExceptionMessage;
use App\Core\Domain\Exception\Enum\ExceptionStatusCode;
use App\Core\Domain\Exception\Enum\ExceptionType;

/**
 * ValidationException class for handling validation errors.
 *
 * This exception is used to encapsulate details about validation errors that occur
 * during the processing of a request. It extends the ApiException class, providing
 * specific information about the nature of the validation error through an array
 * of violations.
 */
class ValidationException extends ApiException
{
    /**
     * @var array an array containing details about the validation violations
     */
    private array $violations;

    /**
     * Constructor for the ValidationException class.
     *
     * @param array $violations an array detailing the validation errors encountered
     */
    public function __construct(array $violations)
    {
        parent::__construct(
            ExceptionMessage::VALIDATION->value,
            ExceptionStatusCode::VALIDATION_ERROR->value,
            ExceptionType::VALIDATION->value
        );
        $this->violations = $violations;
    }

    /**
     * Retrieves the validation violations.
     *
     * @return array an array of validation violations
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * Adds a validation violation.
     *
     * @param TypeKey $constraint the type of constraint that was violated
     * @param string $field the field that was violated
     * @param mixed $value the value that was violated
     */
    public function addViolation(TypeKey $constraint, string $field, mixed $value): void
    {
        $this->violations[] = ValidationErrorFormatter::format($constraint, $field, $value);
    }
}