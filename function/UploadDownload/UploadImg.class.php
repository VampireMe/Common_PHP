
<?php
header("Content-Type: text/html;charset=utf-8");
include './UploadFile.class.php';

/**
 * 
 */

/*************************************************
 * 图片处理类
 *
 * 可以对图片进行生成缩略图，打水印等操作
 * 本类默认编码为UTF8 如果要在GBK下使用请将img_mark方法中打中文字符串水印iconv注释去掉
 *
 * 由于UTF8汉字和英文字母大小(像素)不好确定，在中英文混合出现太多时可能会出现字符串偏左
 * 或偏右,请根据项目环境对get_mark_xy方法中的$strc_w = strlen($this->mark_str)*7+5进
 * 行调整
 * 需要GD库支持，为更好使用本类推荐使用GD库2.0+
 *
 * @author kickflip@php100 QQ263340607
 *************************************************/
class uploadImg extends uploadFile {
    
    public $mark_str = 'kickflip@php100'; //水印字符串
    public $str_r = 0; //字符串颜色R
    public $str_g = 0; //字符串颜色G
    public $str_b = 0; //字符串颜色B
    public $mark_ttf = './upload/SIMSUN.TTC'; //水印文字字体文件(包含路径)
    public $mark_logo = './upload/logo.png'; //水印图片
    public $resize_h;//生成缩略图高
    public $resize_w;//生成缩略图宽
    public $source_img;//源图片文件
    public $dst_path = './upload/';//缩略图文件存放目录，不填则为源图片存放目录
    
    /**
     * 生成缩略图 生成后的图
     * @access public
     * @param integer $w 缩小后图片的宽（px）
     * @param integer $h 缩小后图片的高（px）
     * @param string $source_img 源图片(路径+文件名)
     */
    public function img_resized($w,$h,$source_img = NULL){
        $source_img = $source_img == NULL ? $this->uploaded : $source_img;//取得源文件的地址，如果为空则默认为上次上传的图片
        if(!is_file($source_img)) { //检查源图片是否存在
            $this->errmsg = '文件'.$source_img.'不存在';
            return FALSE;
        }
        $this->source_img = $source_img;
        $img_info = getimagesize($source_img);
        $source = $this->img_create($source_img); //创建源图片
        $this->resize_w = $w;
        $this->resize_h = $h;
        $thumb = imagecreatetruecolor($w,$h);
        imagecopyresized($thumb,$source,0,0,0,0,$w,$h,$img_info[0],$img_info[1]);//生成缩略图片
        $dst_path = $this->dst_path == '' ? $this->save_path : $this->dst_path; //取得目标文件夹路径
        $dst_path = (preg_match('/\/$/',$dst_path)) ? $dst_path : $dst_path . '/';//将目标文件夹后加上/
        if(!is_dir($dst_path)) $this->set_dir($dst_path); //如果不存在目标文件夹则创建
        $dst_name = $this->set_newname($source_img);
        $this->img_output($thumb,$dst_name);//输出图片
        imagedestroy($source);
        imagedestroy($thumb);
    }
    
