
<?php
header("Content-Type: text/html;charset=utf-8");

/**
* 功能：下载文件
* @author gaoqing
* 2015年6月29日
*/

//找到要下载的文件
$file_name = "壁纸.jpg";
$download_file = "." . DIRECTORY_SEPARATOR . "Upload" . DIRECTORY_SEPARATOR . $file_name;

if (strstr(PHP_OS, "WIN")) {
    $download_file = mb_convert_encoding($download_file, "gbk", "utf-8");
}
$file_size = filesize($download_file);

//得到文件资源
$download_file_resource = fopen($download_file, "r");

//设置下载的参数
header("Content-type: application/octet-stream");
header("Accept-Ranges: bytes");
header("Accept-Length:" . $file_size);
header("Content-Disposition: attachment; filename=" . $file_name);

//读取文件
echo fread($download_file_resource, $file_size);

fclose($download_file_resource);

exit();
?>

