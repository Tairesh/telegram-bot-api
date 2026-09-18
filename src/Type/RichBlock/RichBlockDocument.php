<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\RichBlock;

use Luzrain\TelegramBotApi\Type\Document;

/**
 * A block with a general file, corresponding to the custom HTML tag <tg-document>.
 */
final readonly class RichBlockDocument extends RichBlock
{
    public const TYPE = 'document';

    public function __construct(
        /**
         * The document
         */
        public Document $document,

        /**
         * Optional. Caption of the block
         */
        public RichBlockCaption|null $caption = null,
    ) {
        parent::__construct(self::TYPE);
    }
}
