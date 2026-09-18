<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type;

use Luzrain\TelegramBotApi\Type;

/**
 * Describes a service message about a chat or a bot being added to a community.
 */
final readonly class CommunityChatAdded extends Type
{
    protected function __construct(
        /**
         * The new community to which the chat or the bot belongs
         */
        public Community $community,
    ) {
    }
}
