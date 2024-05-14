<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Message\Stream;

use InvalidArgumentException;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use ValueError;

use function fopen;
use function fwrite;
use function is_resource;
use function restore_error_handler;
use function rewind;
use function set_error_handler;

class StreamFactory implements StreamFactoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws RuntimeException
     */
    public function createStream(string $content = ''): StreamInterface
    {
        $resource = fopen('php://temp', 'rwb+');

        if (!is_resource($resource)) {
            throw new RuntimeException('StreamFactory::createStream() could not open temporary file stream.');
        }

        fwrite($resource, $content);
        rewind($resource);

        return $this->createStreamFromResource($resource);
    }

    /**
     * {@inheritdoc}
     */
    public function createStreamFromFile(
        string $filename,
        string $mode = 'rb',
        StreamInterface $cache = null
    ): StreamInterface {
        set_error_handler(
            static function (int $errno, string $errstr) use ($filename, $mode): void {
                throw new RuntimeException(
                    "Unable to open $filename using mode $mode: $errstr",
                    $errno
                );
            }
        );

        try {
            $resource = fopen($filename, $mode);
        } catch (ValueError $exception) {
            throw new RuntimeException("Unable to open $filename using mode $mode: " . $exception->getMessage());
        } finally {
            restore_error_handler();
        }

        if (!is_resource($resource)) {
            throw new RuntimeException(
                "StreamFactory::createStreamFromFile() could not create resource from file `$filename`"
            );
        }

        return new Stream($resource, $cache);
    }

    /**
     * {@inheritdoc}
     */
    public function createStreamFromResource($resource, StreamInterface $cache = null): StreamInterface
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException(
                'Parameter 1 of StreamFactory::createStreamFromResource() must be a resource.'
            );
        }

        return new Stream($resource, $cache);
    }
}