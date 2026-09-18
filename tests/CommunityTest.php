<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test;

use Luzrain\TelegramBotApi\Type\Community;
use Luzrain\TelegramBotApi\Type\CommunityChatAdded;
use Luzrain\TelegramBotApi\Type\CommunityChatJoined;
use Luzrain\TelegramBotApi\Type\CommunityChatRemoved;
use Luzrain\TelegramBotApi\Type\Update;
use PHPUnit\Framework\TestCase;

final class CommunityTest extends TestCase
{
    public function testCommunityChatAddedHydration(): void
    {
        $update = Update::fromJson(\file_get_contents(__DIR__ . '/data/events/communityChatAdded.json'));

        $this->assertInstanceOf(CommunityChatAdded::class, $update->message->communityChatAdded);
        $this->assertInstanceOf(Community::class, $update->message->communityChatAdded->community);
        $this->assertSame(5555555555555, $update->message->communityChatAdded->community->id);
        $this->assertSame('Test Community', $update->message->communityChatAdded->community->name);
        $this->assertNull($update->message->communityChatRemoved);
        $this->assertNull($update->message->communityChatJoined);
    }

    public function testCommunityChatJoinedHydration(): void
    {
        $joined = CommunityChatJoined::fromArray(['community' => ['id' => 1, 'name' => 'C']]);

        $this->assertSame(1, $joined->community->id);
    }

    public function testCommunityChatRemovedSerializesAsJsonObjectNotArray(): void
    {
        $this->assertSame('{}', \json_encode(CommunityChatRemoved::fromArray([])));
    }

    public function testMessageMapsAllThreeCommunityServiceFields(): void
    {
        $message = \Luzrain\TelegramBotApi\Type\Message::fromArray([
            'message_id' => 1,
            'date' => 1,
            'chat' => ['id' => 1, 'type' => 'supergroup'],
            'community_chat_added' => ['community' => ['id' => 1, 'name' => 'A']],
            'community_chat_removed' => [],
            'community_chat_joined' => ['community' => ['id' => 2, 'name' => 'J']],
        ]);

        $this->assertInstanceOf(CommunityChatAdded::class, $message->communityChatAdded);
        $this->assertInstanceOf(CommunityChatRemoved::class, $message->communityChatRemoved);
        $this->assertInstanceOf(CommunityChatJoined::class, $message->communityChatJoined);
        $this->assertSame('J', $message->communityChatJoined->community->name);
    }

    public function testChatFullInfoCarriesCommunity(): void
    {
        $chat = \Luzrain\TelegramBotApi\Type\ChatFullInfo::fromArray([
            'id' => 1,
            'type' => 'supergroup',
            'accent_color_id' => 0,
            'max_reaction_count' => 11,
            'accepted_gift_types' => [
                'unlimited_gifts' => true,
                'limited_gifts' => true,
                'unique_gifts' => true,
                'premium_subscription' => false,
            ],
            'community' => ['id' => 9, 'name' => 'Nine'],
        ]);

        $this->assertInstanceOf(Community::class, $chat->community);
        $this->assertSame(9, $chat->community->id);
    }
}
