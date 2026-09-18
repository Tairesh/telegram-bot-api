<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Method;

use Luzrain\TelegramBotApi\Method;
use Luzrain\TelegramBotApi\Type\InlineKeyboardMarkup;
use Luzrain\TelegramBotApi\Type\MessageEntity;

/**
 * Use this method to edit the caption of an ephemeral message. Note that it is not guaranteed that the user will receive
 * the message edit event, especially if they are offline. On success, True is returned.
 *
 * @extends Method<true>
 */
final class EditEphemeralMessageCaption extends Method
{
    protected static string $methodName = 'editEphemeralMessageCaption';

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
         * New caption of the message, 0-1024 characters after entities parsing
         */
        protected string|null $caption = null,

        /**
         * Mode for parsing entities in the message caption. See formatting options for more details.
         *
         * @see https://core.telegram.org/bots/api#formatting-options
         */
        protected string|null $parseMode = null,

        /**
         * A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
         *
         * @var list<MessageEntity>|null
         */
        protected array|null $captionEntities = null,

        /**
         * Pass True if the caption must be shown above the message media. Supported only for animation, photo and video messages.
         */
        protected bool|null $showCaptionAboveMedia = null,

        /**
         * A JSON-serialized object for an inline keyboard
         */
        protected InlineKeyboardMarkup|null $replyMarkup = null,
    ) {
    }
}
