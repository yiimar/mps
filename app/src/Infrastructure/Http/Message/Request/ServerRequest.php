<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Message\Request;

use App\Infrastructure\Http\Enum\HttpMethods;
use App\Infrastructure\Http\Message\Headers\HeadersInterface;
use App\Infrastructure\Http\Message\Message;
use InvalidArgumentException;
use Psr\Http\Message\{ServerRequestInterface, StreamInterface, UploadedFileInterface, UriInterface};
use function is_array;
use function is_null;
use function is_object;
use function is_string;
use function ltrim;
use function parse_str;
use function preg_match;
use function sprintf;
use function str_replace;

class ServerRequest extends Message implements ServerRequestInterface
{
    protected string $requestTarget = '/';

    protected ?array $queryParams;

    protected null|array|object $parsedBody;

    /**
     * @param string           $method        The request method
     * @param UriInterface     $uri           The request URI object
     * @param HeadersInterface $headers       The request headers collection
     * @param array            $cookies       The request cookies collection
     * @param array            $serverParams  The server environment variables
     * @param StreamInterface  $body          The request body object
     * @param UploadedFileInterface[] $uploadedFiles The request uploadedFiles collection
     * @throws InvalidArgumentException on invalid HTTP method
     */
    public function __construct(
        protected string $method,
        protected UriInterface $uri,
        protected array $cookies,
        protected array $serverParams,
        HeadersInterface $headers,
        StreamInterface $body,
        protected array $attributes = [],
        protected array $uploadedFiles = [],
    ) {
        parent::__construct($headers, $body);

        $this->method = $this->filterMethod($method);

        if (isset($serverParams['SERVER_PROTOCOL'])) {
            $this->protocolVersion = str_replace('HTTP/', '', $serverParams['SERVER_PROTOCOL']);
        }

        if (!$this->headers->hasHeader('Host') || $this->uri->getHost() !== '') {
            $this->headers->setHeader('Host', $this->uri->getHost());
        }
    }

    /**
     * This method is applied to the cloned object after PHP performs an initial shallow-copy.
     * This method completes a deep-copy by creating new objects for the cloned object's internal reference pointers.
     */
    public function __clone()
    {
        $this->headers = clone $this->headers;
        $this->body = clone $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withMethod(string $method): ServerRequestInterface
    {
        $method = $this->filterMethod($method);
        $clone = clone $this;
        $clone->method = $method;

        return $clone;
    }

    /**
     * Validate the HTTP method
     *
     * @param string $method
     *
     * @return string
     *
     * @throws InvalidArgumentException on invalid HTTP method.
     */
    protected function filterMethod(string $method): string
    {
        if (null === HttpMethods::tryFrom($method)) {
            throw new InvalidArgumentException(sprintf(
                'Unsupported HTTP method "%s" provided',
                $method
            ));
        }

        return $method;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestTarget(): string
    {
        if ($this->requestTarget) {
            return $this->requestTarget;
        }

        if ($this->uri === null) {
            return '/';
        }

        $path = $this->uri->getPath();
        $path = '/' . ltrim($path, '/');

        $query = $this->uri->getQuery();
        if ($query) {
            $path .= '?' . $query;
        }

        return $path;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withRequestTarget($requestTarget): ServerRequestInterface
    {
        if (!is_string($requestTarget) || preg_match('#\s#', $requestTarget)) {
            throw new InvalidArgumentException(
                'Invalid request target provided; must be a string and cannot contain whitespace'
            );
        }

        $clone = clone $this;
        $clone->requestTarget = $requestTarget;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withUri(UriInterface $uri, $preserveHost = false): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->uri = $uri;

        if (!$preserveHost && $uri->getHost() !== '') {
            $clone->headers->setHeader('Host', $uri->getHost());
            return $clone;
        }

        if ((($uri->getHost() !== '' && !$this->hasHeader('Host')) || $this->getHeaderLine('Host') === '')) {
            $clone->headers->setHeader('Host', $uri->getHost());
            return $clone;
        }

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getCookieParams(): array
    {
        return $this->cookies;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->cookies = $cookies;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParams(): array
    {
        if (is_array($this->queryParams)) {
            return $this->queryParams;
        }

        if ($this->uri === null) {
            return [];
        }

        // Decode URL data
        parse_str($this->uri->getQuery(), $this->queryParams);

        // @phpstan-ignore-next-line
        return is_array($this->queryParams) ? $this->queryParams : [];
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->queryParams = $query;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->uploadedFiles = $uploadedFiles;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     * @return mixed
     */
    public function getAttribute($name, $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withAttribute($name, $value): ServerRequestInterface
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;

        return $clone;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withoutAttribute($name): ServerRequestInterface
    {
        $clone = clone $this;

        unset($clone->attributes[$name]);

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function getParsedBody(): object|array|null
    {
        return $this->parsedBody;
    }

    /**
     * {@inheritdoc}
     * @return static
     */
    public function withParsedBody($data): ServerRequestInterface
    {
        /** @var mixed $data */
        if (!is_null($data) && !is_object($data) && !is_array($data)) {
            throw new InvalidArgumentException('Parsed body value must be an array, an object, or null');
        }

        $clone = clone $this;
        $clone->parsedBody = $data;

        return $clone;
    }
}