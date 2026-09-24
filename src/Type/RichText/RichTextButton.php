<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\RichText;

use Luzrain\TelegramBotApi\Type\RichMessageButton;

/**
 * A button.
 */
final readonly class RichTextButton extends RichText
{
    public const TYPE = 'button';

    public function __construct(
        /**
         * The button
         */
        public RichMessageButton $button,
    ) {
        parent::__construct(self::TYPE);
    }
}
