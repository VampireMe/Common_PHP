<?php
header("Content-Type: text/html;charset=utf-8");

/**
* 功能：server 端
* @author gaoqing
* 2015年7月9日
*/

/**
 * 产生 Socket 的流程是：
 *  （1）使用 socket_create() 函数创建一个 socket 资源 $socket
 *  （2）将指定的 IP、Port 绑定到 $socket 上
 *  （3）监听客户端的请求
 *  （4）接收客户端的请求
 *  （5）与客户端进行交互
 */
 
 //（1）使用 socket_create() 函数创建一个 socket 资源 $socket
 $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("创建 socket 资源失败！");
 
 //（2）将指定的 IP、Port 绑定到 $socket 上
 $address = "127.0.0.1";
 $port = "45670";
 socket_bind($socket, $address, $port) or die("绑定指定的地址：" . $address . "，端口：" . $port . " 失败！");
 
 //（3）监听客户端的请求
 socket_listen($socket, 10) or die("监听客户端请求时，发生错误！");
 
 
 //（5）与客户端进行交互
 while (true) {
     
     //（4）接收客户端的请求
     $deal_client_socket = socket_accept($socket) or die("接收客户端请求时发生错误！");
     
     //连接成功后，向客户端反馈信息
     $message = "connect success！";
     $write_num = socket_write($deal_client_socket, $message, mb_strlen($message, "gbk"));
     if (empty($write_num)) {
         echo "write to client failure !";
     }
     
     //读取客户端的信息
     $max_read_length = 1024 * 1024;
     $read_message_str = socket_read($deal_client_socket, $max_read_length);
     echo "recieve client message are ：" . $read_message_str;
     
     //关闭连接
     socket_close($deal_client_socket);
 }

 //关闭 $socket 资源
socket_close($socket);
?>