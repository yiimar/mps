<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Message\Headers;

/**
 * @author Dmitry S
 */

use InvalidArgumentException;
use function base64_encode;
use function function_exists;
use function getallheaders;
use function is_array;
use function is_numeric;
use function is_string;
use function preg_match;
use function strtolower;
use function substr;
use function trim;

class Headers implements HeadersInterface
{
    /**
     * @param Header[] $headers
     * @param array|null $globals
     */
    final public function __construct(
        protected array $headers = [],
        protected ?array $globals = null
    )
    {
        $this->globals = $globals ?? $_SERVER;
        $this->setHeaders($headers);
    }

    /**
     * {@inheritdoc}
     */
    public function addHeader(string $name, array|string $value): HeadersInterface
    {
        [$values, $originalName, $normalizedName] = $this->prepareHeader($name, $value);

        if (isset($this->headers[$normalizedName])) {
            $header = $this->headers[$normalizedName];
            $header->addValues($values);
        } else {
            $this->headers[$normalizedName] = new Header($originalName, $normalizedName, $values);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeHeader(string $name): HeadersInterface
    {
        $name = $this->normalizeHeaderName($name);
        unset($this->headers[$name]);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader(string $name, array $default = []): array
    {
        $name = $this->normalizeHeaderName($name);

        if (isset($this->headers[$name])) {
            $header = $this->headers[$name];
            return $header->getValues();
        }

        if (empty($default)) {
            return $default;
        }

        $this->validateHeader($name, $default);
        return $this->trimHeaderValue($default);
    }

    /**
     * {@inheritdoc}
     */
    public function setHeader(string $name, array|string $value): HeadersInterface
    {
        [$values, $originalName, $normalizedName] = $this->prepareHeader($name, $value);

        // Ensure we preserve original case if the header already exists in the stack
        if (isset($this->headers[$normalizedName])) {
            $existingHeader = $this->headers[$normalizedName];
            $originalName = $existingHeader->getOriginalName();
        }

        $this->headers[$normalizedName] = new Header($originalName, $normalizedName, $values);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeaders(array $headers): HeadersInterface
    {
        $this->headers = [];

        foreach ($this->parseAuthorizationHeader($headers) as $name => $value) {
            $this->addHeader($name, $value);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasHeader(string $name): bool
    {
        $name = $this->normalizeHeaderName($name);
        return isset($this->headers[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders(bool $originalCase = false): array
    {
        $headers = [];

        foreach ($this->headers as $header) {
            $name = $originalCase ? $header->getOriginalName() : $header->getNormalizedName();
            $headers[$name] = $header->getValues();
        }

        return $headers;
    }

    protected function normalizeHeaderName(string $name, bool $preserveCase = false): string
    {
        $name = str_replace('_', '-', $name);

        if (!$preserveCase) {
            $name = strtolower($name);
        }

        if (str_starts_with(strtolower($name), 'http-')) {
            $name = substr($name, 5);
        }

        return $name;
    }

    /**
     * Parse incoming headers and determine Authorization header from original headers
     *
     * @param array $headers
     * @return array
     */
    protected function parseAuthorizationHeader(array $headers): array
    {
        $hasAuthorizationHeader = false;
        foreach ($headers as $name => $value) {
            if (strtolower((string) $name) === 'authorization') {
                $hasAuthorizationHeader = true;
                break;
            }
        }

        if (!$hasAuthorizationHeader) {
            if (isset($this->globals['REDIRECT_HTTP_AUTHORIZATION'])) {
                $headers['Authorization'] = $this->globals['REDIRECT_HTTP_AUTHORIZATION'];
            } elseif (isset($this->globals['PHP_AUTH_USER'])) {
                $pw = $this->globals['PHP_AUTH_PW'] ?? '';
                $headers['Authorization'] = 'Basic ' . base64_encode($this->globals['PHP_AUTH_USER'] . ':' . $pw);
            } elseif (isset($this->globals['PHP_AUTH_DIGEST'])) {
                $headers['Authorization'] = $this->globals['PHP_AUTH_DIGEST'];
            }
        }

        return $headers;
    }

    protected function trimHeaderValue(array|string $value): array
    {
        $items = is_array($value) ? $value : [$value];
        $result = [];
        foreach ($items as $item) {
            $result[] = trim((string) $item, " \t");
        }
        return $result;
    }

    /**
     * @param string $name
     * @param array|string $value
     *
     * @return array
     *@throws InvalidArgumentException
     *
     */
    protected function prepareHeader(string $name, array|string $value): array
    {
        $this->validateHeader($name, $value);
        $values = $this->trimHeaderValue($value);
        $originalName = $this->normalizeHeaderName($name, true);
        $normalizedName = $this->normalizeHeaderName($name);
        return [$values, $originalName, $normalizedName];
    }

    /**
     * Make sure the header complies with RFC 7230.
     *
     * Header names must be a non-empty string consisting of token characters.
     *
     * Header values must be strings consisting of visible characters with all optional
     * leading and trailing whitespace stripped. This method will always strip such
     * optional whitespace. Note that the method does not allow folding whitespace within
     * the values as this was deprecated for almost all instances by the RFC.
     *
     * header-field = field-name ":" OWS field-value OWS
     * field-name   = 1*( "!" / "#" / "$" / "%" / "&" / "'" / "*" / "+" / "-" / "." / "^"
     *              / "_" / "`" / "|" / "~" / %x30-39 / ( %x41-5A / %x61-7A ) )
     * OWS          = *( SP / HTAB )
     * field-value  = *( ( %x21-7E / %x80-FF ) [ 1*( SP / HTAB ) ( %x21-7E / %x80-FF ) ] )
     *
     * @see https://tools.ietf.org/html/rfc7230#section-3.2.4
     *
     * @param string $name
     * @param array|string $value
     *
     * @throws InvalidArgumentException;
     */
    protected function validateHeader(string $name, array|string $value): void
    {
        $this->validateHeaderName($name);
        $this->validateHeaderValue($value);
    }

    /**
     * @param mixed $name
     *
     * @throws InvalidArgumentException
     */
    protected function validateHeaderName(mixed $name): void
    {
        if (!is_string($name) || preg_match("@^[!#$%&'*+.^_`|~0-9A-Za-z-]+$@D", $name) !== 1) {
            throw new InvalidArgumentException('Header name must be an RFC 7230 compatible string.');
        }
    }

    /**
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     */
    protected function validateHeaderValue(mixed $value): void
    {
        $items = is_array($value) ? $value : [$value];

        if (empty($items)) {
            throw new InvalidArgumentException(
                'Header values must be a string or an array of strings, empty array given.'
            );
        }

        $pattern = "@^[ \t\x21-\x7E\x80-\xFF]*$@D";
        foreach ($items as $item) {
            $hasInvalidType = !is_numeric($item) && !is_string($item);
            $rejected = $hasInvalidType || preg_match($pattern, (string) $item) !== 1;
            if ($rejected) {
                throw new InvalidArgumentException(
                    'Header values must be RFC 7230 compatible strings.'
                );
            }
        }
    }

    public static function createFromGlobals(): static
    {
        $headers = null;

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        }

        if (!is_array($headers)) {
            $headers = [];
        }

        return new static($headers);
    }
}