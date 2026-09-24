<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type;

use Luzrain\TelegramBotApi\Type;

/**
 * Represents a community (a group of chats).
 */
final readonly class Community extends Type
{
    protected function __construct(
        /**
         * Unique identifier for this community. This number may have more than 32 significant bits and some programming languages
         * may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer
         * or double-precision float type are safe for storing this identifier.
         */
        public int $id,

        /**
         * Name of the community
         */
        public string $name,
    ) {
    }
}
