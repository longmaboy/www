<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/31
 * Time: 21:46
 */

namespace app\malongbo\controller;


class Feedback
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

                if (!isset($_POST['content'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->saveFeedbak($_POST['content'],$_POST['uid']);
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

    protected function saveFeedbak($content, $uid) {

        $up_data = [
            'content' => $content,
            'uid' => $uid,
        ];

        Db::table('sf_feedback')
            ->data($up_data)
            ->insert();

        $data = config()['requestsuccess'];
        $data['code'] = 200;
        $data['message'] = '提交成功';
        $data['data'] = $up_data;
        return json($data);
    }
}