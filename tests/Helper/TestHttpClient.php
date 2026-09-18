<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test\Helper;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class TestHttpClient implements ClientInterface
{
    private RequestInterface|null $lastRequest = null;

    public function __construct(private readonly string $responseJson = '{"ok":true,"result":true}')
    {
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->lastRequest = $request;

        return new TestResponse(TestStream::fromString($this->responseJson));
    }

    public function getLastRequest(): RequestInterface|null
    {
        return $this->lastRequest;
    }
}
