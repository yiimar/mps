<?php
declare(strict_types=1);

namespace App\Core\Infrastructure\Listener;

use App\Core\Domain\Exception\Enum\ExceptionStatusCode;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use function in_array;

final class RequestListener
{
    private const ALLOWED_CONTENT_TYPE = 'application/json';

    /**
     * Validates the content type of the incoming request.
     *
     * @param Request $request the incoming HTTP request
     *
     * @throws BadRequestException if the content type is not allowed
     */
    private function validateContentType(Request $request): void
    {
        $contentType = $request->headers->get('Content-Type');

        if (self::ALLOWED_CONTENT_TYPE !== $contentType) {
            throw new BadRequestException('Invalid Content-Type. Only application/json is allowed.', ExceptionStatusCode::BAD_REQUEST->value);
        }
        if (empty($request->getContent()) && $this->isContentMethod($request->getMethod())) {
            throw new BadRequestException('Request body cannot be empty for this method', ExceptionStatusCode::BAD_REQUEST->value);
        }
    }

    /**
     * @param string $method the HTTP method
     *
     * @return bool true if the method typically contains content
     */
    private function isContentMethod(string $method): bool
    {
        return in_array($method, [Request::METHOD_POST, Request::METHOD_PUT, Request::METHOD_PATCH], true);
    }

    /**
     * Handles the onKernelRequest event.
     *
     * @param RequestEvent $event the event triggered on each request
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $this->validateContentType($request);
    }
}