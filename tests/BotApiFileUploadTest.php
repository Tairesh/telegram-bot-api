<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test;

use Luzrain\TelegramBotApi\BotApi;
use Luzrain\TelegramBotApi\Method;
use Luzrain\TelegramBotApi\Test\Helper\TestHttpClient;
use Luzrain\TelegramBotApi\Test\Helper\TestPsrFactory;
use Luzrain\TelegramBotApi\Type;
use PHPUnit\Framework\TestCase;

final class BotApiFileUploadTest extends TestCase
{
    private const MESSAGE_RESPONSE = '{"ok":true,"result":{"message_id":1,"date":1,"chat":{"id":1,"type":"private"}}}';

    /**
     * Uploaded payloads must not themselves contain the literal "attach://",
     * otherwise the multipart body assertions match the file content instead of the reference.
     */
    private const FILE_A = __DIR__ . '/data/events/command.json';
    private const FILE_B = __DIR__ . '/data/events/callbackQuery.json';
    private const FILE_C = __DIR__ . '/data/events/channelPost.json';

    /**
     * InputFile::getUniqueName() produces uniqid('attach.', true), i.e. "attach.<hex>.<digits>".
     * Matching that exact shape avoids both the JSON-escaped "attach:\/\/" form and any
     * accidental match inside an uploaded file's own contents.
     *
     * @return array{parts: list<string>, attachRefs: list<string>, body: string}
     */
    private function inspectRequest(TestHttpClient $client): array
    {
        $body = (string) $client->getLastRequest()->getBody();

        \preg_match_all('/name="([^"]+)"/', $body, $partMatches);
        \preg_match_all('#attach:(?:\\\\?/){2}(attach\.[0-9a-f]+\.[0-9]+)#', $body, $refMatches);

        return [
            'parts' => $partMatches[1],
            'attachRefs' => \array_values(\array_unique($refMatches[1])),
            'body' => $body,
        ];
    }

    private function createApi(TestPsrFactory $factory, TestHttpClient $client): BotApi
    {
        return new BotApi(
            requestFactory: $factory,
            streamFactory: $factory,
            client: $client,
            token: 'TEST_TOKEN',
        );
    }

    public function testTopLevelInputFileIsUploadedExactlyOnce(): void
    {
        $factory = new TestPsrFactory();

        $this->createApi($factory, new TestHttpClient(self::MESSAGE_RESPONSE))->call(new Method\SendPhoto(
            chatId: 1,
            photo: new Type\InputFile(__FILE__),
        ));

        $this->assertSame([__FILE__], $factory->getStreamFromFileCalls());
    }

    public function testSeveralTopLevelInputFilesAreUploaded(): void
    {
        $factory = new TestPsrFactory();

        $this->createApi($factory, new TestHttpClient(self::MESSAGE_RESPONSE))->call(new Method\SendVideo(
            chatId: 1,
            video: new Type\InputFile(__FILE__),
            thumbnail: new Type\InputFile(__DIR__ . '/BaseTypeTest.php'),
        ));

        $calls = $factory->getStreamFromFileCalls();
        \sort($calls);
        $expected = [__FILE__, __DIR__ . '/BaseTypeTest.php'];
        \sort($expected);

        $this->assertSame($expected, $calls);
    }

    public function testInputFilesNestedInsideMediaArrayAreUploaded(): void
    {
        $factory = new TestPsrFactory();

        $this->createApi($factory, new TestHttpClient('{"ok":true,"result":[]}'))->call(new Method\SendMediaGroup(
            chatId: 1,
            media: [
                new Type\InputMediaPhoto(media: new Type\InputFile(__FILE__)),
                new Type\InputMediaVideo(
                    media: new Type\InputFile(__DIR__ . '/BaseTypeTest.php'),
                    thumbnail: new Type\InputFile(__DIR__ . '/UpdateTypeTest.php'),
                ),
            ],
        ));

        $this->assertCount(3, $factory->getStreamFromFileCalls());
    }

    public function testInputFileDeepInsideRichBlockTreeIsUploaded(): void
    {
        $factory = new TestPsrFactory();

        $this->createApi($factory, new TestHttpClient(self::MESSAGE_RESPONSE))->call(new Method\SendRichMessage(
            chatId: 1,
            richMessage: new Type\InputRichMessage(blocks: [
                new Type\InputRichBlock\InputRichBlockDetails(
                    summary: 'S',
                    blocks: [
                        new Type\InputRichBlock\InputRichBlockCollage(blocks: [
                            new Type\InputRichBlock\InputRichBlockPhoto(
                                photo: new Type\InputMediaPhoto(media: new Type\InputFile(__FILE__)),
                            ),
                        ]),
                    ],
                ),
            ]),
        ));

        $this->assertSame([__FILE__], $factory->getStreamFromFileCalls());
    }

