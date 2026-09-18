<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Method;

use Luzrain\TelegramBotApi\Method;
use Luzrain\TelegramBotApi\Type\InlineKeyboardMarkup;

/**
 * Use this method to edit only the reply markup of an ephemeral message. Note that it is not guaranteed that the user
 * will receive the message edit event, especially if they are offline. On success, True is returned.
 *
 * @extends Method<true>
 */
final class EditEphemeralMessageReplyMarkup extends Method
{
    protected static string $methodName = 'editEphemeralMessageReplyMarkup';

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
         * Identifier of the ephemeral message to edit
         */
        protected int $ephemeralMessageId,

        /**
         * A JSON-serialized object for an inline keyboard
         */
        protected InlineKeyboardMarkup|null $replyMarkup = null,
    ) {
    }
}
