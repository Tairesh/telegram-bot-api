<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

use Luzrain\TelegramBotApi\Type\InputMediaVideo;
use Luzrain\TelegramBotApi\Type\RichBlock\RichBlockCaption;

/**
 * A block with a video, corresponding to the HTML tag <video>.
 */
final readonly class InputRichBlockVideo extends InputRichBlock
{
    public const TYPE = 'video';

    public function __construct(
        /**
         * The video. Caption is ignored.
         */
        public InputMediaVideo $video,

        /**
         * Optional. Caption of the block
         */
        public RichBlockCaption|null $caption = null,
    ) {
        parent::__construct(self::TYPE);
    }
}
