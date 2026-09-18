<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

use Luzrain\TelegramBotApi\Internal\ArrayType;
use Luzrain\TelegramBotApi\Internal\RichTextType;
use Luzrain\TelegramBotApi\Type\RichText\RichText;

/**
 * A block quotation, corresponding to the HTML tag <blockquote>.
 */
final readonly class InputRichBlockBlockQuotation extends InputRichBlock
{
    public const TYPE = 'blockquote';

    public function __construct(
        /**
         * Content of the block
         *
         * @var list<InputRichBlock>
         */
        #[ArrayType(InputRichBlock::class)]
        public array $blocks,

        /**
         * Optional. Credit of the block
         *
         * @var RichText|string|list<RichText|string|array>|null
         */
        #[RichTextType]
        public RichText|string|array|null $credit = null,
    ) {
        parent::__construct(self::TYPE);
    }
}
