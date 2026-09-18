<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type;

use Luzrain\TelegramBotApi\Type;

/**
 * Describes a service message about a chat being joined by a user from a community.
 */
final readonly class CommunityChatJoined extends Type
{
    protected function __construct(
        /**
         * The community from which the chat was joined
         */
        public Community $community,
    ) {
    }
}
