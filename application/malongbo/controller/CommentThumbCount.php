<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/29
 * Time: 12:31
 */

namespace app\malongbo\controller;

use think\Db;

class CommentThumbCount
{
    public function index() {
        return self::commentThumbCount();
    }

    /**
     * 获取个人中心点赞评论数
     * @return \think\response\Json
     */
    public function commentThumbCount() {

        if (!Token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (Token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['uid'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->getCommentWithThumbCount($_POST['uid']);
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

    protected function getCommentWithThumbCount($uid) {

        $thumbs = Db::table('sf_thumbs')
            ->where('createuid',$uid)
            ->where('status','1')
            ->where('uid','<>',$uid)
            ->where('uidstatus','0')
            ->select();

        if (isset($thumbs)) {
            $item['thumbCount'] = count($thumbs);
        }else{
            $item['thumbCount'] = '0';
        }

        $comments = Db::table('sf_comment')
            ->where('createuid',$uid)
//            ->where('reuid',$uid)
            ->where('status','1')
            ->where('uid','<>',$uid)
            ->where('uidstatus','0')
            ->select();

        if (isset($comments)) {
            $item['commentCount'] = count($comments);
        }else{
            $item['commentCount'] = '0';
        }


        $data = config()['requestsuccess'];
        $data['code'] = 200;
        $data['message'] = '未读评论点赞数获取成功';
        $data['data'] = $item;
        return json($data);
    }
}