<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test\Helper;

use Psr\Http\Message\StreamInterface;

final class TestStream implements StreamInterface
{
    /** @var resource|null */
    private $resource;

    /**
     * @param resource $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public static function fromString(string $content): self
    {
        $resource = \fopen('php://temp', 'r+');
        \fwrite($resource, $content);
        \rewind($resource);

        return new self($resource);
    }

    public function __toString(): string
    {
        $this->rewind();

        return $this->getContents();
    }

    public function close(): void
    {
        if ($this->resource !== null) {
            \fclose($this->resource);
            $this->resource = null;
        }
    }

    public function detach()
    {
        $resource = $this->resource;
        $this->resource = null;

        return $resource;
    }

    public function getSize(): int|null
    {
        return $this->resource === null ? null : (\fstat($this->resource)['size'] ?? null);
    }

    public function tell(): int
    {
        return \ftell($this->resource);
    }

    public function eof(): bool
    {
        return $this->resource === null || \feof($this->resource);
    }

    public function isSeekable(): bool
    {
        return true;
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        \fseek($this->resource, $offset, $whence);
    }

    public function rewind(): void
    {
        \rewind($this->resource);
    }

    public function isWritable(): bool
    {
        return true;
    }

    public function write(string $string): int
    {
        return \fwrite($this->resource, $string);
    }

    public function isReadable(): bool
    {
        return true;
    }

    public function read(int $length): string
    {
        return \fread($this->resource, $length);
    }

    public function getContents(): string
    {
        return \stream_get_contents($this->resource);
    }

    public function getMetadata(string|null $key = null)
    {
        $meta = \stream_get_meta_data($this->resource);

        return $key === null ? $meta : ($meta[$key] ?? null);
    }
}
