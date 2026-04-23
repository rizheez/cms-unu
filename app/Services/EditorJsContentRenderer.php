<?php

declare(strict_types=1);

namespace App\Services;

use Athphane\FilamentEditorjs\FilamentEditorjs;
use Illuminate\Support\HtmlString;
use JsonException;

class EditorJsContentRenderer
{
    /**
     * @param  array<string, mixed>|string|null  $content
     */
    public function render(array|string|null $content): HtmlString
    {
        if ($content === null || $content === []) {
            return new HtmlString('');
        }

        if (is_string($content)) {
            return new HtmlString($content);
        }

        return new HtmlString(FilamentEditorjs::renderContent($content));
    }

    /**
     * @param  array<string, mixed>|string|null  $content
     */
    public function plainText(array|string|null $content): string
    {
        return trim(strip_tags((string) $this->render($content)));
    }

    /**
     * @param  array<string, mixed>|string|null  $content
     */
    public function wordCount(array|string|null $content): int
    {
        if ($content === null || $content === []) {
            return 0;
        }

        if (is_string($content)) {
            return str_word_count(strip_tags($content));
        }

        return FilamentEditorjs::countWords($content);
    }

    /**
     * @param  array<string, mixed>|string|null  $content
     * @return array<string, mixed>|null
     */
    public function toEditorJsDocument(array|string|null $content): ?array
    {
        if ($content === null || $content === []) {
            return null;
        }

        if (is_array($content)) {
            return $content;
        }

        $trimmedContent = trim($content);

        if ($trimmedContent === '') {
            return null;
        }

        try {
            $decoded = json_decode($trimmedContent, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $decoded = null;
        }

        if (is_array($decoded) && isset($decoded['blocks'])) {
            return $decoded;
        }

        return [
            'time' => now()->getTimestampMs(),
            'blocks' => [
                [
                    'type' => 'paragraph',
                    'data' => [
                        'text' => $this->legacyHtmlToParagraphText($trimmedContent),
                    ],
                ],
            ],
            'version' => '2.30.0',
        ];
    }

    private function legacyHtmlToParagraphText(string $html): string
    {
        $text = preg_replace('/<\/p>\s*<p[^>]*>/i', '<br><br>', $html) ?? $html;
        $text = preg_replace('/^<p[^>]*>/i', '', $text) ?? $text;
        $text = preg_replace('/<\/p>$/i', '', $text) ?? $text;

        return trim($text);
    }
}
