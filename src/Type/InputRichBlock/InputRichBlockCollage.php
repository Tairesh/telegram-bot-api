<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

use Luzrain\TelegramBotApi\Internal\ArrayType;
use Luzrain\TelegramBotApi\Type\RichBlock\RichBlockCaption;

/**
 * A collage, corresponding to the custom HTML tag <tg-collage>.
 */
final readonly class InputRichBlockCollage extends InputRichBlock
{
    public const TYPE = 'collage';

    public function __construct(
        /**
         * Elements of the collage
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
