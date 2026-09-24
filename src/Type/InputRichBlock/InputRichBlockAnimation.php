<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

use Luzrain\TelegramBotApi\Type\InputMediaAnimation;
use Luzrain\TelegramBotApi\Type\RichBlock\RichBlockCaption;

/**
 * A block with an animation, corresponding to the HTML tag <video>.
 */
final readonly class InputRichBlockAnimation extends InputRichBlock
{
    public const TYPE = 'animation';

    public function __construct(
        /**
         * The animation. Caption is ignored.
         */
        public InputMediaAnimation $animation,

        /**
         * Optional. Caption of the block
         */
        public RichBlockCaption|null $caption = null,
    ) {
        parent::__construct(self::TYPE);
    }
}
