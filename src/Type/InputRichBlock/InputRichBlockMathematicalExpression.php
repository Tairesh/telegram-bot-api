<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Type\InputRichBlock;

/**
 * A block with a mathematical expression in LaTeX format, corresponding to the custom HTML tag <tg-math-block>.
 */
final readonly class InputRichBlockMathematicalExpression extends InputRichBlock
{
    public const TYPE = 'mathematical_expression';

    public function __construct(
        /**
         * The mathematical expression in LaTeX format
         */
        public string $expression,
    ) {
        parent::__construct(self::TYPE);
    }
}
