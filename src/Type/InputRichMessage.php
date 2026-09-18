<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type;

use Luzrain\TelegramBotApi\Internal\ArrayType;
use Luzrain\TelegramBotApi\Type;
use Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlock;

/**
 * Describes a rich message to be sent. Exactly one of the fields html, markdown, or blocks must be used.
 */
final readonly class InputRichMessage extends Type
{
    public function __construct(
        /**
         * Optional. Content of the rich message to send described using HTML formatting. See rich message formatting options for more details.
         * Use media field to specify the media used in the message.
         */
        public string|null $html = null,

        /**
         * Optional. Content of the rich message to send described using Markdown formatting. See rich message formatting options for more details.
         * Use media field to specify the media used in the message.
         */
        public string|null $markdown = null,

        /**
         * Optional. Pass True if the rich message must be shown right-to-left
         */
        public bool|null $isRtl = null,

        /**
         * Optional. Pass True to skip automatic detection of entities (e.g., URLs, email addresses, username mentions, hashtags, cashtags, bot commands, or phone numbers) in the text
         */
        public bool|null $skipEntityDetection = null,

        /**
         * Optional. Content of the rich message to send described as a list of blocks
         *
         * @var list<InputRichBlock>|null
         */
        #[ArrayType(InputRichBlock::class)]
        public array|null $blocks = null,

        /**
         * Optional. List of media that are specified in the markdown or html fields using tg://photo?id=, tg://video?id=,
         * tg://document?id=, and tg://audio?id= links
         *
         * @var list<InputRichMessageMedia>|null
         */
        #[ArrayType(InputRichMessageMedia::class)]
        public array|null $media = null,
    ) {
    }
}
