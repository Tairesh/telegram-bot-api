<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Method;

use Luzrain\TelegramBotApi\Method;
use Luzrain\TelegramBotApi\Type\MessageEntity;

/**
 * Use this method to stream a partial message to a user while the message is being generated.
 * Note that the streamed draft is ephemeral and acts as a temporary 30-second preview - once the output is finalized,
 * you must call sendMessage with the complete message to persist it in the user's chat. Returns True on success.
 *
 * @extends Method<true>
 */
final class SendMessageDraft extends Method
{
    protected static string $methodName = 'sendMessageDraft';

    public function __construct(
        /**
         * Unique identifier for the target private chat
         */
        protected int $chatId,

        /**
         * Unique identifier of the message draft; must be non-zero. Changes to drafts with the same identifier are animated.
         * Otherwise, the draft is replaced without animation.
         */
        protected int $draftId,

        /**
         * Text of the message to be sent, 0-4096 characters after entities parsing. Pass an empty text to show a "Thinking…" placeholder.
         */
        protected string $text,

        /**
         * Unique identifier for the target message thread
         */
        protected int|null $messageThreadId = null,

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
         * Pass True to show the user a button to stop further drafts.
         * The bot will receive an Update "stopped_message_generation" if the user presses the button.
         */
        protected bool|null $canStop = null,

        /**
         * Pass True to keep the draft in the chat when the button is pressed. The draft will still disappear after a short time
         * or if the bot sends a message. To fully preserve the partial draft, the bot should send it as a new message.
         */
        protected bool|null $keepOnStop = null,
    ) {
    }
}
