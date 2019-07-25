<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/14
 * Time: 14:42
 */

namespace app\person\controller;

use app\login\controller\Loadimage;
use app\login\controller\Token;
use app\load\controller\Loadimg;

class Uploadphoto
{
    public function index () {

        if (!Token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (Token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['image'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'iamge param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return Loadimg::myloadimage($_POST['image']);
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


}