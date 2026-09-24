<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test\Helper;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class TestResponse implements ResponseInterface
{
    private array $headers = [];

    public function __construct(
        private readonly StreamInterface $body,
        private readonly int $statusCode = 200,
        private readonly string $reasonPhrase = 'OK',
    ) {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function getProtocolVersion(): string
    {
        return '1.1';
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader(string $name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader(string $name): array
    {
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine(string $name): string
    {
        return \implode(', ', $this->getHeader($name));
    }

    public function withStatus(int $code, string $reasonPhrase = ''): static
    {
        return $this;
    }

    public function withProtocolVersion(string $version): static
    {
        return $this;
    }

    public function withHeader(string $name, $value): static
    {
        $clone = clone $this;
        $clone->headers[$name] = [$value];

        return $clone;
    }

    public function withAddedHeader(string $name, $value): static
    {
        $clone = clone $this;
        $clone->headers[$name][] = $value;

        return $clone;
    }

    public function withoutHeader(string $name): static
    {
        $clone = clone $this;
        unset($clone->headers[$name]);

        return $clone;
    }

    public function withBody(StreamInterface $body): static
    {
        return new self($body, $this->statusCode, $this->reasonPhrase);
    }
}
