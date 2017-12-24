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


$urlObj = DB::for_table('product_link')
        ->where_equal('crawled','0')
        ->order_by_asc('id')
        ->find_one();

if($urlObj){
    $url = $urlObj->url;
    $url = 'https://dogcatjan.com'.$url;

}else{
    echo 'finish';
    die;
}


$H_Crawl = new H_Crawl();
$html_content = $H_Crawl->getHtml($url);

$html = str_get_html($html_content);

// product name
$title = $html->find('h1[itemprop="name"]', 0)->innertext;
echo $title.'<br/>';

//price
$price = $html->find('span#ProductPrice', 0)->innertext;
$price = trim($price);


// desc
$desc = $html->find('div#responsiveTabsDemo div#description div[itemprop="description"]', 0)->innertext;
$desc = trim($desc);


foreach ($html->find('ul#ProductThumbs li a') as $e) {
    $images[] = $e->href;
}



foreach ($html->find('select#productSelect option') as $e) {
    $colors[] = $e->innertext;
}

// insert

$row = DB::for_table('product')->create();
$row->name = $title;
$row->price = trim($price, '$');
$row->image_url = implode('|', $images);
$row->color_info = implode('|', $colors);
$row->desc = $desc;
$row->url = $url;
$row->date = date('Y-m-d H:i:s', time());
$row->save();

echo $url;

// update row
$urlObj->crawled = 1;
$urlObj->save();













?>