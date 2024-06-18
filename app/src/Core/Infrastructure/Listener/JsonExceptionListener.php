<?php
declare(strict_types=1);

namespace App\Core\Infrastructure\Listener;

use App\Core\Application\Exception\ValidationException;
use App\Core\Application\Validation\TypeKey;
use App\Core\Application\Validation\ValidationErrorFormatter;
use App\Core\Domain\Exception\ApiException;
use App\Core\Domain\Exception\Enum\ExceptionMessage;
use App\Core\Domain\Exception\Enum\ExceptionStatusCode;
use App\Core\Domain\Exception\Enum\ExceptionType;
use App\Core\Domain\Service\Logger\Logger;
use App\Core\Infrastructure\Response\ErrorResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

/**
 * Class JsonExceptionListener.
 *
 * This class listens for exceptions thrown by the application and formats them into a JSON response.
 * It also logs exceptions if the response status code is an internal error.
 */
final readonly class JsonExceptionListener
{
    /**
     * Array of exceptions that are allowed to be thrown by the application.
     */
    public const CUSTOM_EXCEPTIONS_FOR_USER = [
        HttpExceptionInterface::class, // \Symfony
        ApiException::class, // \Exception
        // (...)
    ];

    /**
     * JsonExceptionListener constructor.
     *
     * @param Logger $logger the logger instance
     */
    public function __construct(
        private Logger $logger
    ) {
    }

    /**
     * This method is called when an exception occurs in the kernel.
     *
     * @param ExceptionEvent $event the event object containing the exception and request details
     *
     * @throws ApiException
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = $this->createJsonResponse($exception);
        $this->logException($response, $exception);
        $event->setResponse($response);
    }

    /**
     * Creates a JSON response based on the exception and the request.
     *
     * @param Throwable $exception the caught exception
     *
     * @return JsonResponse the JSON response to be returned
     *
     * @throws ApiException
     */
    private function createJsonResponse(Throwable $exception): JsonResponse
    {
        $statusCode = $this->getStatusCode($exception);

        return ErrorResponse::response(
            $this->getErrorData($exception),
            $statusCode,
            $this->getErrorMessage($exception, $statusCode),
            $this->getErrorType($exception)
        );
    }

    /**
     * Generates an appropriate error message or throws a ValidationException.
     *
     * @param Throwable $exception  the caught exception
     * @param int        $statusCode the HTTP status code
     *
     * @return string the error message
     */
    private function getErrorMessage(Throwable $exception, int $statusCode): string
    {
        if (ExceptionStatusCode::INTERNAL_ERROR->value === $statusCode) {
            return ExceptionMessage::INTERNAL->value;
        }
        if ($exception instanceof HttpExceptionInterface && $exception->getPrevious() instanceof ValidationFailedException) {
            return ExceptionMessage::VALIDATION->value;
        }

        return $exception->getMessage();
    }

    /**
     * Determines the type of error based on the exception.
     *
     * @param Throwable $exception the caught exception
     *
     * @return string the error type
     */
    private function getErrorType(Throwable $exception): string
    {
        if ($exception instanceof ApiException) {
            return $exception->getType();
        }

        return ExceptionType::EXCEPTION->value;
    }

    /**
     * Retrieves error data from the exception if applicable.
     *
     * @param Throwable $exception the caught exception
     *
     * @return array|null error data or null
     *
     * @throws ApiException
     */
    private function getErrorData(Throwable $exception): ?array
    {
        if ($exception instanceof ValidationException) {
            return $exception->getViolations();
        }

        if ($exception instanceof HttpExceptionInterface && $exception->getPrevious() instanceof ValidationFailedException) {
            /* @var ValidationFailedException $validationException */
            $validationException = $exception->getPrevious();

            return $this->formatSymfonyConstraintsViolations($validationException->getViolations());
        }

        return null;
    }

    /**
     * Format validation exception errors.
     *
     * @param ConstraintViolationListInterface $violations the list of constraint violations
     *
     * @return array the formatted violations
     *
     * @throws ApiException
     */
    private function formatSymfonyConstraintsViolations(ConstraintViolationListInterface $violations): array
    {
        $formattedViolations = [];

        /** @var ConstraintViolation $violation */
        foreach ($violations as $violation) {
            $propertyPath = $violation->getPropertyPath();
            if ('' === $propertyPath) {
                throw new ApiException(ExceptionMessage::INVALID_PAYLOAD->value, ExceptionStatusCode::BAD_REQUEST->value);
            }

            $constraint = $violation->getConstraint();
            $constraintKey = self::symfonyConstraintKeyMap($constraint);
            $formattedViolations[] = ValidationErrorFormatter::format(
                $constraintKey,
                $propertyPath,
                $violation->getInvalidValue()
            );
        }

        return $formattedViolations;
    }

    /**
     * Maps Symfony constraint violation keys to application-specific keys.
     *
     * @return string|null the application-specific key for the constraint
     */
    private static function symfonyConstraintKeyMap(?Constraint $constraint): ?string
    {
        return match (true) {
            $constraint instanceof NotBlank => TypeKey::NOT_BLANK->value,
            $constraint instanceof NotNull => TypeKey::NOT_NULL->value,
            $constraint instanceof Length => self::getLengthConstraintKey($constraint),
            $constraint instanceof Choice => TypeKey::INVALID->value,
            default => '',
        };
    }

    /**
     * Gets the length constraint key.
     *
     * @return string|void
     */
    private static function getLengthConstraintKey(Length $constraint)
    {
        if (null !== $constraint->min && $constraint->min > 0) {
            return TypeKey::MIN_LENGTH->value;
        }
        if (null !== $constraint->max && $constraint->max > 0) {
            return TypeKey::MAX_LENGTH->value;
        }
    }

    /**
     * Determines the status code to be used in the response.
     *
     * @param Throwable $exception the caught exception
     *
     * @return int the status code
     */
    private function getStatusCode(Throwable $exception): int
    {
        if ($this->isAllowedException($exception)) {
            // For Symfony HttpExceptionInterface, use getStatusCode() if it's enabled.
            if ($exception instanceof HttpExceptionInterface) {
                return $exception->getStatusCode();
            }

            // For allowed exceptions, use getCode() if it's enabled.
            return $exception->getCode();
        }

        return ExceptionStatusCode::INTERNAL_ERROR->value;
    }

    /**
     * Determines if the given exception is allowed or not based on custom exceptions defined for users.
     *
     * @param Throwable $exception the caught exception
     *
     * @return bool true if the exception is allowed, false otherwise
     */
    private function isAllowedException(Throwable $exception): bool
    {
        foreach (self::CUSTOM_EXCEPTIONS_FOR_USER as $allowedException) {
            if ($exception instanceof $allowedException) {
                return true;
            }
        }

        return false;
    }

    /**
     * Logs the exception if the response status code is an internal error.
     *
     * @param JsonResponse $response  the response object
     * @param Throwable   $exception the caught exception
     */
    private function logException(JsonResponse $response, Throwable $exception): void
    {
        if (ExceptionStatusCode::INTERNAL_ERROR->value === $response->getStatusCode()) {
            $this->logger->critical(
                $exception->getMessage(), ['exception' => $exception->getTrace()]
            );
        }
    }
}