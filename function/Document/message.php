
<?php
header("Content-Type:text/html;charset=utf-8");

/**
* 功能：简单的留言系统
* @author gaoqing
* 2015年6月27日
*/

if (isset($_POST['message'])) {
    
    $file = "./message.txt";
    
    $is_touch = touch($file);
    
    if ($is_touch) {
        
        $file_resource = fopen($file, "a");
        
        //要写入的信息
        $message_str = $_POST['user'] . ";" . $_POST['content'] . ";" . date('Y-m-d H:i', $_SERVER['REQUEST_TIME']);
        
        //向文件写入数据
        fwrite($file_resource, $message_str);
        
        fclose($file_resource);
        
    }
    
    
}

?>

<html>
<head>
<title>简单留言系统</title>
</head>


<body>

<form action="#" method = "POST">
留言人：<input type="text" name = "user" value = "" id = "user"><br>
留言信息：
<textarea rows="4" cols="20" name = "content" ></textarea>
<input type="submit" name = "message" value = "提交留言">
</form>

<hr>

<!-- 显示所有的留言信息 Start -->

<!-- 显示所有的留言信息 End -->


</body>
</html>

