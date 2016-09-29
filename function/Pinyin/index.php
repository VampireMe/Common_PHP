<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>演示：PHP汉语拼音转换</title>
<meta name="keywords" content="PHP汉语拼音转换" />
<meta name="description"
	content="本文整理了PHP汉字拼音转换和公历农历转换两个功能类文件，非常实用。比如我们查找通讯录可以通过联系人姓名的拼音首字母来查询，可以通过首字母来导航大数据量，可以通过转换拼音来做网站优化等。公农历转化一般用在日历日程安排的项目中，方便农历的节日提醒等等。" />
<link rel="stylesheet" type="text/css"
	href="http://www.sucaihuo.com/jquery/css/common.css" />
<style type="text/css">
.demo {
	width: 560px;
	margin: 20px auto;
	font-size: 14px;
	position: relative
}

.demo h4 {
	height: 36px;
	line-height: 36px;
	font-size: 14px
}

.demo p {
	line-height: 28px;
	padding-left: 20px
}

.input {
	width: 200px;
	height: 20px;
	line-height: 20px;
	padding: 2px
}

.input {
	width: 252px;
	height: 20px;
	padding: 1px;
	line-height: 20px;
	border: 1px solid #999
}

.btn {
	position: relative;
	overflow: hidden;
	display: inline-block;
	*display: inline;
	padding: 4px 20px 4px;
	font-size: 14px;
	line-height: 18px;
	*line-height: 20px;
	color: #fff;
	text-align: center;
	vertical-align: middle;
	cursor: pointer;
	background-color: #5bb75b;
	border: 1px solid #cccccc;
	border-color: #e6e6e6 #e6e6e6 #bfbfbf;
	border-bottom-color: #b3b3b3;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}
</style>
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
			<a class="logo" href="http://www.sucaihuo.com"><img
				src="http://www.sucaihuo.com/Public/images/logo.jpg" alt="素材火logo" /></a>
		</div>
	</div>
	<div class="container">
		<div class="demo">
			<h2 class="title">
				<a href="http://www.sucaihuo.com/js/362.html">教程：PHP汉语拼音转换</a>
			</h2>
			<h4>将中文汉字转换为拼音</h4>
			<p style="margin: 10px 0">
				请输入中文内容：<input type="text" class="input" id="str"> <input
					type="button" class="btn" id="pinyin_btn" value="转换">
			
			</p>
			<div id="result">
				<p>
					中文：<span id="re_zh"></span>
				</p>
				<p style="margin: 10px 0 0; color: red">
					拼音：<span id="re_py"></span>
				</p>
			</div>

		</div>
	</div>
	<div class="foot">
		Powered by sucaihuo.com 本站皆为作者原创，转载请注明原文链接：<a
			href="http://www.sucaihuo.com" target="_blank">www.sucaihuo.com</a>
	</div>
	<script type="text/javascript"
		src="http://libs.useso.com/js/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript">
$(function(){
	var str_zh = "好好学习天天向上";
	strtopy(str_zh);
	$("#pinyin_btn").click(function(){
		var str = $("#str").val();
		strtopy(str);
	});
});
function strtopy(str_zh){
	$.post("pinyin.php",{str:str_zh},function(data){
		$("#re_zh").html(str_zh);
		$("#re_py").html(data);
	});
}
</script>
</body>
</html>