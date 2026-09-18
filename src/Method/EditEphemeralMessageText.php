<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Method;

use Luzrain\TelegramBotApi\Method;
use Luzrain\TelegramBotApi\Type\InlineKeyboardMarkup;
use Luzrain\TelegramBotApi\Type\InputRichMessage;
use Luzrain\TelegramBotApi\Type\LinkPreviewOptions;
use Luzrain\TelegramBotApi\Type\MessageEntity;

/**
 * Use this method to edit an ephemeral text or rich message. Note that it is not guaranteed that the user will receive
 * the message edit event, especially if they are offline. On success, True is returned.
 *
 * @extends Method<true>
 */
final class EditEphemeralMessageText extends Method
{
    protected static string $methodName = 'editEphemeralMessageText';

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
         * New text of the message, 1-4096 characters after entity parsing; required if rich_message isn't specified
         */
        protected string|null $text = null,

        /**
         * Mode for parsing entities in the message text. See formatting options for more details.
         *
         * @see https://core.telegram.org/bots/api#formatting-options
         */
        protected string|null $parseMode = null,

        /**
         * A JSON-serialized list of special entities that appear in message text, which can be specified instead of parse_mode
         *
         * @var list<MessageEntity>|null
         */
        protected array|null $entities = null,

        /**
         * New rich content of the message; required if text isn't specified
         */
        protected InputRichMessage|null $richMessage = null,

        /**
         * Link preview generation options for the message
         */
        protected LinkPreviewOptions|null $linkPreviewOptions = null,

        /**
         * A JSON-serialized object for an inline keyboard
         */
        protected InlineKeyboardMarkup|null $replyMarkup = null,
    ) {
    }
}
