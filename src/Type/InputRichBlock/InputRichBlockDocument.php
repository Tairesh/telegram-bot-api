<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

use Luzrain\TelegramBotApi\Type\InputMediaDocument;
use Luzrain\TelegramBotApi\Type\RichBlock\RichBlockCaption;

/**
 * A block with a general file, corresponding to the custom HTML tag <tg-document>.
 */
final readonly class InputRichBlockDocument extends InputRichBlock
{
    public const TYPE = 'document';

    public function __construct(
        /**
         * The document. Caption is ignored.
         */
        public InputMediaDocument $document,

        /**
         * Optional. Caption of the block
         */
        public RichBlockCaption|null $caption = null,
    ) {
        parent::__construct(self::TYPE);
    }
}
