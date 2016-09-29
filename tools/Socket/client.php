<?php
header("Content-Type: text/html;charset=utf-8");

/**
* 功能：客户端
* @author gaoqing
* 2015年7月9日
*/

/**
 * 客户端连接服务器端的流程为：
 *  （1）创建一个 socket 资源 $socket 
 *  （2）连接指定的服务器端的 socket
 *  （3）读取服务器发送的消息
 *  （4）向服务器端发送消息
 *  （5）
 */

//（1）创建一个 socket 资源 $socket 
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("创建 socket 资源时，失败！");

//（2）连接指定的服务器端的 socket
$address = "127.0.0.1";
$port = "45670";
socket_connect($socket, $address, $port) or die("在连接 " . $address . " ，端口是：" . $port . " 时，发生错误！");

//（3）读取服务器发送的消息
$message = "I'm client ！";
$write_num = socket_write($socket, $message, mb_strlen($message, "gbk"));

//（4）向服务器端发送消息
$recieve_str = "";
$max_length = 1024 * 1024;
while (!!($recieve = socket_read($socket, $max_length))) {
    $recieve_str .= $recieve;
}
echo $recieve_str;
?>