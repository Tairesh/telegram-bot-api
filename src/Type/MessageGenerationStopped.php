<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type;

use Luzrain\TelegramBotApi\Type;

/**
 * This object describes an update about a user stopping message generation.
 */
final readonly class MessageGenerationStopped extends Type
{
    protected function __construct(
        /**
         * Chat in which the message is generated
         */
        public Chat $chat,

        /**
         * Unique identifier of the message draft which was stopped
         */
        public int $draftId,

        /**
         * Optional. Unique identifier of the message thread in which the message is generated
         */
        public int|null $messageThreadId = null,
    ) {
    }
}
