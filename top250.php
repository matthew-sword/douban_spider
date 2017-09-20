<?php
header('Content-Type: text/html; charset: utf-8');
require ('curl_get.php');   //获取html页面
require ('simple_html_dom.php'); //equire
require ('conn/conn.php'); //连接数据库


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
    $list = fopen("top_list.txt","w+");
    echo "file open...\n";
    while($i < 10)
    {
        $html = get_html($url);

        $douban = new simple_html_dom(); //创建simple_html_dom对象douban

        $douban->load($html);

        $names = $douban->find('span.title,span.other'); //爬取电影名

        $reg = '/&nbsp;\/.*/'; //html中&nbsp表示空格

        //循环输出top250名单
        foreach ($names as $key => $value)
        {
            if (!preg_match($reg,$value))
            {
               echo "\n".$rank." ";
               fwrite($list,"\r\n"."$rank");
               $rank++;
            }

            //过滤html标签
            $value = strip_tags($value);
            $clean = str_replace('&nbsp;','',$value);
            //输出&写入
            echo $clean;
            fwrite($list,$clean);
        }

        //暂停爬取10s
        //sleep(10);
        //url生成
        $i++;
        $url = 'https://movie.douban.com/top250?start='.(string)($i*25).'&filter=';
        //清空dom内存
        $douban->clear();
    }
    fclose($list);
}
show_list();

function show_detail()
{
    $url = 'https://movie.douban.com/top250';

    $html = get_html($url);

    $douban = new simple_html_dom(); //创建simple_html_dom对象douban

    $douban->load($html);

    $links = $douban->find('li.item');
    foreach ($links as $key => $value)
    {
        echo "$value<br>";

    }

}

//show_detail();




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