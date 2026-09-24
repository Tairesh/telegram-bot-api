<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type;

use Luzrain\TelegramBotApi\Type;

/**
 * This object describes a profile photo to set. Currently, it can be one of
 *
 * @see InputProfilePhotoStatic
 * @see InputProfilePhotoAnimated
 */
readonly class InputProfilePhoto extends Type
{
    protected function __construct(
        /**
         * Type of the profile photo
         */
        public string $type,
    ) {
    }
}
