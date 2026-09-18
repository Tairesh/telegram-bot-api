<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

/**
 * A divider, corresponding to the HTML tag <hr/>.
 */
final readonly class InputRichBlockDivider extends InputRichBlock
{
    public const TYPE = 'divider';

    public function __construct()
    {
        parent::__construct(self::TYPE);
    }
}
