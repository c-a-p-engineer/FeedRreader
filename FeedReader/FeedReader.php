<?php

require_once 'RssReader.php';
require_once 'AtomReader.php';

class FeedReader
{
    public static function loadFeed(string $url): array
    {
        // URL判定
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return [];
        }

        $feed = simplexml_load_file($url);
        if ($feed === false) {
            return [];
        }

        if (strlen((string)$feed->channel->title) > 0) {
            return RssReader::load($url);
        } elseif (strlen((string)$feed->title) > 0) {
            return AtomReader::load($url);
        }
        return [];
    }
}
