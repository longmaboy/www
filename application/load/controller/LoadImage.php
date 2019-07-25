<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/25
 * Time: 18:01
 */

namespace app\load\controller;

use think\Db;

class LoadImage
{
    static public function myloadimage($imgStr) {

        return self::uploadphoto($imgStr);

    }

    /**
     * @param $image
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    static protected function uploadphoto ($image) {

        $filename = md5($_POST['uid']).'u'.$_POST['uid'];
        $imgStr = UploadImage::loadPicture($image,$filename);


        if ($imgStr != null) {

            $data = ["photo" => $imgStr];
            $arr = array($imgStr);

            Db::table('sf_user')->where('id', $_POST['uid'])->update($data);

            $rdata = config()['requestsuccess'];
            $rdata['code'] = 200;
            $rdata['message'] = '上传头像成功';
            $rdata['data'] = $arr;
            return json($rdata);

        }else{

            $rdata = config()['requestsuccess'];
            $rdata['code'] = 202;
            $rdata['message'] = '上传失败';
            $rdata['data'] = '';
            return json($rdata);
        }
    }

    /**
     * @param $images
     * @return array
     */
    static public function uploadmorepicture($images) {

        return self::uploadDigui($images,0);

    }

    /**
     * @param $images
     * @param int $index
     * @param $url_arr
     * @return array
     */
    static public function uploadDigui($images, $index = 0){


        $imageString = '';

        foreach ($images as $value) {

            $imgStr = UploadImage::loadPicture($value,'');

            if ($index == 0) {
                $imageString = $imgStr;
            }else{
                $imageString = $imageString.';'.$imgStr;
            }

            $index++;

        }

        return $imageString;

    }
}