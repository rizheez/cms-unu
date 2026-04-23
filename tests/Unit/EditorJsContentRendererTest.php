<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\EditorJsContentRenderer;
use Tests\TestCase;

class EditorJsContentRendererTest extends TestCase
{
    public function test_it_returns_legacy_html_without_json_rendering(): void
    {
        $html = '<p>Konten lama.</p>';

        $this->assertSame($html, (string) app(EditorJsContentRenderer::class)->render($html));
    }

    public function test_it_renders_editorjs_blocks(): void
    {
        $content = [
            'time' => 1,
            'blocks' => [
                [
                    'type' => 'paragraph',
                    'data' => [
                        'text' => 'Konten baru.',
                    ],
                ],
            ],
            'version' => '2.30.0',
        ];

        $html = (string) app(EditorJsContentRenderer::class)->render($content);

        $this->assertStringContainsString('Konten baru.', $html);
    }

    public function test_it_converts_legacy_html_to_editorjs_document(): void
    {
        $document = app(EditorJsContentRenderer::class)->toEditorJsDocument('<p>Paragraf lama.</p><p>Kedua.</p>');

        $this->assertSame('paragraph', $document['blocks'][0]['type']);
        $this->assertSame('Paragraf lama.<br><br>Kedua.', $document['blocks'][0]['data']['text']);
    }
}
