<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test;

use Luzrain\TelegramBotApi\Method;
use Luzrain\TelegramBotApi\Type;
use PHPUnit\Framework\TestCase;

final class EphemeralMessageTest extends TestCase
{
    public function testMethodNamesMatchTelegram(): void
    {
        $this->assertSame('editEphemeralMessageText', (new Method\EditEphemeralMessageText(chatId: 1, receiverUserId: 2, ephemeralMessageId: 3))->getName());
        $this->assertSame('editEphemeralMessageMedia', (new Method\EditEphemeralMessageMedia(chatId: 1, receiverUserId: 2, ephemeralMessageId: 3, media: new Type\InputMediaPhoto(media: 'f')))->getName());
        $this->assertSame('editEphemeralMessageCaption', (new Method\EditEphemeralMessageCaption(chatId: 1, receiverUserId: 2, ephemeralMessageId: 3))->getName());
        $this->assertSame('editEphemeralMessageReplyMarkup', (new Method\EditEphemeralMessageReplyMarkup(chatId: 1, receiverUserId: 2, ephemeralMessageId: 3))->getName());
        $this->assertSame('deleteEphemeralMessage', (new Method\DeleteEphemeralMessage(chatId: 1, receiverUserId: 2, ephemeralMessageId: 3))->getName());
    }

    public function testMethodsSerializeSnakeCaseParameters(): void
    {
        $params = \iterator_to_array((new Method\DeleteEphemeralMessage(chatId: 42, receiverUserId: 7, ephemeralMessageId: 9))->getIterator());

        $this->assertSame(['chat_id' => 42, 'receiver_user_id' => 7, 'ephemeral_message_id' => 9], $params);
    }

    public function testEditMethodsSerializeTheirRequiredParameters(): void
    {
        $methods = [
            new Method\EditEphemeralMessageText(chatId: 4, receiverUserId: 5, ephemeralMessageId: 6),
            new Method\EditEphemeralMessageCaption(chatId: 4, receiverUserId: 5, ephemeralMessageId: 6),
            new Method\EditEphemeralMessageReplyMarkup(chatId: 4, receiverUserId: 5, ephemeralMessageId: 6),
        ];

        foreach ($methods as $method) {
            $params = \iterator_to_array($method->getIterator());

            $this->assertSame(
                ['chat_id' => 4, 'receiver_user_id' => 5, 'ephemeral_message_id' => 6],
                $params,
                $method->getName() . ' must emit exactly its three required parameters',
            );
        }
    }

    public function testEditEphemeralMessageMediaUploadsLocalFile(): void
    {
        $factory = new \Luzrain\TelegramBotApi\Test\Helper\TestPsrFactory();
        $client = new \Luzrain\TelegramBotApi\Test\Helper\TestHttpClient();

        (new \Luzrain\TelegramBotApi\BotApi(
            requestFactory: $factory,
            streamFactory: $factory,
            client: $client,
            token: 'T',
        ))->call(new Method\EditEphemeralMessageMedia(
            chatId: 1,
            receiverUserId: 2,
            ephemeralMessageId: 3,
            media: new Type\InputMediaPhoto(media: new Type\InputFile(__DIR__ . '/data/events/command.json')),
        ));

        $this->assertCount(1, $factory->getStreamFromFileCalls());
    }

    public function testEphemeralMessageParametersSerialization(): void
    {
        $decoded = \json_decode(\json_encode(new Type\EphemeralMessageParameters(
            receiverUserId: 42,
            replaceCallbackQueryMessage: true,
        )), true);

        $this->assertSame(['receiver_user_id' => 42, 'replace_callback_query_message' => true], $decoded);
        $this->assertArrayNotHasKey('callback_query_id', $decoded);
    }

    public function testEphemeralParametersAreSerialized(): void
    {
        $params = \iterator_to_array((new Method\SendMessage(
            chatId: 1,
            text: 't',
            ephemeralMessageParameters: new Type\EphemeralMessageParameters(receiverUserId: 5),
        ))->getIterator());

        $this->assertArrayHasKey('ephemeral_message_parameters', $params);
        $this->assertSame(5, $params['ephemeral_message_parameters']->receiverUserId);
    }

