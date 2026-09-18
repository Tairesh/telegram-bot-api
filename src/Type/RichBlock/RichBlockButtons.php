<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\RichBlock;

use Luzrain\TelegramBotApi\Internal\ArrayType;
use Luzrain\TelegramBotApi\Type\RichMessageButton;

/**
 * A block containing a list of buttons that are shown in one row, corresponding to the custom HTML tag <tg-button-row>.
 */
final readonly class RichBlockButtons extends RichBlock
{
    public const TYPE = 'buttons';

    public function __construct(
        /**
         * The buttons
         *
         * @var list<RichMessageButton>
         */
        #[ArrayType(RichMessageButton::class)]
        public array $buttons,

        /**
         * Optional. Horizontal alignment of the buttons. Currently, must be one of "left", "center", or "right".
         */
        public string|null $align = null,
    ) {
        parent::__construct(self::TYPE);
    }
}
