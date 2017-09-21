<?php
//header('Content-Type: text/html; charset: utf-8');
require ('curl_get.php');   //获取html页面
require ('simple_html_dom.php'); //提取html页面
require ('conn/conn.php'); //连接数据库


function get_list($conn)    //爬取一页暂停10s,可加入文件写入
{
    $i = 0;
    $rank = 1;
    $url = 'https://movie.douban.com/top250';
    $list = fopen("top_list.txt","w+");
    echo "file opened...\n";

    //创建电影数据表  记得关闭链接！！！
    $m_table = $conn->prepare("CREATE TABLE IF NOT EXISTS top250(
                                        rank INT PRIMARY KEY AUTO_INCREMENT,
                                        link VARCHAR(100))    DEFAULT CHARSET=utf8 ");
    if ($m_table->execute())
        echo "table cteated...\n";

    //循环爬取网页
    while($i < 10)
    {
        //获取网页内容
        $html = get_html($url);
        $douban = new simple_html_dom(); //创建simple_html_dom对象douban
        $douban->load($html);

        //提取电影名
        $names = $douban->find('span.title,span.other'); //爬取电影名
        $name_reg = '/&nbsp;\/.*/'; //html中&nbsp表示空格

        //提取详情链接
        $addrs = $douban->find('a');
        $addr_reg ='/https:\/\/movie\.douban\.com\/subject.*/';

        //循环输出top250名单
        foreach ($names as $key => $value)
        {
            if (!preg_match($name_reg,$value))
            {
               echo "\n\n".$rank." ";
               fwrite($list,"\n\n"."$rank");
               $rank++;
            }

            //过滤html标签
            $value = strip_tags($value);
            $clean = str_replace('&nbsp;','',$value);

            //输出&写入
            echo $clean;
            fwrite($list,$clean);
        }

        //详情链接写入mysql
        foreach ($addrs as $key => $value)
        {
            //匹配电影详情页的url && 去重
            if ( preg_match($addr_reg,$value->href) &&
                 !strstr($addrs[$key]->href,$addrs[$key+1]->href) )
            {
                $add = $value->href;
                $m_table = $conn->prepare("INSERT INTO top250 (link) VALUES (:link)");
                $m_table->bindParam(':link',$add);
                $m_table->execute();
            }
        }

        //暂停爬取5~10s
        /*$time = rand(5,10);
        echo "\n\nsleeping($time s)...\n";
        sleep($time);*/

        //url生成
        $i++;
        $url = 'https://movie.douban.com/top250?start='.(string)($i*25).'&filter=';

        //清空dom内存
        $douban->clear();
    }

    //关闭文件 & mysql链接
    fclose($list);


}


function get_detail($conn)
{
//    /*$url = 'https://movie.douban.com/top250';
//
//    $html = get_html($url);
//
//    $douban = new simple_html_dom(); //创建simple_html_dom对象douban
//
//    $douban->load($html);
//
//    $links = $douban->find('li.item');
//    foreach ($links as $key => $value)
//    {
//        echo "$value<br>";
//
//    }*/

    for($i = 1;$i <= 2; $i++)
    {
        //获取详情页url
        $m_table = $conn->prepare("SELECT link FROM top250 WHERE rank = $i");
        $m_table->execute();
        $link = $m_table->fetch();

        //爬取详情页
        $html = get_html($link[0]);
        $douban = new simple_html_dom(); //创建simple_html_dom对象douban
        $douban->load($html);

       /* //提取海报url
        $posters = $douban->find('.nbgnbg');

        //下载海报
        foreach ($posters as $value)
        {
            $src = $value->children[0]->src;

            //获取海报
            ob_start();
            readfile($src);
            $img = ob_get_contents();
            ob_end_clean();

            //保存海报
            $picname = "$i".'.jpg';
            $file_img=fopen("poster/".$picname,"w+");
            fwrite($file_img,$img);
            fclose($file_img);
        }


        //获取简介
        $short = $douban->find('div[id=link-report]');

        //保存简介
        foreach ($short as $value)
        {
            //提取简介
            $summary = $value->children[0]->innertext;
            $summary = strip_tags($summary);

            //写入文件
            $sumname = "$i".'.txt';
            $file_sum = fopen("summary/".$sumname,"w+");
            fwrite($file_sum,$summary);
            fclose($file_sum);
        }*/


        //获取影评
        $comments = $douban->find('div.comment');

        foreach ($comments as $value)
        {
            $comment = $value->children[1]->innertext;
            echo $comment."\n\n";
        }


        //清空内存
        $douban->clear();
    }





}

//get_list($conn);

get_detail($conn);

$conn = null;




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