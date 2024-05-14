<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Message\Stream;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

use function fclose;
use function feof;
use function fread;
use function fseek;
use function fstat;
use function ftell;
use function fwrite;
use function is_array;
use function is_resource;
use function is_string;
use function pclose;
use function rewind;
use function stream_get_contents;
use function stream_get_meta_data;

use const SEEK_SET;

class Stream implements StreamInterface
{
    /**
     * Bit mask to determine if the stream is a pipe
     *
     * This is octal as per header stat.h
     */
    public const FSTAT_MODE_S_IFIFO = 0010000;

    /**
     * @param resource $stream The underlying stream resource. A PHP resource handle.
     * @param  ?StreamInterface $cache A stream to cache $stream (useful for non-seekable streams)
     *
     * @throws InvalidArgumentException If argument is not a resource.
     */
    public function __construct(
        protected ?resource        $stream,
        protected ?StreamInterface $cache = null,
        protected ?array           $meta = null,
        protected ?bool            $readable = null,
        protected ?bool            $writable = null,
        protected ?bool            $seekable = null,
        protected ?int             $size = null,
        protected ?bool            $isPipe = null,
        protected bool             $finished = false
    )
    {
        $this->attach($stream);

        if ($cache && (!$cache->isSeekable() || !$cache->isWritable())) {
            throw new RuntimeException('Cache stream must be seekable and writable');
        }
    }

    /**
     * {@inheritdoc}
     * @return array|mixed
     */
    public function getMetadata($key = null): mixed
    {
        if (!$this->stream) {
            return null;
        }

        $this->meta = stream_get_meta_data($this->stream);

        if (!$key) {
            return $this->meta;
        }

        return $this->meta[$key] ?? null;
    }

    /**
     * Attach new resource to this object.
     *
     * @param resource $stream A PHP resource handle.
     *
     * @return void
     * @throws InvalidArgumentException If argument is not a valid PHP resource.
     *
     * @internal This method is not part of the PSR-7 standard.
     *
     */
    protected function attach($stream): void
    {
        if (!is_resource($stream)) {
            throw new InvalidArgumentException(__METHOD__ . ' argument must be a valid PHP resource');
        }

        if ($this->stream) {
            $this->detach();
        }

        $this->stream = $stream;
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        $oldResource = $this->stream;
        $this->stream = null;
        $this->meta = null;
        $this->readable = null;
        $this->writable = null;
        $this->seekable = null;
        $this->size = null;
        $this->isPipe = null;

        $this->cache = null;
        $this->finished = false;

        return $oldResource;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        if (!$this->stream) {
            return '';
        }
        if ($this->cache && $this->finished) {
            $this->cache->rewind();
            return $this->cache->getContents();
        }
        if ($this->isSeekable()) {
            $this->rewind();
        }
        return $this->getContents();
    }

    /**
     * {@inheritdoc}
     */
    public function close(): void
    {
        if ($this->stream) {
            if ($this->isPipe()) {
                pclose($this->stream);
            } else {
                fclose($this->stream);
            }
        }

        $this->detach();
    }

    /**
     * {@inheritdoc}
     */
    public function getSize(): ?int
    {
        if ($this->stream && !$this->size) {
            $stats = fstat($this->stream);

            if ($stats) {
                $this->size = !$this->isPipe() ? $stats['size'] : null;
            }
        }

        return $this->size;
    }

    /**
     * {@inheritdoc}
     */
    public function tell(): int
    {
        $position = false;

        if ($this->stream) {
            $position = ftell($this->stream);
        }

        if ($position === false || $this->isPipe()) {
            throw new RuntimeException('Could not get the position of the pointer in stream.');
        }

        return $position;
    }

    /**
     * {@inheritdoc}
     */
    public function eof(): bool
    {
        return !$this->stream || feof($this->stream);
    }

    /**
     * {@inheritdoc}
     */
    public function isReadable(): bool
    {
        if ($this->readable !== null) {
            return $this->readable;
        }

        $this->readable = false;

        if ($this->stream) {
            $mode = $this->getMetadata('mode');

            if (is_string($mode) && (str_contains($mode, 'r') || str_contains($mode, '+'))) {
                $this->readable = true;
            }
        }

        return $this->readable;
    }

    /**
     * {@inheritdoc}
     */
    public function isWritable(): bool
    {
        if ($this->writable === null) {
            $this->writable = false;

            if ($this->stream) {
                $mode = $this->getMetadata('mode');

                if (is_string($mode) && (str_contains($mode, 'w') || str_contains($mode, '+'))) {
                    $this->writable = true;
                }
            }
        }

        return $this->writable;
    }

    /**
     * {@inheritdoc}
     */
    public function isSeekable(): bool
    {
        if ($this->seekable === null) {
            $this->seekable = false;

            if ($this->stream) {
                $this->seekable = !$this->isPipe() && $this->getMetadata('seekable');
            }
        }

        return $this->seekable;
    }

    /**
     * {@inheritdoc}
     */
    public function seek($offset, $whence = SEEK_SET): void
    {
        if (!$this->isSeekable() || ($this->stream && fseek($this->stream, $offset, $whence) === -1)) {
            throw new RuntimeException('Could not seek in stream.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        if (!$this->isSeekable() || ($this->stream && rewind($this->stream) === false)) {
            throw new RuntimeException('Could not rewind stream.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function read($length): string
    {
        $data = false;

        if ($this->isReadable() && $this->stream && $length >= 0) {
            $data = fread($this->stream, $length);
        }

        if (is_string($data)) {
            $this->cache?->write($data);
            if ($this->eof()) {
                $this->finished = true;
            }
            return $data;
        }

        throw new RuntimeException('Could not read from stream.');
    }

    /**
     * {@inheritdoc}
     * @return int
     */
    public function write($string): int
    {
        $written = false;

        if ($this->isWritable() && $this->stream) {
            $written = fwrite($this->stream, $string);
        }

        if ($written !== false) {
            $this->size = null;
            return $written;
        }

        throw new RuntimeException('Could not write to stream.');
    }

    /**
     * {@inheritdoc}
     */
    public function getContents(): string
    {
        if ($this->cache && $this->finished) {
            $this->cache->rewind();
            return $this->cache->getContents();
        }

        $contents = false;

        if ($this->stream) {
            $contents = stream_get_contents($this->stream);
        }

        if (is_string($contents)) {
            $this->cache?->write($contents);
            if ($this->eof()) {
                $this->finished = true;
            }
            return $contents;
        }

        throw new RuntimeException('Could not get contents of stream.');
    }

    /**
     * Returns whether or not the stream is a pipe.
     *
     * @return bool
     * @internal This method is not part of the PSR-7 standard.
     *
     */
    public function isPipe(): bool
    {
        if ($this->isPipe === null) {
            $this->isPipe = false;

            if ($this->stream) {
                $stats = fstat($this->stream);

                if (is_array($stats)) {
                    $this->isPipe = ($stats['mode'] & self::FSTAT_MODE_S_IFIFO) !== 0;
                }
            }
        }

        return $this->isPipe;
    }
}