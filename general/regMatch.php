<?php
header("Content-Type:text/html; charset=utf-8");
// 下面是php正则表达式的练习

// i 不区别大小写


// 从 URL 中取得主机名
preg_match('/^(http:\/\/)?([^\/]+)/i', 'http://www.xiqiyanyan.com/index.html', $matches);
$host = $matches[2];
// 从主机名中取得后面两段
preg_match('/[^\.\/]+\.[^\.\/]+$/', $host, $matches);
//echo "域名为：{$matches[0]}";


//下面的例子演示了将文本中所有 <pre></pre> 标签内的关键字（php）显示为红色
$str = "<pre>学习php是一件快乐的事。</pre><pre>所有的phper需要共同努力！</pre>";
$kw = "php";
preg_match_all('/<pre>([\s\S]*?)<\/pre>/',$str,$mat);
for($i=0;$i<count($mat[0]);$i++){
    $mat[0][$i] = $mat[1][$i];
    $mat[0][$i] = str_replace($kw, '<span style="color:#ff0000">'.$kw.'</span>', $mat[0][$i]);
    $str = str_replace($mat[1][$i], $mat[0][$i], $str);
}
//echo $str;


// 正则匹配中文汉字根据页面编码不同而略有区别：
// GBK/GB2312编码：[x80-xff]+ 或 [xa1-xff]+ \\ 未验证
// UTF-8编码：[\x{4e00}-\x{9fa5}]+/u
$str = '学习php是一件快乐的事。';
preg_match_all('/[\x{4e00}-\x{9fa5}]+/u', $str, $matches);
print_r($matches);

//\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*  匹配Email地址

