<?php
header("Content-Type: text/html;charset=utf-8");

/**
* 功能：curl 测试
* @author gaoqing
* 2015年7月23日
*/

include '../tools/FirePHPCore/fb.php';

/*
 * 1、初始化 curl 资源
 * 2、设置 curl 资源的相关配置
 * 3、执行 curl 
 * 4、获取执行后的信息
 */
 
 //连接相关参数
 $URL = "http://localhost/Common_PHP/gaoqing/post.php";
 $timeout = 60;
 $post_fields = array( 
                        "name" => urlencode("gaoqing") ,
                        "pass" => urlencode("123456")
                      );
 
 $curl = curl_init();
 
 $opt_arr = array( 
                    CURLOPT_URL => $URL ,
                    CURLOPT_RETURNTRANSFER => 1 ,
                    CURLOPT_CONNECTTIMEOUT => $timeout,
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => $post_fields
                );
 curl_setopt_array($curl, $opt_arr);

 $curl_return = curl_exec($curl);
 
 echo "<pre>";
 echo htmlspecialchars($curl_return);
 echo "</pre>"; 
 
 curl_close($curl);

//FB::info($curl_return);

?>