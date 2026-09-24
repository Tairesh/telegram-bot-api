<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

use Luzrain\TelegramBotApi\Type\InputMediaPhoto;
use Luzrain\TelegramBotApi\Type\RichBlock\RichBlockCaption;

/**
 * A block with a photo, corresponding to the HTML tag <img>.
 */
final readonly class InputRichBlockPhoto extends InputRichBlock
{
    public const TYPE = 'photo';

    public function __construct(
        /**
         * The photo. Caption is ignored.
         */
        public InputMediaPhoto $photo,

        /**
         * Optional. Caption of the block
         */
        public RichBlockCaption|null $caption = null,
    ) {
        parent::__construct(self::TYPE);
    }
}
