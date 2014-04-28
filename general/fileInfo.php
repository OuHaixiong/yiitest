<?php
// 下面是调用php的扩展 Fileinfo
$filename = 'wo.html';
// $mime = mime_content_type($filename);
$fileInfo = finfo_open(FILEINFO_MIME);
//$finfo = finfo_open(FILEINFO_MIME, "/www/magic"); // return mime type ala mimetype extension
if (!$fileInfo) {
    echo "Opening fileinfo database failed";
    exit();
}
// 输出文件的mime类型
var_dump(finfo_file($fileInfo, $filename)); /* get mime-type for a specific file */
finfo_close($fileInfo); /* close connection */