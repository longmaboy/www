<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/15
 * Time: 21:26
 */

namespace app\person\controller;

use app\login\controller\token;
use think\Db;

class savenickname
{
    public function index () {

        if (!token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['nickname'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'iamge param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->saveNickName();

                    $data = config()['requestsuccess'];
                    $data['code'] = 200;
                    $data['message'] = '11111111';
                    $data['data'] = '';
                    return json($data);

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


    protected function saveNickName() {

        $exphone = Db::table('sf_user')->where('id', $_POST['uid'])->find();

        if (isset($exphone) && !empty($exphone)) {

            $nickname = ['nickname' => $_POST['nickname']];

            Db::name('sf_user')
                ->where('id', $_POST['uid'])
                ->update($nickname);

            $infoData = [
                'nickname' => $_POST['nickname'],
            ];

            $data = config()['requestsuccess'];
            $data['data'] = $infoData;
            $data['code'] = 200;
            $data['message'] = '更新成功';
            return json($data);

        }else{

            $data = config()['requestsuccess'];
            $data['data'] = '';
            $data['code'] = 201;
            $data['message'] = '未查询到用户';
            return json($data);

        }
    }

}