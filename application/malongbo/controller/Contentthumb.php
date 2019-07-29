<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/29
 * Time: 13:22
 */

namespace app\malongbo\controller;

use think\Db;

class Contentthumb
{
    public function index() {

        return self::contentthumb();

    }

    /**
     *内容点赞和取消
     * @return \think\response\Json
     */
    public function contentthumb() {

        if (!Token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (Token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['contentid']) || !isset($_POST['uid']) || !isset($_POST['isthumb']) || !isset($_POST['createuid'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->setThumbs($_POST['isthumb']);
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

    protected function setThumbs($thumb) {

        $isThumb = Db::table('sf_thumbs')->where('uid',$_POST['uid'])->where('contentid',$_POST['contentid'])->find();

        if (isset($isThumb)) {

            $update_status = [
                'status' => $thumb,
                'thumbstime' => microtime_float(),
                'uidstatus' => '0'
            ];

            Db::table('sf_thumbs')
                ->where('id',$isThumb['id'])
                ->update($update_status);

            $rtData = [
                'isThumb' => $thumb,
            ];

            $data = config()['requestsuccess'];
            $data['code'] = 200;
            $data['message'] = '';
            $data['data'] = $rtData;
            return json($data);

        }else{

            $update_status = [
                'status' => $thumb,
                'uidstatus' => 0,
                'contentid' => $_POST['contentid'],
                'createuid' => $_POST['createuid'],
                'uid' => $_POST['uid'],
                'thumbstime' => microtime_float()
            ];

            Db::table('sf_thumbs')->data($update_status)->insert();

            $rtData = [
                'isThumb' => $thumb,
            ];

            $data = config()['requestsuccess'];
            $data['code'] = 200;
            $data['message'] = '';
            $data['data'] = $rtData;
            return json($data);

        }

    }

}