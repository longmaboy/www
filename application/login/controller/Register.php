<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/6/14
 * Time: 09:50
 */

namespace app\login\controller;


use think\Db;


class Register
{
    public function index() {

        if (isset($_POST['telephone']) && isset($_POST['password'])) {

            if (verification_phone($_POST['telephone'])) {

                return self::register();

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
            $data['message'] = 'parameter error';
            $data['code'] = 204;
            $data['success'] = 0;
            return json($data);

        }

    }


    protected function register() {

        $exphone = Db::table('sf_user')->where('telephone',$_POST['telephone'])->find();

        if (!isset($exphone)) {

            $data = [
                'telephone' => $_POST['telephone'],
                'password' => md5(md5($_POST["password"]).'a'),
                "token" => randomkeys(16),
                'register_time' => microtime_float(),
            ];

            $result = Db::name('sf_user')->insert($data);

            if (isset($result)) {

                $data = config()['requestsuccess'];
                $data['message'] = '注册成功';
                $data['data'] = $result;
                return json($data);

            }else{

                $data = config()['requestsuccess'];
                $data['code'] = 203;
                $data['message'] = '注册失败';
                $data['data'] = '';
                return json($data);
            }

        }else{
            $data = config()['requestsuccess'];
            $data['code'] = 201;
            $data['message'] = '账号已存在';
            return json($data);

        }

    }



}