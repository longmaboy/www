<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/29
 * Time: 12:54
 */

namespace app\malongbo\controller;

use think\Db;

class Schoollist
{
    public function index() {
        return self::schoollist();
    }

    /**
     * 学校列表api
     * @return \think\response\Json
     */
    public function schoollist() {

        if (!Token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (Token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['province'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->queryProvinceSChool($_POST['province']);
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

    protected function queryProvinceSChool($province)
    {

        $result = Db::table('sf_university')->where('province', $province)->select();

        if (isset($result) && !empty($result)) {

            $data = config()['requestsuccess'];
            $data['code'] = 200;
            $data['message'] = '学校查询成功';
            $data['data'] = $result;
            return json($data);

        }else{

            $data = config()['requestsuccess'];
            $data['code'] = 203;
            $data['message'] = '查询失败';
            $data['data'] = $result;
            return json($data);

        }

    }

}