<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test\Helper;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

final class TestRequest implements RequestInterface
{
    private array $headers = [];
    private StreamInterface|null $body = null;

    public function __construct(
        private readonly string $method,
        private readonly string $uri,
    ) {
    }

    public function getRequestTarget(): string
    {
        return $this->uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): UriInterface
    {
        throw new \LogicException('Not needed in tests; use getUriString()');
    }

    public function getUriString(): string
    {
        return $this->uri;
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

    public function getBody(): StreamInterface
    {
        return $this->body ?? TestStream::fromString('');
    }

    public function withAddedHeader(string $name, $value): static
    {
        $clone = clone $this;
        $clone->headers[$name][] = $value;

        return $clone;
    }

    public function withBody(StreamInterface $body): static
    {
        $clone = clone $this;
        $clone->body = $body;

        return $clone;
    }

    public function withRequestTarget(string $requestTarget): static
    {
        return $this;
    }

    public function withMethod(string $method): static
    {
        return $this;
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): static
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

    public function withoutHeader(string $name): static
    {
        $clone = clone $this;
        unset($clone->headers[$name]);

        return $clone;
    }
}
