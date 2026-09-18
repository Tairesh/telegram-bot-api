<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

use Luzrain\TelegramBotApi\Internal\ArrayType;
use Luzrain\TelegramBotApi\Type\RichBlock\RichBlockCaption;

/**
 * A slideshow, corresponding to the custom HTML tag <tg-slideshow>.
 */
final readonly class InputRichBlockSlideshow extends InputRichBlock
{
    public const TYPE = 'slideshow';

    public function __construct(
        /**
         * Elements of the slideshow
         *
         * @var list<InputRichBlock>
         */
        #[ArrayType(InputRichBlock::class)]
        public array $blocks,

        /**
         * Optional. Caption of the block
         */
        public RichBlockCaption|null $caption = null,
    ) {
        parent::__construct(self::TYPE);
    }
}
