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

    public function testInlineKeyboardMarkupStillAcceptsSinglePositionalArgument(): void
    {
        $markup = new Type\InlineKeyboardMarkup([[new Type\InlineKeyboardButton(text: 'x')]]);

        $this->assertCount(1, $markup->inlineKeyboard);
        $this->assertNull($markup->forceReply);
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

    public function testAdministratorTypesAcceptCanSendWelcomeMessages(): void
    {
        foreach ([Type\ChatAdministratorRights::class, Type\ChatMemberAdministrator::class] as $class) {
            $names = \array_map(
                static fn(\ReflectionParameter $p) => $p->getName(),
                (new \ReflectionClass($class))->getConstructor()->getParameters(),
            );

            $this->assertContains('canSendWelcomeMessages', $names, $class);
        }
    }

    public function testChatMemberAdministratorKeepsCustomTitleLast(): void
    {
        $names = \array_map(
            static fn(\ReflectionParameter $p) => $p->getName(),
            (new \ReflectionClass(Type\ChatMemberAdministrator::class))->getConstructor()->getParameters(),
        );

        $this->assertSame(['canSendWelcomeMessages', 'customTitle'], \array_slice($names, -2));
    }

    public function testDraftMethodsAcceptCanStopAndKeepOnStop(): void
    {
        foreach ([Method\SendMessageDraft::class, Method\SendRichMessageDraft::class] as $class) {
            $names = \array_map(
                static fn(\ReflectionParameter $p) => $p->getName(),
                (new \ReflectionClass($class))->getConstructor()->getParameters(),
            );

            $this->assertContains('canStop', $names, $class);
            $this->assertContains('keepOnStop', $names, $class);
        }
    }
}
