<?php

namespace App\Support;

class LessonContentSanitizer
{
    /** @var list<string> */
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'ul', 'ol', 'li',
        'a', 'h2', 'h3', 'h4', 'blockquote',
    ];

    public function sanitize(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $trimmed = trim($html);
        if ($trimmed === '') {
            return null;
        }

        $allowed = '<'.implode('><', self::ALLOWED_TAGS).'>';
        $clean = strip_tags($trimmed, $allowed);

        $clean = preg_replace_callback(
            '/<a\s+[^>]*href\s*=\s*(["\'])(.*?)\1[^>]*>/i',
            function (array $matches): string {
                $href = trim($matches[2]);
                if ($href === '' || preg_match('/^\s*javascript:/i', $href) || preg_match('/^\s*data:/i', $href)) {
                    return '<a>';
                }

                $safe = htmlspecialchars($href, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                return '<a href="'.$safe.'" rel="noopener noreferrer">';
            },
            $clean
        ) ?? $clean;

        $clean = preg_replace('/\son\w+\s*=\s*("|\').*?\1/i', '', $clean) ?? $clean;

        return $clean;
    }
}
