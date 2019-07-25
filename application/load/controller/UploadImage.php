<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/25
 * Time: 18:18
 */

namespace app\load\controller;

//require_once '../vendor/teg1c/thinkphp-qiniu-sdk/qiniu_php_driver/autoload.php';
//
//use Qiniu\Auth;
//use Qiniu\Storage\UploadManager;


class UploadImage
{
//单图上传 七牛云
    /**
     * @param $dataStr    必传
     * @param $filenames  文件名 传则是同名覆盖 不传自定义新名
     * @return null|string
     */
    static public function loadPicture($dataStr, $filenames) {

        //$img为传入字符串
        $img = str_replace('data:image/png;base64,', '', $dataStr);
        $img = str_replace(' ', '+', $img);
        $img_data = base64_decode($img);

//        $config = config()['uploadimage']['UPLOAD_Qiniu_CONFIG'];
//        $upManager = new UploadManager();
//        $auth = new Auth($config['accessKey'],$config['secretKey']);

        if (strlen($filenames) > 0) {
            $filename = $filenames.".png"; //新图片名称
            $policy=array('insertOnly' => 0);
//            $token = $auth->uploadToken($config['bucket'], $filename, 3306, $policy);

        }else{

            $filename = date("Ym").md5(time().mt_rand(10, 99)).".png"; //新图片名称
//            $token = $auth->uploadToken($config['bucket']);

        }

        //保存本地 public/image/目录下
        if ($filenames) {
            $imgPath = './photo/'.$filename;
            $imgPath2 = 'photo/'.$filename;
        }else{
            $imgPath = './image/'.$filename;
            $imgPath2 = 'image/'.$filename;
        }
        if(@file_exists($imgPath)){
            @unlink($imgPath);
        }@clearstatcache();
        $fp=fopen($imgPath,'w');
        fwrite($fp,$img_data);
        fclose($fp);

        return $imgPath2;

//        list($ret, $error) = $upManager->put($token, $filename, $img_data);
//        if ($error === null) {
//            return $config['domain'].$ret['key'];
//        }else{
//            return null;
//        }

    }
}