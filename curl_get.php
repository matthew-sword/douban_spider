<?php

function get_html($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);  // 设置要抓取的页面地址

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 抓取结果直接返回（如果为0，则直接输出内容到页面）

    //关闭SSL证书验证
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $res = curl_exec($ch);   //返回html字符串

    curl_close($ch);

    echo "get html successfully...<br>";
    return $res;
}

?>