<?php

require_once 'FeedReader/FeedReader.php';
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

function sortByKey($keyName, $sortOrder, $array)
{
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
    if (count($result)  > 0) {
        $posts = array_merge($posts, $result);
    }
}

// ソートを変更
$posts = sortByKey('date', SORT_DESC, $posts);

$html = <<<HTML
<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Rss Reader</title>
  </head>
  <body>
HTML;

$colors = [
  ' bg-secondary text-white',
  ' bg-light text-dark',
];
$i = 0;

$html .= '<ul class="list-group">';
foreach ($posts as $post) {
    $html .= '<a href="' . $post['link'] . '" class="list-group-item list-group-item-action' . $colors[($i % count($colors))] . '" target="_blank">' . $post['date'] . ' | ' . $post['title'] . ' | ' . $post['siteTitle'] . '</a>';
    $i++;

    if ($i == ($setting['count'] ?? 100)) {
        continue;
    }
}
$html .= '</ul">';

$html .= <<<HTML
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
HTML;
file_put_contents('./docs/index.html', $html);
