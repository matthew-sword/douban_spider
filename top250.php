<?php
header('Content-Type: text/html; charset: utf-8');

require ('curl_get.php');   //获取html页面
require ('simple_html_dom.php');

//$url = 'https://movie.douban.com/subject/26634179/';
//$url = 'https://movie.douban.com/subject/26270502/';
$url = 'https://movie.douban.com/top250';


$html = get_html($url);

$douban = new simple_html_dom(); //创建simple_html_dom对象douban

$douban->load($html);


//查看电影名单

$names = $douban->find('span.title,span.other');

foreach ($names as $key => $value)
{
    if (!preg_match('/ \\ */',$value))
    {
       echo "<br>";
    }

    echo "$value";

}




/*$links = $douban->find('a');

foreach ($links as $key => $value)
{
    echo "$value->href<br>";
}*/

/*$posters = $douban->find('img');

foreach ($posters as $key => $value)
{
    echo "$value->src<br>";
}*/

?>