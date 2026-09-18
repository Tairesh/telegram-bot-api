<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test;

use Luzrain\TelegramBotApi\ClientApi;
use Luzrain\TelegramBotApi\Event;
use Luzrain\TelegramBotApi\Test\Helper\ClosureTestHelper;
use Luzrain\TelegramBotApi\Type;
use PHPUnit\Framework\TestCase;

final class BotSubscriptionTest extends TestCase
{
    private function json(): string
    {
        return \file_get_contents(__DIR__ . '/data/events/botSubscriptionUpdated.json');
    }

    public function testUpdateHydration(): void
    {
        $update = Type\Update::fromJson($this->json());

        $this->assertSame(900000003, $update->updateId);
        $this->assertInstanceOf(Type\BotSubscriptionUpdated::class, $update->subscription);
        $this->assertInstanceOf(Type\User::class, $update->subscription->user);
        $this->assertSame(123456789, $update->subscription->user->id);
        $this->assertSame('sub-payload-1', $update->subscription->invoicePayload);
        $this->assertSame('canceled', $update->subscription->state);
        $this->assertNull($update->message);
    }

    public function testEventIsDispatched(): void
    {
        $helper = new ClosureTestHelper();
        $client = new ClientApi();
        $client->on(new Event\BotSubscriptionUpdated($helper->getClosure()));
        $client->handle(Type\Update::fromJson($this->json()));

        $this->assertTrue($helper->isCalled());
        $this->assertInstanceOf(Type\BotSubscriptionUpdated::class, $helper->getParameter());
        $this->assertSame('canceled', $helper->getParameter()->state);
    }

    public function testEventIsNotDispatchedForUnrelatedUpdate(): void
    {
        $helper = new ClosureTestHelper();
        $client = new ClientApi();
        $client->on(new Event\BotSubscriptionUpdated($helper->getClosure()));
        $client->handle(Type\Update::fromJson(\file_get_contents(__DIR__ . '/data/events/command.json')));

        $this->assertFalse($helper->isCalled(), 'checker must not match an unrelated update');
    }

    /**
     * UPDATE_TYPES drives setWebhook(allowedUpdates:). An entry missing from it silently drops
     * that update type for every consumer, and nothing else in the suite would notice.
     */
    public function testUpdateTypesMatchesUpdateProperties(): void
    {
        $reflection = new \ReflectionClass(Type\Update::class);

        $constant = $reflection->getConstant('UPDATE_TYPES');
        $properties = [];
        foreach ($reflection->getConstructor()->getParameters() as $parameter) {
            if ($parameter->getName() === 'updateId') {
                continue;
            }
            $properties[] = \strtolower(\preg_replace('/(?<!^)[A-Z]/', '_$0', $parameter->getName()));
        }

        \sort($constant);
        \sort($properties);

        $this->assertSame($properties, $constant);
    }
}
