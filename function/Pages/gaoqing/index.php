<?php
header("Content-Type: text/html;charset=utf-8");

error_reporting(E_ALL);

/**
* 功能：分页测试的首页模板
* @author gaoqing
* 2015年7月17日
*/

include 'config.inc.php';
include 'db.inc.php';
include 'Page.class.php';
include_once '../FirePHPCore/fb.php';

/*
 * 处理的流程如下：
 *      1、连接相应的数据库
 *      2、设定指定的分页参数后，得到对应分页的数据 $current_page_val_arr
 *      3、将得到对应的数据  $current_page_val_arr 循环显示在页面中
 *      4、得到组装的分页 HTML 并显示在页面中 
 */
 
 //每页显示的数据条数
 $page_pre_num = 10;
 //得到当前页数
 $page_current_num = intval(isset($_GET['page']) ? $_GET['page'] : 1);
 
 //2、设定指定的分页参数后，得到对应分页的数据 $current_page_val_arr
 $start = ($page_current_num - 1) * $page_pre_num;
 $rows = $page_pre_num;
 
 //得到当前条件下的所有记录数
 $toal_nums = getTotalNums($DSN, $user, $pass);
 //总页数
 $total_page = ceil($toal_nums/$page_pre_num);
 
 $current_page_val_arr = getPagesList($DSN, $user, $pass, $start, $rows);
 
 //4、得到组装的分页 HTML 并显示在页面中 
 $base_page_url = "http://localhost/Common_PHP/gaoqing/index.php";
 $page_html = Page::getPageHTML($page_current_num, $base_page_url, $total_page, 8);

?>

<!-- HTML 主体 Start -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
        <title>演示：PHP简单漂亮的分页类</title>
        <meta name="keywords" content="php分页类" />
        <meta name="description" content="本文介绍一款原生的PHP分页类，分页样式有点类似bootstrap。" />
        <link rel="stylesheet" type="text/css" href="http://www.sucaihuo.com/jquery/css/common.css" />
        <link rel="stylesheet" type="text/css" href="./index.css" />
        
    </head>
    <body>
        <div class="head">
            <div class="head_inner clearfix">
                <ul id="nav">
                    <li><a href="http://www.sucaihuo.com">首 页</a></li>
                    <li><a href="http://www.sucaihuo.com/templates">网站模板</a></li>
                    <li><a href="http://www.sucaihuo.com/js">网页特效</a></li>
                    <li><a href="http://www.sucaihuo.com/php">PHP</a></li>
                    <li><a href="http://www.sucaihuo.com/site">精选网址</a></li>
                </ul>
                <a class="logo" href="http://www.sucaihuo.com"><img src="http://www.sucaihuo.com/Public/images/logo.jpg" alt="素材火logo" /></a>
            </div>
        </div>
        <div class="container">
            <div class="demo">
                <h2 class="title"><a href="#">自定义的分页练习页</a></h2>

                <div class="showData">

                    <ul class="dates">
                        
                        <!-- 分页显示内容部分 Start -->
                        <?php 
                            if (!empty($current_page_val_arr)) {
                                foreach ($current_page_val_arr as $current_page_val_key => $current_page_val){
                        ?>
                        <li>
                            <span> <?php echo $current_page_val['id']; ?> </span>
                            <a target="_blank" href="http://www.sucaihuo.com/js"> <?php echo $current_page_val['title']; ?> </a>
                        </li>
                        <?php             
                                }
                            }
                        ?>
                        <!-- 分页显示内容部分 End -->
                        
                    </ul>
                    <!--显示数据区-->
                </div>
                <div class="showPage">
                
                    <!-- 分页 HTML 部分 Start -->
                    <?php echo $page_html;?>
                    <!-- 分页 HTML 部分 End -->
                    
                </div>
            </div>
        </div>
        <div class="foot">
            Powered by sucaihuo.com  本站皆为作者原创，转载请注明原文链接：<a href="http://www.sucaihuo.com" target="_blank">www.sucaihuo.com</a>
        </div>
        <script type="text/javascript" src="http://www.sucaihuo.com/Public/js/other/jquery.js"></script> 
    </body>
</html>
<!-- HTML 主体 End -->