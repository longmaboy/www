<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/29
 * Time: 13:31
 */

namespace app\malongbo\controller;

use think\Db;

class Allthumbsstatus
{
    public function index() {
        return self::thumbsStatus();
    }

    /**
     * 点赞数已读
     * @return \think\response\Json
     */
    public function thumbsStatus() {

        if (!Token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (Token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['uid'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->setThumbsStatus($_POST['uid']);
                }

            }else{

                $data = config()['requestsuccess'];
                $data['code'] = 201;
                $data['message'] = 'param error';
                $data['data'] = '';
                return json($data);

            }

        } else{

            $data = config()['requestsuccess'];
            $data['code'] = 202;
            $data['message'] = 'param error';
            $data['data'] = '';
            return json($data);

        }

    }

    protected function setThumbsStatus($uid) {

        $update_data = ['uidstatus' => '1'];
        Db::table('sf_thumbs')
            ->where('createuid',$uid)
            ->where('status','1')
            ->where('uidstatus','0')
            ->update($update_data);

        $data = config()['requestsuccess'];
        $data['code'] = 200;
        $data['message'] = '设置点赞已阅读成功';
        $data['data'] = '';
        return json($data);

    }
}