    /**
     *打水印
     *@access public
     *@param string $source_img 源图片路径+文件名
     *@param integer $mark_type 水印类型(1为英文字符串，2为中文字符串，3为图片logo,默认为英文字符串)
     *@param integer $mark_postion 水印位置(1为左下角,2为右下角,3为左上角,4为右上角,默认为右下角);
     *@return 打上水印的图片
     */
    public function img_mark($source_img = NULL,$mark_type = 1,$mark_postion = 2) {
        $source_img = $source_img == NULL ? $this->uploaded : $source_img;//取得源文件的地址，如果为空则默认为上次上传的图片
        if(!is_file($source_img)) { //检查源图片是否存在
            $this->errmsg = '文件'.$source_img.'不存在';
            return FALSE;
        }
        $this->source_img = $source_img;
        $img_info = getimagesize($source_img);
        $source = $this->img_create($source_img); //创建源图片
        $mark_xy = $this->get_mark_xy($mark_postion);//取得水印位置
        $mark_color = imagecolorallocate($source,$this->str_r,$this->str_g,$this->str_b);
        switch($mark_type) {
            case 1 : //加英文字符串水印
                $str = $this->mark_str;
                imagestring($source,5,$mark_xy[0],$mark_xy[1],$str,$mark_color);
                $this->img_output($source,$source_img);
                break;
            case 2 : //加中文字符串水印
                if(!is_file($this->mark_ttf)) { //检查字体文件是否存在
                    $this->errmsg = '打水印失败：字体文件'.$this->mark_ttf.'不存在!';
                    return FALSE;
                }
                $str = $this->mark_str;
                //$str = iconv('gbk','utf-8',$str);//转换字符编码 如果使用GBK编码请去掉此行注释
                imagettftext($source,12,0,$mark_xy[2],$mark_xy[3],$mark_color,$this->mark_ttf,$str);
                $this->img_output($source,$source_img);
                break;
            case 3 : //加图片水印
                if(is_file($this->mark_logo)){ //如果存在水印logo的图片则取得logo图片的基本信息,不存在则退出
                    $logo_info = getimagesize($this->mark_logo);
                }else{
                    $this->errmsg = '打水印失败：logo文件'.$this->mark_logo.'不存在！';
                    return FALSE;
                }
                $logo_info = getimagesize($this->mark_logo);
                if($logo_info[0]>$img_info[0] || $logo_info[1]>$img_info[1]) { //如果源图片小于logo大小则退出
                    $this->errmsg = '打水印失败：源图片'.$this->source_img.'比'.$this->mark_logo.'小！';
                    return FALSE;
                }
                $logo = $this->img_create($this->mark_logo);
                imagecopy ( $source, $logo, $mark_xy[4], $mark_xy[5], 0, 0, $logo_info[0], $logo_info[1]);
                $this->img_output($source,$source_img);
                break;
            default: //其它则为文字图片
                $str = $this->mark_str;
                imagestring($source,5,$mark_xy[0],$mark_xy[1],$str,$mark_color);
                $this->img_output($source,$source_img);
                break;
        }
        imagedestroy($source);
    }
    
    /**
     * 取得水印位置
     * @access private
     * @param integer $mark_postion 水印的位置(1为左下角,2为右下角,3为左上角,4为右上角,其它为右下角)
     * @return array $mark_xy 水印位置的坐标(索引0为英文字符串水印坐标X,索引1为英文字符串水印坐标Y，
     * 索引2为中文字符串水印坐标X，索引3为中文字符串水印坐标Y,索引4为水印图片坐标X，索引5为水印图片坐标Y)
     */
    private function get_mark_xy($mark_postion){
        $img_info = getimagesize($this->source_img);
        $stre_w = strlen($this->mark_str)*9+5 ; //水印英文字符串的长度(px)(5号字的英文字符大小约为9px 为了美观再加5px)
        //(12号字的中文字符大小为12px,在utf8里一个汉字长度为3个字节一个字节4px 而一个英文字符长度一个字节大小大约为9px
        // 为了在中英文混合的情况下显示完全 设它的长度为字节数*7px)
        $strc_w = strlen($this->mark_str)*7+5 ; //水印中文字符串的长度(px)
        if(is_file($this->mark_logo)){ //如果存在水印logo的图片则取得logo图片的基本信息
            $logo_info = getimagesize($this->mark_logo);
        }
        //由于imagestring函数和imagettftext函数中对于字符串开始位置不同所以英文和中文字符串的Y位置也有所不同
        //imagestring函数是从文字的左上角为参照 imagettftext函数是从文字左下角为参照
        switch($mark_postion){
            case 1: //位置左下角
                $mark_xy[0] = 5; //水印英文字符串坐标X
                $mark_xy[1] = $img_info[1]-20;//水印英文字符串坐标Y
                $mark_xy[2] = 5; //水印中文字符串坐标X
                $mark_xy[3] = $img_info[1]-5;//水印中文字符串坐标Y
                $mark_xy[4] = 5;//水印图片坐标X
                $mark_xy[5] = $img_info[1]-$logo_info[1]-5;//水印图片坐标Y
                break;
            case 2: //位置右下角
                $mark_xy[0] = $img_info[0]-$stre_w; //水印英文字符串坐标X
                $mark_xy[1] = $img_info[1]-20;//水印英文字符串坐标Y
                $mark_xy[2] = $img_info[0]-$strc_w; //水印中文字符串坐标X
                $mark_xy[3] = $img_info[1]-5;//水印中文字符串坐标Y
                $mark_xy[4] = $img_info[0]-$logo_info[0]-5;//水印图片坐标X
                $mark_xy[5] = $img_info[1]-$logo_info[1]-5;//水印图片坐标Y
                break;
            case 3: //位置左上角
                $mark_xy[0] = 5; //水印英文字符串坐标X
                $mark_xy[1] = 5;//水印英文字符串坐标Y
                $mark_xy[2] = 5; //水印中文字符串坐标X
                $mark_xy[3] = 15;//水印中文字符串坐标Y
                $mark_xy[4] = 5;//水印图片坐标X
                $mark_xy[5] = 5;//水印图片坐标Y
                break;
            case 4: //位置右上角
                $mark_xy[0] = $img_info[0]-$stre_w; //水印英文字符串坐标X
                $mark_xy[1] = 5;//水印英文字符串坐标Y
                $mark_xy[2] = $img_info[0]-$strc_w; //水印中文字符串坐标X
                $mark_xy[3] = 15;//水印中文字符串坐标Y
                $mark_xy[4] = $img_info[0]-$logo_info[0]-5;//水印图片坐标X
                $mark_xy[5] = 5;//水印图片坐标Y
                break;
            default : //其它默认为右下角
                $mark_xy[0] = $img_info[0]-$stre_w; //水印英文字符串坐标X
                $mark_xy[1] = $img_info[1]-5;//水印英文字符串坐标Y
                $mark_xy[2] = $img_info[0]-$strc_w; //水印中文字符串坐标X
                $mark_xy[3] = $img_info[1]-15;//水印中文字符串坐标Y
                $mark_xy[4] = $img_info[0]-$logo_info[0]-5;//水印图片坐标X
                $mark_xy[5] = $img_info[1]-$logo_info[1]-5;//水印图片坐标Y
                break;
        }
        return $mark_xy;
    }
    
