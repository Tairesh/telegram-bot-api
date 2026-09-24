<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type;

use Luzrain\TelegramBotApi\Type;

/**
 * Describes parameters of an ephemeral message.
 */
final readonly class EphemeralMessageParameters extends Type
{
    public function __construct(
        /**
         * Identifier of the user who will receive the message. It is not guaranteed that the user will receive the message,
         * especially if they are offline. See here for more details.
         *
         * @see https://core.telegram.org/bots/api#ephemeral-messages-and-commands
         */
        public int $receiverUserId,

        /**
         * Optional. Identifier of the callback query which triggered the message, if any
         */
        public string|null $callbackQueryId = null,

        /**
         * Optional. Pass True if the ephemeral message must be shown in place of the original message.
         * Must be False for callback queries from ephemeral messages, which must be edited using regular editEphemeralMessage… methods.
         */
        public bool|null $replaceCallbackQueryMessage = null,
    ) {
    }
}
