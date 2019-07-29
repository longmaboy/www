<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/29
 * Time: 13:33
 */

namespace app\malongbo\controller;

use think\Db;

class Thumbslist
{

    public function index() {
        return self::thumbsList();
    }

    /**
     * 点赞列表
     * @return \think\response\Json
     */
    public function thumbsList() {

        if (!Token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (Token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['uid']) || !isset($_POST['page'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->getThumbsList($_POST['page']);
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

    protected function getThumbsList($page) {

        $uid = $_POST['uid'];

        $list = Db::table('sf_thumbs')
            ->where('status',1)
            ->where('createuid',$uid)
            ->where('uid','<>',$uid)
            ->order('thumbstime','desc')
            ->alias('a')
            ->join('sf_user w','a.uid = w.id')
//            ->join('sf_content h','a.contentid = h.id')
            ->field('a.*,w.nickname,w.photo,w.school')
            ->paginate(10,true,['page' => $page ])
            ->each(function($item, $key){

                $content = Db::table('sf_content')->where('id',$item['contentid'])->find();
                $item['content'] = $content['content'];
                return $item;
            })
            ->toArray();

        $data = config()['requestsuccess'];
        $data['code'] = 200;
        $data['message'] = '点赞获取成功';
        $data['data'] = $list['data'];
        return json($data);

    }
}