<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/29
 * Time: 13:02
 */

namespace app\malongbo\controller;

use think\Db;

class Province
{
    public function index() {

        if (!Token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (Token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                return $this->queryProvince();

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

    protected function queryProvince() {

        $result = Db::table('sf_university')->distinct(true) ->field("province")->select();

        if (isset($result) && !empty($result)) {

            $data = config()['requestsuccess'];
            $data['code'] = 200;
            $data['message'] = '城市查询成功';
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