<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type;

use Luzrain\TelegramBotApi\Type;

/**
 * Describes a media element embedded in an outgoing rich message.
 */
final readonly class InputRichMessageMedia extends Type
{
    public function __construct(
        /**
         * Unique identifier of the media used in a tg://photo?id=, tg://video?id=, tg://document?id=, or tg://audio?id= link.
         * 1-64 characters, only A-Z, a-z, 0-9, _ and - are allowed.
         */
        public string $id,

        /**
         * The media to be sent. Everything except the media itself and its properties is ignored.
         */
        public InputMediaAnimation|InputMediaAudio|InputMediaDocument|InputMediaPhoto|InputMediaVideo|InputMediaVoiceNote $media,
    ) {
    }
}