    /**
     * 创建源图片
     * @access private
     * @param string $source_img 源图片(路径+文件名)
     * @return img 从目标文件新建的图像
     */
    private function img_create($source_img) {
        $info = getimagesize($source_img);
        switch ($info[2]){
            case 1:
                if(!function_exists('imagecreatefromgif')){
                    $source = @imagecreatefromjpeg($source_img);
                }else{
                    $source = @imagecreatefromgif($source_img);
                }
                break;
            case 2:
                $source = @imagecreatefromjpeg($source_img);
                break;
            case 3:
                $source = @imagecreatefrompng($source_img);
                break;
            case 6:
                $source = @imagecreatefromwbmp($source_img);
                break;
            default:
                $source = FALSE;
                break;
        }
        return $source;
    }
    
    /**
     * 重命名图片
     * @access private
     * @param string $source_img 源图片路径+文件名
     * @return string $dst_name 重命名后的图片名(路径+文件名)
     */
    private function set_newname($sourse_img) {
        $info = pathinfo($sourse_img);
        $new_name = $this->resize_w.'_'.$this->resize_h.'_'.$info['basename'];//将文件名修改为：宽_高_文件名
        if($this->dst_path == ''){ //如果存放缩略图路径为空则默认为源文件同文件夹
            $dst_name = str_replace($info['basename'],$new_name,$sourse_img);
        }else{
            $dst_name = $this->dst_path.$new_name;
        }
        return $dst_name;
    }
    
    /**
     * 输出图片
     * @access private
     * @param $im 处理后的图片
     * @param $dst_name 输出后的的图片名(路径+文件名)
     * @return 输出图片
     */
    public function img_output($im,$dst_name) {
        $info = getimagesize($this->source_img);
        switch ($info[2]){
            case 1:
                if(!function_exists('imagegif')){
                    imagejpeg($im,$dst_name);
                }else{
                    imagegif($im, $dst_name);
                }
                break;
            case 2:
                imagejpeg($im,$dst_name);
                break;
            case 3:
                imagepng($im,$dst_name);
                break;
            case 6:
                imagewbmp($im,$dst_name);
                break;
        }
    }
}
?>