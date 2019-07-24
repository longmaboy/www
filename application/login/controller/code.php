<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/6/19
 * Time: 14:10
 */

namespace app\login\controller;

use think\Db;

class code
{
    public function index() {

        if (isset($_POST['telephone'])) {

            if (verification_phone($_POST['telephone'])) {

                return self::sendCode();

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

    protected function sendCode() {

        $code = randomNumbers(4);

        $exdata = Db::table('sf_code')->where('telephone',$_POST['telephone'])->where('send_time', '>', strtotime(date("Y-m-d"),time()))->select();

        if (!isset($exdata) || count($exdata) < 5) {

            $data = ['code' => $code, 'send_time' => microtime_float(), 'telephone' => $_POST['telephone']];

            $result = Db::table('sf_code')->insert($data);

            if ($result == 1) {

                //调用发送验证码
                $config = config()['app'];
                if ($config['sendcode']) {
                    self::sendCodeForMessage($code);
                }

                $data = config()['requestsuccess'];
                $data['code'] = 200;
                $data['message'] = '验证码发送成功';
                return json($data);

            }else{
                $data = config()['requestsuccess'];
                $data['code'] = 201;
                $data['message'] = '验证码发送出错';
                return json($data);
            }

        }else{

            $data = config()['requestsuccess'];
            $data['code'] = 201;
            $data['message'] = '验证码发送出错';
            return json($data);

        }

    }

    //发送短信到手机
    protected function sendCodeForMessage($code) {



    }


}