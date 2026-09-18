<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test\Helper;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

final class TestPsrFactory implements RequestFactoryInterface, StreamFactoryInterface
{
    /** @var list<string> */
    private array $streamFromFileCalls = [];

    public function createRequest(string $method, $uri): RequestInterface
    {
        return new TestRequest($method, (string) $uri);
    }

    public function createStream(string $content = ''): StreamInterface
    {
        return TestStream::fromString($content);
    }

    public function createStreamFromFile(string $filename, string $mode = 'r'): StreamInterface
    {
        $this->streamFromFileCalls[] = $filename;

        return new TestStream(\fopen($filename, $mode));
    }

    public function createStreamFromResource($resource): StreamInterface
    {
        return new TestStream($resource);
    }

    /**
     * @return list<string>
     */
    public function getStreamFromFileCalls(): array
    {
        return $this->streamFromFileCalls;
    }
}
