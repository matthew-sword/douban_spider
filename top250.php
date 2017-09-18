<?php
header('Content-Type: text/html; charset: utf-8');
require ('curl_get.php');   //获取html页面
require ('simple_html_dom.php');
/*$url = 'https://movie.douban.com/subject/26634179/';
$url = 'https://movie.douban.com/subject/26270502/';
$url = 'https://movie.douban.com/top250';


$html = get_html($url);

$douban = new simple_html_dom(); //创建simple_html_dom对象douban

$douban->load($html);*/
function show_list()    //爬取一页暂停10s,可加入文件写入
{
    $i = 0;
    $rank = 1;
    $url = 'https://movie.douban.com/top250';
    while($i < 10)
    {
        $html = get_html($url);

        $douban = new simple_html_dom(); //创建simple_html_dom对象douban

        $douban->load($html);

        $names = $douban->find('span.title,span.other'); //只爬取主名

        $reg = '/&nbsp;\/.*/';//html中&nbsp表示空格
        foreach ($names as $key => $value)
        {
            if (!preg_match($reg,$value))
            {
               echo "<br>".$rank++." ";
            }
            echo "$value";
        }

        //暂停爬取10s
        sleep(10);
        //url生成
        $i++;
        $url = 'https://movie.douban.com/top250?start='.(string)($i*25).'&filter=';

    }
}
show_list();

function show_detail()
{

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