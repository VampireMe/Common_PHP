<?php
header("Content-Type: text/html;charset=utf-8");

/**
* 功能：分页类的功能类
* @author gaoqing
* 2015年7月17日
*/

class Page{
    
    /**
     * 得到组装后的分页 html 字符串
     * 2015年7月20日
     * @author gaoqing
     * @param int $currentNum 当前页
     * @param string $basePageURL 基本的分页路径 
     * @param int $totalPage 总页数
     * @param int $showPageNum 显示分页的具体总数（如：1 ... 9，$showPageNum = 9）
     * @return string 组装好的分页 html 字符串
     */
    public static function getPageHTML($currentNum, $basePageURL, $totalPage, $showPageNum){
        $pageHTML = "";
        
        /*
         * 显示的格式是：首页 | 上一页 | 1 2 3 4 5 6 7 8 9 10 下一页| 尾页 跳转至 15 页 确定
         * （1）通过 $currentNum 计算出 【上一页】【下一页】以及当前显示的分页页数（3 4 5 6 7 8 9）
         */
        
        //(1)首先是首页 
        $indexPageStr = Page::getIndexPage($currentNum, $basePageURL);
        
        //(2)上一页
        $previousPageStr = Page::getPreviousPage($currentNum, $basePageURL);
        
        //(3)下一页
        $nextPageStr = Page::getNextPage($currentNum, $basePageURL, $totalPage);
        
        //(4)尾页
        $endPageStr = Page::getEndPage($basePageURL, $totalPage);
        
        //(5)得到中间 1 2 3 4 5 6 7 8 9 10 的值
        $middleNumStr = Page::getMiddleNum($currentNum, $basePageURL, $totalPage, $showPageNum);
        
        $pageHTML = $indexPageStr . $previousPageStr . $middleNumStr . $nextPageStr . $endPageStr;
        
        return $pageHTML;
    }
    
    /**
     * 得到中间 1 2 3 4 5 6 7 8 9  的值
     * @author gaoqing
     * 2015年7月22日
     * @param int $currentNum 当前页
     * @param string $basePageURL 基本的访问地址
     * @param int $totalPage 总页数
     * @param int $showPageNum 显示分页的具体总数（如：1 ... 9，$showPageNum = 9）
     * @return string 中间 1 2 3 4 5 6 7 8 9  的值 的 html 字符串
     */
    private static function getMiddleNum($currentNum, $basePageURL, $totalPage, $showPageNum){
        //中间 1 2 3 4 5 6 7 8 9  的值 的 html 字符串
        $middleNumStr = "";
        //前 4 个数的 html 字符串
        $leftFourNumStr = ''; 
        //后 4 个数的 html 字符串
        $rightFourNumStr = '';
        $currentNumStr = '';
        
        //$showPageNum 的中间取值
        $showPageNumMiddle = 0;
        //以中间值 $showPageNumMiddle 为基准，左边显示的分页个数
        $showPageNumLeft = 0;
        //以中间值 $showPageNumMiddle 为基准，右边显示的分页个数
        $showPageNumRight = 0;
        
        //判断当前 $showPageNum 是奇数还是偶数
        if ($showPageNum % 2 == 0) {
            $showPageNumMiddle = $showPageNum / 2;
            
            /*
             * 如果是偶数的话，根据自己的情况，指定显示左右分页的个数
             *      在这里，自己定义规则为：
             *      左：(($showPageNum - 1) - 1) / 2
             *      右：($showPageNum - 1) - 左
             */
            $showPageNumLeft = (($showPageNum - 1) - 1) / 2;
            $showPageNumRight = ($showPageNum - 1) - $showPageNumLeft;
            
        }else {
            $showPageNumMiddle = ($showPageNum + 1) / 2;
            //奇数时，则左右对称显示分页的个数相等
            $showPageNumLeft = ($showPageNum - 1) / 2;
            $showPageNumRight = ($showPageNum - 1) / 2;
        }
        
        //只有在＞5 的时候
        if ($currentNum > $showPageNumMiddle) {
            //前 4 个值
            for ($i = $showPageNumLeft; $i >= 1; $i--){
                $page = $currentNum - $i;
                if ($page > 0) {
                    
                    $leftFourNumStr .= '<a href = "'. $basePageURL . '?page=' . $page .'" >&nbsp;&nbsp;'. $page .'&nbsp;&nbsp;</a>&nbsp;&nbsp;';
                }else {
                    break;
                }
            }
            
            //当前页值
            $currentNumStr = '<a href = "'.$basePageURL. '?page='. $currentNum . '" style = "text-decoration: underline;" >&nbsp;&nbsp;'. $currentNum .'&nbsp;&nbsp;</a>&nbsp;&nbsp;';
            
            //后 4 个值
            for ($j = 1; $j <= $showPageNumRight; $j++){
                $page = $currentNum + $j;
                if ($page <= $totalPage) {
                    $rightFourNumStr .= '<a href = "'. $basePageURL . '?page=' . $page .'" >&nbsp;&nbsp;'. $page .'&nbsp;&nbsp;</a>&nbsp;&nbsp;';         
                }else {
                    break;
                }
            }
            $middleNumStr = $leftFourNumStr . $currentNumStr . $rightFourNumStr;
        }else {
            for ($k = 1; $k <= $totalPage; $k++){
                if ($k <= $showPageNum) {
                    $class = $currentNum == $k ? 'style = "text-decoration: underline;"' : '';
                    $middleNumStr .= '<a href = "'. $basePageURL . '?page=' . $k . '" '. $class .' >&nbsp;&nbsp;' . $k .'&nbsp;&nbsp;</a>&nbsp;&nbsp;';
                }else {
                    break;
                }
            }
        }
        return $middleNumStr;
    }
    
