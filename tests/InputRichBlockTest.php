<?php

declare(strict_types=1);

namespace Luzrain\TelegramBotApi\Test;

use Luzrain\TelegramBotApi\Type\InputFile;
use Luzrain\TelegramBotApi\Type\InputMediaPhoto;
use Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockCollage;
use Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockDetails;
use Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockDivider;
use Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockList;
use Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockListItem;
use Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockParagraph;
use Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockPhoto;
use Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockSectionHeading;
use PHPUnit\Framework\TestCase;

final class InputRichBlockTest extends TestCase
{
    public function testBlockTypeDiscriminatorsAreSerialized(): void
    {
        $this->assertSame('paragraph', \json_decode(\json_encode(new InputRichBlockParagraph(text: 'x')), true)['type']);
        $this->assertSame('divider', \json_decode(\json_encode(new InputRichBlockDivider()), true)['type']);
        $this->assertSame('heading', \json_decode(\json_encode(new InputRichBlockSectionHeading(text: 'x', size: 2)), true)['type']);
    }

    public function testNullFieldsAreOmitted(): void
    {
        $decoded = \json_decode(\json_encode(new InputRichBlockCollage(blocks: [])), true);

        $this->assertSame('collage', $decoded['type']);
        $this->assertSame([], $decoded['blocks']);
        $this->assertArrayNotHasKey('caption', $decoded);
    }

    public function testNestedBlocksArePreserved(): void
    {
        $tree = new InputRichBlockDetails(
            summary: 'Summary',
            blocks: [
                new InputRichBlockCollage(blocks: [
                    new InputRichBlockPhoto(photo: new InputMediaPhoto(media: new InputFile(__FILE__))),
                ]),
                new InputRichBlockParagraph(text: 'text'),
            ],
            isOpen: true,
        );

        $decoded = \json_decode(\json_encode($tree), true);

        $this->assertSame('details', $decoded['type']);
        $this->assertTrue($decoded['is_open']);
        $this->assertSame('collage', $decoded['blocks'][0]['type']);
        $this->assertSame('photo', $decoded['blocks'][0]['blocks'][0]['type']);
        $this->assertStringStartsWith('attach://', $decoded['blocks'][0]['blocks'][0]['photo']['media']);
        $this->assertSame('paragraph', $decoded['blocks'][1]['type']);
    }

    public function testListItemSerializesNumberingFields(): void
    {
        $decoded = \json_decode(\json_encode(new InputRichBlockList(items: [
            new InputRichBlockListItem(blocks: [new InputRichBlockParagraph(text: 'a')], value: 3, type: 'i'),
        ])), true);

        $this->assertSame('list', $decoded['type']);
        $this->assertSame(3, $decoded['items'][0]['value']);
        $this->assertSame('i', $decoded['items'][0]['type']);
    }

    public function testTableCellsSurviveArrayOfArrayNesting(): void
    {
        $cell = new \Luzrain\TelegramBotApi\Type\RichBlock\RichBlockTableCell(align: 'left', valign: 'top', text: 'c1');

        $decoded = \json_decode(\json_encode(
            new \Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockTable(cells: [[$cell, $cell], [$cell]]),
        ), true);

        $this->assertCount(2, $decoded['cells']);
        $this->assertCount(2, $decoded['cells'][0]);
        $this->assertSame('left', $decoded['cells'][0][0]['align']);
        $this->assertSame('c1', $decoded['cells'][0][0]['text']);
    }

    public function testInputRichMessageCarriesBlocksAndMedia(): void
    {
        $message = new \Luzrain\TelegramBotApi\Type\InputRichMessage(
            blocks: [new InputRichBlockParagraph(text: 'p')],
            media: [new \Luzrain\TelegramBotApi\Type\InputRichMessageMedia(
                id: 'pic-1',
                media: new InputMediaPhoto(media: 'file_id_here'),
            )],
        );

        $decoded = \json_decode(\json_encode($message), true);

        $this->assertSame('paragraph', $decoded['blocks'][0]['type']);
        $this->assertSame('pic-1', $decoded['media'][0]['id']);
        $this->assertArrayNotHasKey('html', $decoded);
    }

    public function testNewInputBlocksSerializeCorrectDiscriminators(): void
    {
        $buttons = \json_decode(\json_encode(new \Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockButtons(
            buttons: [new \Luzrain\TelegramBotApi\Type\RichMessageButton(text: 'A', callbackData: 'x')],
            align: 'center',
        )), true);
        $quote = \json_decode(\json_encode(new \Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockExpandableBlockQuotation(
            text: 'q',
            credit: 'c',
        )), true);
        $doc = \json_decode(\json_encode(new \Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockDocument(
            document: new \Luzrain\TelegramBotApi\Type\InputMediaDocument(media: 'file_id'),
        )), true);

        $this->assertSame('buttons', $buttons['type']);
        $this->assertSame('center', $buttons['align']);
        $this->assertSame('x', $buttons['buttons'][0]['callback_data']);
        $this->assertSame('expandable_blockquote', $quote['type']);
        $this->assertSame('c', $quote['credit']);
        $this->assertSame('document', $doc['type']);
    }

    public function testInputRichBlockTableIsCompact(): void
    {
        $decoded = \json_decode(\json_encode(
            new \Luzrain\TelegramBotApi\Type\InputRichBlock\InputRichBlockTable(cells: [], isCompact: true),
        ), true);

        $this->assertTrue($decoded['is_compact']);
        $this->assertArrayNotHasKey('is_bordered', $decoded);
    }

    /**
     * The realistic defect in a Bot API catch-up is a mistyped TYPE string, and the five-check
     * gate cannot see it. The incoming RichBlock family predates this upgrade and is known good,
     * so pinning every outgoing TYPE against its incoming twin catches a typo on either side.
     *
     * @return iterable<string, array{string, string}>
     */
    public static function blockClassPairs(): iterable
    {
        foreach (\glob(__DIR__ . '/../src/Type/InputRichBlock/InputRichBlock*.php') as $file) {
            $short = \basename($file, '.php');
            if ($short === 'InputRichBlock' || $short === 'InputRichBlockListItem') {
                continue;
            }

            yield $short => [
                'Luzrain\\TelegramBotApi\\Type\\InputRichBlock\\' . $short,
                'Luzrain\\TelegramBotApi\\Type\\RichBlock\\' . \substr($short, 5),
            ];
        }
    }

    /**
     * @dataProvider blockClassPairs
     * @param class-string $inputClass
     * @param class-string $outputClass
     */
    public function testEveryInputBlockTypeMatchesItsOutgoingTwin(string $inputClass, string $outputClass): void
    {
        $this->assertTrue(\class_exists($outputClass), $outputClass . ' must exist');
        $this->assertSame(
            \constant($outputClass . '::TYPE'),
            \constant($inputClass . '::TYPE'),
            $inputClass . '::TYPE must equal ' . $outputClass . '::TYPE',
        );
    }

    public function testAllTwentyFourBlockPairsAreCovered(): void
    {
        $this->assertCount(24, \iterator_to_array(self::blockClassPairs()));
    }

    public function testBlockWithoutFieldsSerializesAsJsonObjectNotArray(): void
    {
        $this->assertSame('{"type":"divider"}', \json_encode(new InputRichBlockDivider()));
    }
}
