<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/29
 * Time: 13:09
 */

namespace app\malongbo\controller;

use think\Db;

class Setschool
{
    public function index() {

        return self::mySchool();

    }

    /**
     * 设置我的学校api
     * @return \think\response\Json
     */
    public function mySchool() {

        if (!Token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (Token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['school']) || !isset($_POST['sid'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->setMySchool($_POST['school'],$_POST['sid']);
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

    protected function setMySchool($school,$sid) {

        //不频繁 先查
        $exphone = Db::table('sf_user')->where('id',$_POST['uid'])->find();

        if (isset($exphone) && !empty($exphone)) {

            //更新
            $up_school = ['school' => $school, 'school_id' => $sid];
            Db::name('sf_user')
                ->where('id', $_POST['uid'])
                ->update($up_school);

            $infoData = [
                'sid'   => $sid,
                'school'  => $school,
            ];

            $data = config()['requestsuccess'];
            $data['data'] = $infoData;
            $data['code'] = 200;
            $data['message'] = '设置学校成功';
            return json($data);

        }else{

            $data = config()['requestsuccess'];
            $data['data'] = $exphone;
            $data['code'] = 201;
            $data['message'] = '用户不存在';
            return json($data);

        }

    }
}