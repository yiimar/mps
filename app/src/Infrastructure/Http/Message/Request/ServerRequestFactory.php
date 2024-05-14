<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Message\Request;

use InvalidArgumentException;
use Psr\Http\Message\{
    RequestFactoryInterface,
    RequestInterface,
    StreamFactoryInterface,
    UriFactoryInterface,
    UriInterface
};
use App\Infrastructure\Http\Message\Headers\Headers;

/**
 * @author Dmitry S
 */
final class ServerRequestFactory implements RequestFactoryInterface
{
    public function __construct(
        protected StreamFactoryInterface $streamFactory,
        protected UriFactoryInterface $uriFactory
    ) {}

    /**
     * {@inheritdoc}
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        if (is_string($uri)) {
            $uri = $this->uriFactory->createUri($uri);
        }

        if (!$uri instanceof UriInterface) {
            throw new InvalidArgumentException(
                'Parameter 2 of RequestFactory::createRequest() must be a string or a compatible UriInterface.'
            );
        }

        $body = $this->streamFactory->createStream();

        return new ServerRequest(
            $method,
            $uri,
            [],
            [],
            new Headers(),
            $body
        );
    }
}