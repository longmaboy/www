<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/21
 * Time: 21:06
 */

namespace app\school\controller;

use app\login\controller\Token;
use app\load\controller\Loadimg;
use think\Db;

class Publish
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

                    return $this->fabuAction($_POST['content']);
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

    protected function fabuAction($content) {

        if (isset($_POST['images'])) {

            $imgArr = $_POST['images'];

            $imagesStr =  Loadimg::uploadmorepicture($imgArr);

            $up_data = [
                'content' => $content,
                'images'  => $imagesStr,
                'uid'     => $_POST['uid'],
                'createtime' => microtime_float()
            ];

            Db::table('sf_content')
                ->data($up_data)
                ->insert();

            $data = config()['requestsuccess'];
            $data['code'] = 200;
            $data['message'] = '发布成功';
            $data['data'] = $up_data;
            return json($data);

        }else{

            $up_data = [
                'content' => $content,
                'uid'     => $_POST['uid'],
                'createtime' => microtime_float()
            ];

            Db::table('sf_content')
                ->data($up_data)
                ->insert();

            $data = config()['requestsuccess'];
            $data['code'] = 200;
            $data['message'] = '发布成功';
            $data['data'] = $up_data;
            return json($data);
        }


    }
}