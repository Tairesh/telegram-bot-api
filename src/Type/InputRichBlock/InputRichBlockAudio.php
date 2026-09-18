<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

use Luzrain\TelegramBotApi\Type\InputMediaAudio;
use Luzrain\TelegramBotApi\Type\RichBlock\RichBlockCaption;

/**
 * A block with a music file, corresponding to the HTML tag <audio>.
 */
final readonly class InputRichBlockAudio extends InputRichBlock
{
    public const TYPE = 'audio';

    public function __construct(
        /**
         * The audio. Caption is ignored.
         */
        public InputMediaAudio $audio,

        /**
         * Optional. Caption of the block
         */
        public RichBlockCaption|null $caption = null,
    ) {
        parent::__construct(self::TYPE);
    }
}
