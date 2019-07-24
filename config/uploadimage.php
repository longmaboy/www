<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/6/17
 * Time: 15:54
 */


//配置文件
return [
    // 文件上传默认驱动
    'UPLOAD_DRIVER' => 'Qiniu', //设置七牛上传驱动
    //'UPLOAD_DRIVER' => 'Local',
    // 七牛上传驱动配置说明
    'UPLOAD_Qiniu_CONFIG' => [
        'secretKey' => 'pUQCa1bf-X9ZugKUmjW0covJ551v4RCznad6MlH-', //七牛服务器
        'accessKey' => '8QbNf-vPUdaF1ioHRorlGzqWl0fWDMPuKqTlyTPt', //七牛用户
        'domain'    => 'http://pt8fmjglz.bkt.clouddn.com/', //七牛域名
        'bucket'    => 'school_image', //空间名称
        'timeout'   => 300, //超时时间
    ],
];
