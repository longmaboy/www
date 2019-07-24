<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/18
 * Time: 18:12
 */

namespace app\school\model;

use think\model;

class SfContent extends model
{
    function AdminMessage(){
        //aid为外键id是adminmessage表关联admin表的外键
        //id是 admin表的主键
        return $this->hasOne('SfUser','uid','id')->field('nickname');
    }
}