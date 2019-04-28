<?php

namespace Tool;

class Tool{
	
	/**
     * 输出错误JSON信息。
     * @param type $message     
     */
    protected function failJSON($message) {
        $message = lang($message);
        $jsonData = array('success' => false, 'message' => $message);
        $json = json_encode($jsonData, true);
        exit($json);
    }

    /**
     * 输出成功JSON信息
     * @param type $data
     */
    protected function successJSON($data = NULL, $msg = "success") {
        if (is_array($data) || is_object($data)) {
            $data = $this->_setDataLang($data);
        }
        $jsonData = array('success' => true, 'data' => $data, 'message' => $msg);
        $json = json_encode($jsonData, 1);
        exit($json);
    }

    /**
     * 文件地址返回
     */
    protected function sp_dir_create($path, $mode = 0777) {
        if (is_dir($path))
            return true;
        $ftp_enable = 0;
        $path = $this->sp_dir_path($path);
        $temp = explode('/', $path);
        $cur_dir = '';
        $max = count($temp) - 1;
        for ($i = 0; $i < $max; $i++) {
            $cur_dir .= $temp[$i] . '/';
            if (@is_dir($cur_dir))
                continue;
            @mkdir($cur_dir, 0777, true);
            @chmod($cur_dir, 0777);
        }
        return is_dir($path);
    }

    protected function sp_dir_path($path) {
        $path = str_replace('\\', '/', $path);
        if (substr($path, -1) != '/')
            $path = $path . '/';
        return $path;
    }


	/**
	 * 二维数组按字段排序
	 */
	public function array_sort($res,$field){
        foreach ($res as $key => $row) {
            $time[$key] = $row[$field];
        }
        array_multisort($time, SORT_DESC, $res);
        return $res;
	}


	/**
     * 二维数组根据首字母分组排序
     * @param  array  $data      二维数组
     * @param  string $targetKey 首字母的键名
     * @return array             根据首字母关联的二维数组
     */
    public function groupByInitials(array $data, $targetKey = 'name'){
        $data = array_map(function ($item) use ($targetKey) {
            return array_merge($item, [
                'initials' => $this->getInitials($item[$targetKey]),
            ]);
        }, $data);
        $data = $this->sortInitials($data);
        return $data;
    }

    /**
     * 按字母排序
     * @param  array  $data
     * @return array
     */
    public function sortInitials(array $data){
        $sortData = [];
        foreach ($data as $key => $value) {
            $sortData[$value['initials']][] = $value;
        }
        ksort($sortData);
        return $sortData;
    }
    
    /**
     * 获取首字母
     * @param  string $str 汉字字符串
     * @return string 首字母
     */
    public function getInitials($str){
        if (empty($str)) {return '';}
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z')) {
            return strtoupper($str{0});
        }
        $s1  = iconv('UTF-8', 'GBK//IGNORE', $str);
        $s2  = iconv('GBK', 'UTF-8', $s1);
        $s   = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284) {
            return 'A';
        }
        if ($asc >= -20283 && $asc <= -19776) {
            return 'B';
        }
        if ($asc >= -19775 && $asc <= -19219) {
            return 'C';
        }
        if ($asc >= -19218 && $asc <= -18711) {
            return 'D';
        }
        if ($asc >= -18710 && $asc <= -18527) {
            return 'E';
        }
        if ($asc >= -18526 && $asc <= -18240) {
            return 'F';
        }
        if ($asc >= -18239 && $asc <= -17923) {
            return 'G';
        }
        if ($asc >= -17922 && $asc <= -17418) {
            return 'H';
        }
        if ($asc >= -17417 && $asc <= -16475) {
            return 'J';
        }
        if ($asc >= -16474 && $asc <= -16213) {
            return 'K';
        }
        if ($asc >= -16212 && $asc <= -15641) {
            return 'L';
        }
        if ($asc >= -15640 && $asc <= -15166) {
            return 'M';
        }
        if ($asc >= -15165 && $asc <= -14923) {
            return 'N';
        }
        if ($asc >= -14922 && $asc <= -14915) {
            return 'O';
        }
        if ($asc >= -14914 && $asc <= -14631) {
            return 'P';
        }
        if ($asc >= -14630 && $asc <= -14150) {
            return 'Q';
        }
        if ($asc >= -14149 && $asc <= -14091) {
            return 'R';
        }
        if ($asc >= -14090 && $asc <= -13319) {
            return 'S';
        }
        if ($asc >= -13318 && $asc <= -12839) {
            return 'T';
        }
        if ($asc >= -12838 && $asc <= -12557) {
            return 'W';
        }
        if ($asc >= -12556 && $asc <= -11848) {
            return 'X';
        }
        if ($asc >= -11847 && $asc <= -11056) {
            return 'Y';
        }
        if ($asc >= -11055 && $asc <= -10247) {
            return 'Z';
        }
        return null;
    }


    // 获取IP地址
    public function getIp(){
        $ip = false;
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            return is_ip($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:$ip;
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            return is_ip($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$ip;
        }else{
            return is_ip($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:$ip;
        }
    }


    /**
     * 检测是否使用手机访问
     * @access public
     * @return bool
     */
    public function is_mobile(){
        if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap")) {
            return true;
        } elseif (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER['HTTP_ACCEPT']), "VND.WAP.WML")) {
            return true;
        } elseif (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
            return true;
        } elseif (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 是否微信访问
     * @return bool
     */
    public function is_weixin(){
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        } else {
            return false;
        }
    }

}



?>