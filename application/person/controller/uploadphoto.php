<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/14
 * Time: 14:42
 */

namespace app\person\controller;

use app\login\controller\loadimage;
use app\login\controller\token;
use app\load\controller\loadimg;

class uploadphoto
{
    public function index () {

        if (!token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['image'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'iamge param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return loadimg::myloadimage($_POST['image']);
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