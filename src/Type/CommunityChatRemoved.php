<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type;

use Luzrain\TelegramBotApi\Type;

/**
 * Describes a service message about a chat or a bot being removed from a community. Currently holds no information.
 */
final readonly class CommunityChatRemoved extends Type
{
    protected function __construct()
    {
    }
}
