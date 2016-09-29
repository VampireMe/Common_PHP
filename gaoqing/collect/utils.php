<?php

class utils {

    /**
     * 结果数据
     *
     * @param Integer $code    状态码
     * @param mixed $data            数据
     * @param String $message  数据信息Tag
     *
     */
    public static function result($code, $message = null, $data = null) {
        return array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );
    }


    /**
     * 获取不重复随机数
     * @param Integer $min                最小值
     * @param Integer $max                  最大值
     * @param Integer | String $prefix    前缀
     */
    public static function randNum($min = 10000, $max = 999999, $prefix = 0) {
        $tmpNum = $orderNum = array();
        $len = strlen($max);
        $tMin = $oMin = $min;
        $oMax = $max;
        for ($tMin; $tMin <= $max; ++$tMin) {
            $tmpNum[$tMin] = $tMin;
        }
        for ($oMin; $oMin <= $max; ++$oMin) {
            $randNum = mt_rand($oMin, $oMax);
            $rands = sprintf("%0" . $len . "d", $tmpNum[$randNum]);
            $orderNum[$rands] = $prefix;
            --$oMax;
        }
        return $orderNum;
    }

    
    /**
     * 
     * 获取随机数
     *
     * @param int
     * @return string
     */
    public static function randomKeys($length) {
        $str = '';
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str.=$strPol[rand(0, $max)]; //rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }
    
    /**
     * 截取字符串（1个汉字长度计为1；1个字母长度计为0.5）
     */
    public static function cutString($sourcestr, $cutlength, $addpoint = 1) {
        $returnstr = '';
        $i = 0;
        $n = 0;
        $str_length = strlen($sourcestr); //字符串的字节数
        while (($n < $cutlength) and ( $i <= $str_length)) {
            $temp_str = substr($sourcestr, $i, 1);
            $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码
            if ($ascnum >= 224) {    //如果ASCII位高与224，
                $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
                $i = $i + 3;            //实际Byte计为3
                $n++;            //字串长度计1
            } elseif ($ascnum >= 192) { //如果ASCII位高与192，
                $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
                $i = $i + 2;            //实际Byte计为2
                $n++;            //字串长度计1
            } elseif ($ascnum >= 65 && $ascnum <= 90) { //如果是大写字母，
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1;            //实际的Byte数仍计1个
                $n++;            //但考虑整体美观，大写字母计成一个高位字符
            } else {                //其他情况下，包括小写字母和半角标点符号，
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1;            //实际的Byte数计1个
                $n = $n + 0.5;        //小写字母和半角标点等与半个高位字符宽...
            }
        }
        if ($str_length > $i && $addpoint) {
            $returnstr = $returnstr . "..."; //超过长度时在尾处加上省略号
        }
        return $returnstr;
    }

    public static function removehtml($html) {
        return preg_replace("'(<(/)?(\S*?)?[^>]*>)'i", "", $html);
    }

    public static function sendmail($to, $subject, $body) {
        $flag = false;
        try {
            $mail = Yii::app()->mailer;
            $mail->AddReplyTo($mail->Username);
            $mail->ClearAddresses();
            if (!is_array($to)) {
                $to = explode(';', $to);
            }
            foreach ($to as $t) {
                if (!empty($t)) {
                    $mail->AddAddress($t);
                }
            }
            $mail->Subject = $subject;
            $mail->Body = $body;
            $flag = $mail->Send();
        } catch (Exception $e) {
            $flag = false;
            //throw new Exception($e->getMessage(),$e->getCode());
        }
        return $flag;
    }

    public static function percentage($data, $format = '%.2f%%') {
        if (is_numeric($data)) {
            $percent_data = floatval($data);
            return sprintf($format, $percent_data * 100);
        }
        return $data;
    }

    public static function matchwords($subject) {
        $keywords = '@(儿童多动症|白癜风|肝硬化|牛皮癣|白内障|便秘腹泻|国际医疗|中医祛斑|小儿咳嗽|癌症食疗|癫痫|问医生|酸奶减肥|预防肝癌|糖尿病|皮肤癌|血糖|饮食)@';
        $reg_count = preg_match_all($keywords, $subject, $arr_match);

        $result = array();
        if ($reg_count > 0) {
            $result = array_unique($arr_match[0]);
        }
        return $result;
    }
}
