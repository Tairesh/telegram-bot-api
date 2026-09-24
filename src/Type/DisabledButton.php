<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type;

use Luzrain\TelegramBotApi\Type;

/**
 * This object represents a disabled button which does nothing. Currently holds no information.
 */
final readonly class DisabledButton extends Type
{
    public function __construct()
    {
    }
}
