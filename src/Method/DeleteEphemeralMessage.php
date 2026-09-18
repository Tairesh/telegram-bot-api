<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Method;

use Luzrain\TelegramBotApi\Method;

/**
 * Use this method to delete an ephemeral message. Note that it is not guaranteed that the user will receive
 * the message deletion event, especially if they are offline. Returns True on success.
 *
 * @extends Method<true>
 */
final class DeleteEphemeralMessage extends Method
{
    protected static string $methodName = 'deleteEphemeralMessage';

    public function __construct(
        /**
         * Unique identifier for the target chat or username of the target supergroup in the format @username
         */
        protected int|string $chatId,

        /**
         * Identifier of the user who received the message
         */
        protected int $receiverUserId,

        /**
         * Identifier of the ephemeral message to delete
         */
        protected int $ephemeralMessageId,
    ) {
    }
}
