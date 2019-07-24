<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use think\Db;

/**
 * @return 当前时间的10位时间戳
 */

function microtime_float()
{
    list(, $sec) = explode(" ", microtime());
    return $sec;
}

/**
 * 验证手机号
 * @param $phone
 * @return bool
 */
function verification_phone($phone) {
    if (preg_match("/^1\d{10}$/", $phone)) {
        return true;
    }else{
        return false;
    }
}

/**
 * 获取随机数 数字字母
 * @param $length
 * @return string
 */
function randomkeys($length) {
    $returnStr='';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
    for($i = 0; $i < $length; $i ++) {
        $returnStr .= $pattern {mt_rand ( 0, 61 )};
    }
    return $returnStr;
}

/**
 * 获取随机数 数字
 * @param $length
 * @return string
 */
function randomNumbers($length) {
    $returnStr='';
    $pattern = '1234567890';
    for($i = 0; $i < $length; $i ++) {
        $returnStr .= $pattern {mt_rand ( 0, 9 )};
    }
    return $returnStr;
}



