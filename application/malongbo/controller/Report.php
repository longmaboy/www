<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/31
 * Time: 17:57
 */

namespace app\malongbo\controller;

use think\Db;

class Report
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

                if (!isset($_POST['contentid']) || !isset($_POST['title']) || !isset($_POST['detail'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->saveReport($_POST['contentid'],$_POST['uid'],$_POST['title'],$_POST['detail']);
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

    protected function saveReport($contentid, $uid, $title, $detail) {

        $up_data = [
            'contentid' => $contentid,
            'uid' => $uid,
            'title' => $title,
            'detail' => $detail,
        ];

        Db::table('sf_report')
            ->data($up_data)
            ->insert();

        $data = config()['requestsuccess'];
        $data['code'] = 200;
        $data['message'] = '举报成功';
        $data['data'] = $up_data;
        return json($data);
    }


}