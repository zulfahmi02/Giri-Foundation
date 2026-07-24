<?php

namespace App\Support;

class YouTubeVideo
{
    public static function videoId(?string $youtubeUrl): ?string
    {
        $youtubeReference = trim((string) $youtubeUrl);

        if (preg_match('~^[\w-]{11}$~', $youtubeReference) === 1) {
            return $youtubeReference;
        }

        if (preg_match('~(?:v=|youtu\.be/|embed/|shorts/|live/)([\w-]{11})~', $youtubeReference, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    public static function embedUrl(?string $youtubeUrl): ?string
    {
        $videoId = self::videoId($youtubeUrl);

        return $videoId ? "https://www.youtube-nocookie.com/embed/{$videoId}" : null;
    }

    public static function thumbnailUrl(?string $youtubeUrl): ?string
    {
        $videoId = self::videoId($youtubeUrl);

        return $videoId ? "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg" : null;
    }
}
