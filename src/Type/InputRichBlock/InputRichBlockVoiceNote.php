<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

use Luzrain\TelegramBotApi\Type\InputMediaVoiceNote;
use Luzrain\TelegramBotApi\Type\RichBlock\RichBlockCaption;

/**
 * A block with a voice note, corresponding to the HTML tag <audio>.
 */
final readonly class InputRichBlockVoiceNote extends InputRichBlock
{
    public const TYPE = 'voice_note';

    public function __construct(
        /**
         * The voice note. Caption is ignored.
         */
        public InputMediaVoiceNote $voiceNote,

        /**
         * Optional. Caption of the block
         */
        public RichBlockCaption|null $caption = null,
    ) {
        parent::__construct(self::TYPE);
    }
}