    public function testEveryAttachReferenceHasMatchingMultipartPart(): void
    {
        $factory = new TestPsrFactory();
        $client = new TestHttpClient(self::MESSAGE_RESPONSE);

        $this->createApi($factory, $client)->call(new Method\SendRichMessage(
            chatId: 1,
            richMessage: new Type\InputRichMessage(blocks: [
                new Type\InputRichBlock\InputRichBlockCollage(blocks: [
                    new Type\InputRichBlock\InputRichBlockPhoto(
                        photo: new Type\InputMediaPhoto(media: new Type\InputFile(self::FILE_A)),
                    ),
                    new Type\InputRichBlock\InputRichBlockPhoto(
                        photo: new Type\InputMediaPhoto(media: new Type\InputFile(self::FILE_B)),
                    ),
                ]),
            ]),
        ));

        ['parts' => $parts, 'attachRefs' => $refs] = $this->inspectRequest($client);

        $this->assertCount(2, $refs, 'both nested files must be referenced as attach://');
        foreach ($refs as $ref) {
            $this->assertContains($ref, $parts, \sprintf('attach://%s has no multipart part named %s', $ref, $ref));
        }
    }

    public function testTopLevelFileIsReferencedByAttachPathNotByFilePath(): void
    {
        $factory = new TestPsrFactory();
        $client = new TestHttpClient(self::MESSAGE_RESPONSE);

        $this->createApi($factory, $client)->call(new Method\SendPhoto(
            chatId: 1,
            photo: new Type\InputFile(self::FILE_A),
        ));

        ['parts' => $parts, 'attachRefs' => $refs, 'body' => $body] = $this->inspectRequest($client);

        $this->assertCount(1, $refs);
        $this->assertContains('photo', $parts);
        $this->assertContains($refs[0], $parts);
        $this->assertStringContainsString('attach://' . $refs[0], $body);
    }

    public function testCallWithoutParametersIsSentAsGet(): void
    {
        $factory = new TestPsrFactory();
        $client = new TestHttpClient('{"ok":true,"result":{"id":1,"is_bot":true,"first_name":"B"}}');

        $this->createApi($factory, $client)->call(new Method\GetMe());

        $this->assertSame('GET', $client->getLastRequest()->getMethod());
    }

    public function testCallWithParametersIsSentAsMultipartPost(): void
    {
        $factory = new TestPsrFactory();
        $client = new TestHttpClient(self::MESSAGE_RESPONSE);

        $this->createApi($factory, $client)->call(new Method\SendMessage(chatId: 1, text: 'hi'));

        $request = $client->getLastRequest();

        $this->assertSame('POST', $request->getMethod());
        $this->assertStringContainsString('multipart/form-data; boundary=', $request->getHeaderLine('Content-Type'));
    }

    public function testRequestUrlContainsTokenAndMethodName(): void
    {
        $factory = new TestPsrFactory();
        $client = new TestHttpClient(self::MESSAGE_RESPONSE);

        $this->createApi($factory, $client)->call(new Method\SendMessage(chatId: 1, text: 'hi'));

        $this->assertSame(
            'https://api.telegram.org/botTEST_TOKEN/sendMessage',
            $client->getLastRequest()->getRequestTarget(),
        );
    }

    public function testInputFilesInsidePaidMediaAreUploaded(): void
    {
        $factory = new TestPsrFactory();

        $this->createApi($factory, new TestHttpClient(self::MESSAGE_RESPONSE))->call(new Method\SendPaidMedia(
            chatId: 1,
            starCount: 5,
            media: [
                new Type\InputPaidMediaPhoto(media: new Type\InputFile(__FILE__)),
                new Type\InputPaidMediaVideo(
                    media: new Type\InputFile(__DIR__ . '/BaseTypeTest.php'),
                    thumbnail: new Type\InputFile(__DIR__ . '/UpdateTypeTest.php'),
                ),
            ],
        ));

        $this->assertCount(3, $factory->getStreamFromFileCalls());
    }

    /**
     * Regression guard for a behaviour the recursive walk fixed as a side effect: the previous
     * flat scan only descended into InputMedia/InputPaidMedia, so InputSticker::$sticker was
     * serialized as attach://<name> with no matching multipart part and Telegram rejected the call.
     */
    public function testInputFileInsideInputStickerIsUploaded(): void
    {
        $factory = new TestPsrFactory();
        $client = new TestHttpClient();

        $this->createApi($factory, $client)->call(new Method\CreateNewStickerSet(
            userId: 1,
            name: 'pack_by_bot',
            title: 'Pack',
            stickers: [
                new Type\InputSticker(
                    sticker: new Type\InputFile(self::FILE_A),
                    format: 'static',
                    emojiList: ['*'],
                ),
            ],
        ));

        ['parts' => $parts, 'attachRefs' => $refs] = $this->inspectRequest($client);

        $this->assertCount(1, $factory->getStreamFromFileCalls());
        $this->assertCount(1, $refs);
        $this->assertContains($refs[0], $parts, 'attach:// reference must have a matching multipart part');
    }
}
