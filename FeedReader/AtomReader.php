<?php

class AtomReader {
    public static function load(string $url):array
    {
        // 記事を格納
        $posts = [];
        // URL判定
        if( !filter_var( $url, FILTER_VALIDATE_URL ) ){
            return [];
        }
        // feed取得
        $feed = simplexml_load_file($url);
        // サイト情報
        $siteTitle = (string)$feed->title;
        $siteUrl = (string)$feed->link->attributes()->href;
        $siteDescription = (string)$feed->description;

        // 記事情報取得
        foreach($feed->entry as $item){
            $title = (string)$item->title;
            $date = date("Y-m-d H:i:s", strtotime($item->published));
            $link = (string)$item->link->attributes()->href;
            $description = (string)$item->content;

            $posts[] = [
                'siteTitle' => $siteTitle,
                'siteUrl' => $siteUrl,
                'siteDescription' => $siteDescription,
                'title' => $title,
                'date' => $date,
                'link' => $link,
                'description' => $description,
            ];
        }
        return $posts;
    }
}