    public function testAllFourteenMethodsAcceptEphemeralParameters(): void
    {
        $expected = [
            'SendMessage', 'SendAnimation', 'SendAudio', 'SendDocument', 'SendLivePhoto',
            'SendPhoto', 'SendSticker', 'SendVideo', 'SendVideoNote', 'SendVoice',
            'SendContact', 'SendLocation', 'SendVenue', 'SendRichMessage',
        ];

        foreach ($expected as $short) {
            $names = \array_map(
                static fn(\ReflectionParameter $p) => $p->getName(),
                (new \ReflectionClass('Luzrain\\TelegramBotApi\\Method\\' . $short))->getConstructor()->getParameters(),
            );

            $this->assertContains('ephemeralMessageParameters', $names, $short . ' must accept ephemeralMessageParameters');
        }
    }

    public function testMethodsWithoutEphemeralSupportAreUntouched(): void
    {
        $excluded = ['SendMediaGroup', 'SendPoll', 'SendDice', 'SendChecklist', 'SendGame', 'SendInvoice', 'SendPaidMedia'];

        foreach ($excluded as $short) {
            $names = \array_map(
                static fn(\ReflectionParameter $p) => $p->getName(),
                (new \ReflectionClass('Luzrain\\TelegramBotApi\\Method\\' . $short))->getConstructor()->getParameters(),
            );

            $this->assertNotContains('ephemeralMessageParameters', $names, $short . ' must NOT accept ephemeralMessageParameters');
        }
    }

    public function testEphemeralMessageHydration(): void
    {
        $update = Type\Update::fromJson(\file_get_contents(__DIR__ . '/data/events/ephemeralMessage.json'));

        $this->assertSame(0, $update->message->messageId);
        $this->assertSame(55, $update->message->ephemeralMessageId);
        $this->assertInstanceOf(Type\User::class, $update->message->receiverUser);
        $this->assertSame(987654321, $update->message->receiverUser->id);
        $this->assertSame('Receiver', $update->message->receiverUser->firstName);
    }

    public function testBotCommandIsEphemeral(): void
    {
        $command = Type\BotCommand::fromArray(['command' => 'x', 'description' => 'd', 'is_ephemeral' => true]);

        $this->assertTrue($command->isEphemeral);
    }

    public function testReplyParametersAcceptsEphemeralMessageIdWithoutMessageId(): void
    {
        $decoded = \json_decode(\json_encode(new Type\ReplyParameters(ephemeralMessageId: 7)), true);

        $this->assertSame(['ephemeral_message_id' => 7], $decoded);
        $this->assertArrayNotHasKey('message_id', $decoded);
    }

    public function testReplyParametersStillAcceptsMessageIdPositionally(): void
    {
        $decoded = \json_decode(\json_encode(new Type\ReplyParameters(5)), true);

        $this->assertSame(5, $decoded['message_id']);
    }

    /**
     * Appending rather than inserting is what keeps positional construction working for existing
     * callers. assertContains would not notice a mid-signature insertion; asserting the position does.
     */
    public function testEphemeralParametersIsAlwaysTheLastConstructorParameter(): void
    {
        $expected = [
            'SendMessage', 'SendAnimation', 'SendAudio', 'SendDocument', 'SendLivePhoto',
            'SendPhoto', 'SendSticker', 'SendVideo', 'SendVideoNote', 'SendVoice',
            'SendContact', 'SendLocation', 'SendVenue', 'SendRichMessage',
        ];

        foreach ($expected as $short) {
            $names = \array_map(
                static fn(\ReflectionParameter $p) => $p->getName(),
                (new \ReflectionClass('Luzrain\\TelegramBotApi\\Method\\' . $short))->getConstructor()->getParameters(),
            );

            $this->assertSame('ephemeralMessageParameters', \end($names), $short . ': parameter must be appended last');
        }
    }

    public function testReplyParametersKeepsThreeArgumentPositionalCallsIntact(): void
    {
        $decoded = \json_decode(\json_encode(new Type\ReplyParameters(123, null, true)), true);

        $this->assertSame(['message_id' => 123, 'allow_sending_without_reply' => true], $decoded);
        $this->assertArrayNotHasKey('ephemeral_message_id', $decoded);
    }
}
