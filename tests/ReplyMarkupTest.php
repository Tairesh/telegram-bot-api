<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test;

use Luzrain\TelegramBotApi\Method;
use Luzrain\TelegramBotApi\Type;
use PHPUnit\Framework\TestCase;

final class ReplyMarkupTest extends TestCase
{
    public function testInlineKeyboardMarkupForceReply(): void
    {
        $decoded = \json_decode(\json_encode(new Type\InlineKeyboardMarkup(
            inlineKeyboard: [[new Type\InlineKeyboardButton(text: 'x', callbackData: 'd')]],
            forceReply: true,
        )), true);

        $this->assertTrue($decoded['force_reply']);
        $this->assertSame('x', $decoded['inline_keyboard'][0][0]['text']);
    }

    public function testReplyKeyboardMarkupForceReply(): void
    {
        $decoded = \json_decode(\json_encode(new Type\ReplyKeyboardMarkup(
            keyboard: [[new Type\KeyboardButton(text: 'k')]],
            forceReply: true,
        )), true);

        $this->assertTrue($decoded['force_reply']);
    }

    public function testInlineKeyboardButtonDisabled(): void
    {
        $decoded = \json_decode(\json_encode(new Type\InlineKeyboardButton(
            text: 'x',
            disabled: new Type\DisabledButton(),
        )), true);

        $this->assertArrayHasKey('disabled', $decoded);
    }

    public function testPromoteChatMemberAcceptsCanSendWelcomeMessages(): void
    {
        $params = \iterator_to_array((new Method\PromoteChatMember(
            chatId: 1,
            userId: 2,
            canSendWelcomeMessages: true,
        ))->getIterator());

        $this->assertTrue($params['can_send_welcome_messages']);
    }
}
