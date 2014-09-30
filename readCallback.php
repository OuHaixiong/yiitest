<?php
$string = file_get_contents('temp/abc');
// $string = json_decode($string);
echo '<h2>下面是我们获取的数据（get&post）</h2>';
print_r($string);
echo '<br />';

echo '<h2>下面是pay下面获取的数据</h2>';
$xmlString = file_get_contents('temp/xml');
$xmlString = json_decode($xmlString);
print_r($xmlString);