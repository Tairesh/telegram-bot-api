<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test;

use Luzrain\TelegramBotApi\ClientApi;
use Luzrain\TelegramBotApi\Event;
use Luzrain\TelegramBotApi\Test\Helper\ClosureTestHelper;
use Luzrain\TelegramBotApi\Type;
use PHPUnit\Framework\TestCase;

final class MessageGenerationStoppedTest extends TestCase
{
    private const STICKER = [
        'file_id' => 'f',
        'file_unique_id' => 'u',
        'type' => 'regular',
        'width' => 1,
        'height' => 1,
        'is_animated' => false,
        'is_video' => false,
    ];

    private function json(): string
    {
        return \file_get_contents(__DIR__ . '/data/events/messageGenerationStopped.json');
    }

    public function testHydrationIgnoresConstructorParameterOrder(): void
    {
        $update = Type\Update::fromJson($this->json());

        $this->assertInstanceOf(Type\MessageGenerationStopped::class, $update->stoppedMessageGeneration);
        $this->assertSame(99, $update->stoppedMessageGeneration->draftId);
        $this->assertNull($update->stoppedMessageGeneration->messageThreadId);
        $this->assertSame(14612111, $update->stoppedMessageGeneration->chat->id);
    }

    public function testEventIsDispatched(): void
    {
        $helper = new ClosureTestHelper();
        $client = new ClientApi();
        $client->on(new Event\MessageGenerationStopped($helper->getClosure()));
        $client->handle(Type\Update::fromJson($this->json()));

        $this->assertTrue($helper->isCalled());
        $this->assertSame(99, $helper->getParameter()->draftId);
    }

    public function testUniqueGiftInfoKeepsExistingPositionalArguments(): void
    {
        $names = \array_map(
            static fn(\ReflectionParameter $p) => $p->getName(),
            (new \ReflectionClass(Type\UniqueGiftInfo::class))->getConstructor()->getParameters(),
        );

        $this->assertSame('gift', $names[0]);
        $this->assertSame('origin', $names[1]);
        $this->assertSame('lastResaleStarCount', $names[2]);
        $this->assertSame(['text', 'entities', 'isPrivate'], \array_slice($names, -3));
    }

    public function testUniqueGiftInfoNewFieldTypes(): void
    {
        $byName = [];
        foreach ((new \ReflectionClass(Type\UniqueGiftInfo::class))->getConstructor()->getParameters() as $p) {
            $byName[$p->getName()] = (string) $p->getType();
        }

        $this->assertSame('?string', $byName['text']);
        $this->assertSame('?array', $byName['entities']);
        $this->assertSame('?true', $byName['isPrivate']);
    }

    public function testEventIsNotDispatchedForUnrelatedUpdate(): void
    {
        $helper = new ClosureTestHelper();
        $client = new ClientApi();
        $client->on(new Event\MessageGenerationStopped($helper->getClosure()));
        $client->handle(Type\Update::fromJson(\file_get_contents(__DIR__ . '/data/events/command.json')));

        $this->assertFalse($helper->isCalled(), 'checker must not match an unrelated update');
    }

    public function testUniqueGiftInfoHydratesNewFields(): void
    {
        $info = Type\UniqueGiftInfo::fromArray([
            'gift' => [
                'gift_id' => 'gift-1',
                'base_name' => 'Gift',
                'name' => 'Gift-1',
                'number' => 1,
                'model' => ['name' => 'm', 'sticker' => self::STICKER, 'rarity_per_mille' => 1],
                'symbol' => ['name' => 's', 'sticker' => self::STICKER, 'rarity_per_mille' => 1],
                'backdrop' => [
                    'name' => 'b',
                    'colors' => ['center_color' => 1, 'edge_color' => 2, 'symbol_color' => 3, 'text_color' => 4],
                    'rarity_per_mille' => 1,
                ],
            ],
            'origin' => 'upgrade',
            'text' => 'Happy birthday',
            'entities' => [['type' => 'bold', 'offset' => 0, 'length' => 5]],
            'is_private' => true,
        ]);

        $this->assertSame('Happy birthday', $info->text);
        $this->assertInstanceOf(Type\MessageEntity::class, $info->entities[0]);
        $this->assertSame('bold', $info->entities[0]->type);
        $this->assertTrue($info->isPrivate);
    }
}
