<?php
require_once 'FeedReader/FeedReader.php';

function sortByKey($keyName, $sortOrder, $array) {
    $temp = [];
    foreach ($array as $key => $value) {
        $temp[$key . uniqid()] = $value[$keyName];
    }
    array_multisort($temp, $sortOrder, $array);
    return $array;
}

$setting = parse_ini_file('setting.ini');

// 記事を格納
$posts = [];

foreach ($setting['urls'] as $url) {
    $result = FeedReader::loadFeed($url);
    if(count($result)  > 0 ){
        $posts += $result;
    }
}

// ソートを変更
$posts = sortByKey('date', SORT_DESC, $posts);

$html = '<ul>';
foreach($posts as $post){
    $html .= '<li>' . $post['date'] . ' | ' . $post['title'] . ' | ' . $post['siteTitle'] . '</li>';
}
$html .= '</ul>';
file_put_contents('./docs/index.html', $html);
