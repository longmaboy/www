<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/6/14
 * Time: 09:18
 */

namespace app\login\controller;

use think\Db;

class Index extends Token
{
    public function index() {

        if (isset($_POST['telephone']) && isset($_POST['password'])) {

            if (verification_phone($_POST['telephone'])) {

                return self::login();

            }else{

                $data = config()['requestsuccess'];
                $data['data'] = [
                    'telephone' => $_POST['telephone']
                ];
                $data['code'] = 205;
                $data['message'] = '手机号不合法';
                return json($data);
            }

        }else{

            $data = config()['requestsuccess'];
            $data['code'] = 201;
            $data['message'] = 'param error';
            return json($data);
        }
    }


    protected function login() {

        $exphone = Db::table('sf_user')->where('telephone',$_POST['telephone'])->find();

        if (isset($exphone) && !empty($exphone)) {

            if (md5(md5($_POST["password"]).'a') == $exphone['password']) {

                //更新登录时间
                $up_time = ['update_time' => microtime_float()];
                Db::name('sf_user')
                    ->where('telephone', $_POST['telephone'])
                    ->update($up_time);

                $verificationcode = randomkeys(6);
                $token = $exphone['token'];
                $uid = $exphone['id'];

                if (!Token::create_header_token($token,$uid,microtime_float())) {

                    $data = config()['requestsuccess'];
                    $data['data'] = '';
                    $data['code'] = 301;
                    $data['message'] = 'param error';
                    return json($data);
                }

                $rtTken = Token::create_body_token($token,$verificationcode);

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

    }



}