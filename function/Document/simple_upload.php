
<?php
header("Content-Type: text/html;charset=utf-8");

/**
* 功能：上传文件
* @author gaoqing
* 2015年6月29日
*/

if (isset($_POST['upload_button'])) {
    
    //判断是否上传了图片
    include '../UploadDownload/UploadFile.class.php';
    
    $uploadFile = new UploadFile($_FILES['fileName'], "image");
    
    echo $uploadFile->upload_file();
}
?>

<form action = "#" method = "POST" enctype="multipart/form-data">
    <label>上传文件：</label>
    <input type="file" name = "fileName" value = "">
    <input type="submit" name = "upload_button" value = "上传文件">
</form>