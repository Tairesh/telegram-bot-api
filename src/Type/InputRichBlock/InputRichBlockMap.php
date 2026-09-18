<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

use Luzrain\TelegramBotApi\Type\Location;
use Luzrain\TelegramBotApi\Type\RichBlock\RichBlockCaption;

/**
 * A block with a map, corresponding to the custom HTML tag <tg-map>. The map's width and height must not exceed 10000 in total. The width and height ratio must be at most 20.
 */
final readonly class InputRichBlockMap extends InputRichBlock
{
    public const TYPE = 'map';

    public function __construct(
        /**
         * Location of the center of the map
         */
        public Location $location,

        /**
         * Optional. Map zoom level; 0-24
         */
        public int|null $zoom = null,

        /**
         * Optional. Map width; 0-10000
         */
        public int|null $width = null,

        /**
         * Optional. Map height; 0-10000
         */
        public int|null $height = null,

        /**
         * Optional. Caption of the block
         */
        public RichBlockCaption|null $caption = null,
    ) {
        parent::__construct(self::TYPE);
    }
}
