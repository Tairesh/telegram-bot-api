<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

use Luzrain\TelegramBotApi\Type;

/**
 * This object represents a block in a rich formatted message to be sent. Currently, it can be any of the following types:
 *
 * @see InputRichBlockParagraph
 * @see InputRichBlockSectionHeading
 * @see InputRichBlockPreformatted
 * @see InputRichBlockFooter
 * @see InputRichBlockDivider
 * @see InputRichBlockMathematicalExpression
 * @see InputRichBlockAnchor
 * @see InputRichBlockList
 * @see InputRichBlockBlockQuotation
 * @see InputRichBlockExpandableBlockQuotation
 * @see InputRichBlockPullQuotation
 * @see InputRichBlockCollage
 * @see InputRichBlockSlideshow
 * @see InputRichBlockTable
 * @see InputRichBlockDetails
 * @see InputRichBlockMap
 * @see InputRichBlockButtons
 * @see InputRichBlockAnimation
 * @see InputRichBlockAudio
 * @see InputRichBlockDocument
 * @see InputRichBlockPhoto
 * @see InputRichBlockVideo
 * @see InputRichBlockVoiceNote
 * @see InputRichBlockThinking
 */
readonly class InputRichBlock extends Type
{
    protected function __construct(
        /**
         * Type of the block
         */
        public string $type,
    ) {
    }
}
