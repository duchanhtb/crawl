<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="duchanh" />
    <meta http-equiv="refresh" content="5" />
    <title>Crawl</title>
</head>
<?php
session_start();
include_once('crawl.class.php');
include_once('inc/Db.class.php');

$dbInfo = [
    'dbHost' => 'localhost',
    'dbName' => 'crawl',
    'dbUser' => 'crawl',
    'dbPass' => '123456',
    'dbSesName' => '',
    'dbHostRead' => 'localhost'
];
DB::config($dbInfo);

$maxRow = DB::for_table('product_link')
        ->order_by_desc('id')
        ->find_one();

if($maxRow){
    $page = $maxRow->page + 1;
}else{
    $page = 1;
}

$link = 'https://dogcatjan.com/collections/all?page='.$page;

$H_Crawl = new H_Crawl();
$html_content = $H_Crawl->getHtml($link);
$links = $H_Crawl->getLink($html_content, 'div.grid-uniform div.grid__item a');

foreach ($links as $link){
    $data[$link] = 1;
}

echo "trang:".$page.'<br/>';
foreach ($data as $link=>$a) {
    echo 'https://dogcatjan.com/' . $link . '<br/>';
    $row = DB::for_table('product_link')->create();
    $row->url = $link;
    $row->page = $page;
    $row->save();
}
?>