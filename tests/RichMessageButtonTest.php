<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test;

use Luzrain\TelegramBotApi\Type\DisabledButton;
use Luzrain\TelegramBotApi\Type\RichMessageButton;
use Luzrain\TelegramBotApi\Type\RichText\RichText;
use Luzrain\TelegramBotApi\Type\RichText\RichTextButton;
use PHPUnit\Framework\TestCase;

final class RichMessageButtonTest extends TestCase
{
    public function testRichTextButtonIsResolvedByDiscriminator(): void
    {
        $rt = RichText::fromArray([
            'type' => 'button',
            'button' => ['text' => 'Press', 'style' => 'primary', 'callback_data' => 'x'],
        ]);

        $this->assertInstanceOf(RichTextButton::class, $rt);
        $this->assertSame('button', $rt->type);
        $this->assertInstanceOf(RichMessageButton::class, $rt->button);
        $this->assertSame('primary', $rt->button->style);
        $this->assertSame('x', $rt->button->callbackData);
    }

    public function testRichMessageButtonSerializesSnakeCase(): void
    {
        $decoded = \json_decode(\json_encode(new RichMessageButton(
            text: 'Go',
            switchInlineQueryCurrentChat: 'q',
            disabled: new DisabledButton(),
        )), true);

        $this->assertSame('Go', $decoded['text']);
        $this->assertSame('q', $decoded['switch_inline_query_current_chat']);
        $this->assertArrayHasKey('disabled', $decoded);
        $this->assertArrayNotHasKey('url', $decoded);
    }

    public function testDisabledButtonSerializesAsJsonObjectNotArray(): void
    {
        $this->assertSame('{}', \json_encode(new DisabledButton()));
        $this->assertStringContainsString('"disabled":{}', \json_encode(new RichMessageButton(text: 'x', disabled: new DisabledButton())));
    }

    public function testRichBlockDiscriminatorResolvesNewTypes(): void
    {
        $cases = [
            [['type' => 'buttons', 'buttons' => [['text' => 'A']], 'align' => 'center'], \Luzrain\TelegramBotApi\Type\RichBlock\RichBlockButtons::class],
            [['type' => 'expandable_blockquote', 'text' => 'q', 'credit' => 'c'], \Luzrain\TelegramBotApi\Type\RichBlock\RichBlockExpandableBlockQuotation::class],
            [['type' => 'document', 'document' => ['file_id' => 'f', 'file_unique_id' => 'u']], \Luzrain\TelegramBotApi\Type\RichBlock\RichBlockDocument::class],
        ];

        foreach ($cases as [$data, $expected]) {
            $this->assertInstanceOf($expected, \Luzrain\TelegramBotApi\Type\RichBlock\RichBlock::fromArray($data));
        }
    }

    public function testRichBlockButtonsCarriesButtons(): void
    {
        /** @var \Luzrain\TelegramBotApi\Type\RichBlock\RichBlockButtons $block */
        $block = \Luzrain\TelegramBotApi\Type\RichBlock\RichBlock::fromArray([
            'type' => 'buttons',
            'buttons' => [['text' => 'A', 'callback_data' => 'd']],
            'align' => 'right',
        ]);

        $this->assertSame('right', $block->align);
        $this->assertInstanceOf(RichMessageButton::class, $block->buttons[0]);
        $this->assertSame('d', $block->buttons[0]->callbackData);
    }

    public function testRichBlockTableIsCompact(): void
    {
        /** @var \Luzrain\TelegramBotApi\Type\RichBlock\RichBlockTable $table */
        $table = \Luzrain\TelegramBotApi\Type\RichBlock\RichBlock::fromArray([
            'type' => 'table',
            'cells' => [],
            'is_compact' => true,
        ]);

        $this->assertTrue($table->isCompact);
    }
}
