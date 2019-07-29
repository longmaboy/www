<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/29
 * Time: 13:36
 */

namespace app\malongbo\controller;

use think\Db;

class Allcommentstatus
{

    public function index() {
        return self::commentStatus();
    }

    /**
     * 评论数已读
     * @return \think\response\Json
     */
    public function commentStatus() {

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

                    return $this->setCommentStatus($_POST['uid']);
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

    protected function setCommentStatus($uid) {

        $update_data = ['uidstatus' => '1'];
        Db::table('sf_comment')
            ->where('createuid',$uid)
//            ->where('reuid',$uid)
            ->where('status','1')
            ->where('uidstatus','0')
            ->update($update_data);

        $data = config()['requestsuccess'];
        $data['code'] = 200;
        $data['message'] = '设置评论已阅读成功';
        $data['data'] = '';
        return json($data);

    }

    /**
     * 评论列表
     * @return \think\response\Json
     */
    public function personCommentList() {

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

                    return $this->getPersonCommentList($_POST['page']);
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


    protected function getPersonCommentList($page) {

        $uid = $_POST['uid'];

        $list = Db::table('sf_comment')
            ->where('status',1)
            ->where('createuid',$uid)
//            ->where('reuid',$uid)
            ->where('uid','<>',$uid)
            ->order('commenttime','desc')
            ->alias('a')
            ->join('sf_user w','a.uid = w.id')
//            ->join('sf_content h','a.contentid = h.id')
            ->field('a.*,w.nickname,w.photo,w.school')
            ->paginate(10,true,['page' => $page ])
            ->each(function($item, $key){

                $content = Db::table('sf_content')->where('id',$item['contentid'])->find();
                $item['content'] = $content['content'];

                if (isset($item['reuid'])) {
                    $user = Db::table('sf_user')->where('id',$item['reuid'])->find();
                    $reviewInfo = [
                        'nickname' => $user['nickname'],
                        'uid' => $user['id'],
                        'school' => $user['school'],
                        'photo' => $user['photo']
                    ];
                    $item['reviewInfo'] = $user;

                    return $item;
                }else{
                    return $item;
                }

            })
            ->toArray();

        $data = config()['requestsuccess'];
        $data['code'] = 200;
        $data['message'] = '评论获取成功';
        $data['data'] = $list['data'];
        return json($data);

    }
    
}