    /**
     * 得到尾页
     * @author gaoqing
     * 2015年7月22日
     * @param string $basePageURL 基本的访问地址
     * @param int $endPage 最后一页
     * @return string 尾页 的 html 字符串
     */
    private static function getEndPage($basePageURL, $endPage){
        $lastPage = "";
        $lastPage = '<a href = "'. $basePageURL . '?page=' . $endPage .'" >尾页</a>&nbsp;&nbsp;';
        
        return $lastPage;
    }
    
    /**
     * 得到下一页
     * @author gaoqing
     * 2015年7月21日
     * @param int $currentNum 当前页
     * @param string $basePageURL 基本的访问地址
     * @param int $endPage 最后一页
     * @return string 下一页 的 html 字符串
     */
    private static function getNextPage($currentNum, $basePageURL, $endPage){
        $nextPage = "";
        
        $nextPage = '<a href = "'. $basePageURL . '?page='. ($currentNum + 1) .'' . '" class = "enableIndex" >下一页</a>&nbsp;&nbsp;';
        //如果是最后一页的话，当前 <a> 元素不可用
        if ($currentNum == $endPage) {
            $nextPage = '<a href = "'. $basePageURL . '?page='. $endPage .'' . '" class = "disableIndex" >下一页</a>&nbsp;&nbsp;';
        }
        return $nextPage;
    }
    
    /**
     * 得到上一页
     * @author gaoqing
     * 2015年7月21日
     * @param int $currentNum 当前页
     * @param string $basePageURL 基本的访问地址
     * @return string 上一页 的 html 字符串
     */
    private static function getPreviousPage($currentNum, $basePageURL){
        $previousPage = "";
        
        $previousPage = '<a href = "'. $basePageURL . '?page='. ($currentNum - 1) .'' . '" class = "enableIndex" >上一页</a>&nbsp;&nbsp;';
        //如果是第一页的话，当前 <a> 元素不可用
        if ($currentNum == 1) {
            $previousPage = '<a href = "'. $basePageURL . '?page=1' . '" class = "disableIndex" >上一页</a>&nbsp;&nbsp;';
        }
        return $previousPage;
    }
    
    /**
     * 得到首页
     * 2015年7月20日
     * @author gaoqing
     * @param int $currentNum 当前页
     * @param string $basePageURL 基本的访问地址
     * @return string 首页的 html 字符串
     */
    private static function getIndexPage($currentNum, $basePageURL){
        $class = $currentNum > 1 ? 'class = "enableIndex" ' : 'class = "disableIndex" ';
        
        $indexPageStr = '<a href = "'. $basePageURL . '?page=1' . '" '. $class .' >首页</a>&nbsp;&nbsp;';
        return $indexPageStr;
    }
}
?>