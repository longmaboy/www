<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/6/17
 * Time: 18:15
 */

namespace app\load\controller;
namespace app\login\controller;

use think\Db;

class index extends token {

    /**
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function index () {

        if (!$this->verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'param error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {
            if (!$this->verification_body_token($_POST['token'],$_POST['verificationcode'])) {
                $data = config()['requestsuccess'];
                $data['code'] = 201;
                $data['message'] = 'param error';
                $data['data'] = '';
                return json($data);
            }
        } else{

            $data = config()['requestsuccess'];
            $data['code'] = 201;
            $data['message'] = 'param error';
            $data['data'] = '';
            return json($data);

        }

        if (!isset($_POST['image'])) {
            $data = config()['requestsuccess'];
            $data['code'] = 201;
            $data['message'] = 'iamge param error';
            $data['data'] = '';
            return json($data);
        }

        return self::uploadphoto($_POST['image']);

    }

    /**
     * @param $image
     * @return \think\response\Json
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    protected function uploadphoto ($image) {

        $filename = md5($_POST['uid']);
        $imgStr = uploadimg::loadPicture($image,$filename);

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
    public function uploadmorepicture($images) {

        return $this->uploadDigui($images,0, array());

    }

    /**
     * @param $images
     * @param int $index
     * @param $url_arr
     * @return array
     */
    protected function uploadDigui($images, $index = 0, $url_arr){

        if($index < count($images)){

            $imgStr = uploadimg::loadPicture($images[$index]);

            if ($imgStr) {

                $index++;
                array_push($url_arr, $imgStr);
                $this->uploadDigui($images, $index, $url_arr);

            }else{

                return array();

            }

        }else{

            return $url_arr;
        }
    }


}