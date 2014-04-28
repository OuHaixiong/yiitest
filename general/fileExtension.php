<?php
// PHP 获取文件的扩展名的6种方法
$file = dirname(__FILE__) . '/ouhaixiong.JPG';

// 1 不包括点（.）
//$extension = substr(strrchr($file, '.'), 1);

// 2 不包括点（.）
//$extension = substr($file, strrpos($file, '.')+1);

// 3 不包括点（.）
//$extension = end(explode('.', $file));

// 4 不包括点（.）
//$info = pathinfo($file);
//$extension = $info['extension'];

// 5 不包括点（.）
//$extension = pathinfo($file, PATHINFO_EXTENSION);

// 6 返回的是文件类型 ； 如： image/jpeg
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$extension = finfo_file($finfo, $file) ;
finfo_close($finfo);

var_dump( $extension );