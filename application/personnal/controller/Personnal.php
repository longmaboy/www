<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/27
 * Time: 13:52
 */

namespace app\personnal\controller;

use app\mytoken\controller\MyToken;
use think\Db;

class Personnal
{

    public function index() {
        echo 'fasdsgdh';
    }

    public function login() {

        if (verification_phone($_POST['telephone'])) {

            $exphone = Db::table('sf_user')->where('telephone',$_POST['telephone'])->find();

            if (isset($exphone) && !empty($exphone)) {

                if (md5(md5($_POST["password"]).'a') == $exphone['password']) {

                    //更新登录时间
                    $up_time = ['update_time' => $this->microtime_float()];
                    Db::name('sf_user')
                        ->where('telephone', $_POST['telephone'])
                        ->update($up_time);

                    $verificationcode = $this->randomkeys(6);
                    $token = $exphone['token'];
                    $uid = $exphone['id'];

                    if (!MyToken::create_header_token()) {

                        $data = config()['requestsuccess'];
                        $data['data'] = '';
                        $data['code'] = 301;
                        $data['message'] = 'param error';
                        return json($data);
                    }

                    $rtTken = MyToken::create_body_token();

                    $infoData = [
                        'token' => $rtTken,
                        'name'  => $exphone['name'],
                        'age'   => $exphone['age'],
                        'school'  => $exphone['school'],
                        'nickname' => $exphone['nickname'],
                        'telephone' => $exphone['telephone'],
                        'photo'     => $exphone['photo'],
                        'update_time' => $exphone['update_time'],
                        'verificationcode' => $verificationcode,
                        'uid' => $exphone['id'],
                    ];

                    $data = config()['requestsuccess'];
                    $data['data'] = $infoData;
                    $data['code'] = 200;
                    $data['message'] = '登录成功';
                    return json($data);

                }else{
                    $data = config()['requestsuccess'];
                    $data['data'] = [
                        'telephone' => $_POST['telephone']
                    ];
                    $data['code'] = 202;
                    $data['message'] = '密码错误';
                    return json($data);
                }

            }else{

                $data = config()['requestsuccess'];
                $data['data'] = $exphone;
                $data['code'] = 201;
                $data['message'] = '账号不存在';
                return json($data);

            }

        }else{

            $data = config()['requestsuccess'];
            $data['data'] = [
                'telephone' => $_POST['telephone']
            ];
            $data['code'] = 205;
            $data['message'] = '手机号不合法';
            return json($data);
        }

    }

}