<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/29
 * Time: 13:11
 */

namespace app\malongbo\controller;

use think\Db;

class Schoolcircle
{
    public function index() {

        return self::schoolcircles();

    }

    /**
     * 学校圈
     * @return \think\response\Json
     */
    public function schoolcircles() {

        if (!Token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (Token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['page'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->getSchoolCircle($_POST['page']);
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

    protected function getSchoolCircle($page = 1) {

        $list = Db::table('sf_content')
            ->where('status',1)
            ->order('createtime','desc')
            ->alias('a')
            ->join('sf_user w','a.uid = w.id')
            ->field('a.*,w.nickname,w.photo,w.school,w.isStudent')
            ->paginate(10,true,[ 'page' => $page ])
            ->each(function($item, $key){

                $thumbs = Db::table('sf_thumbs')->where('contentid',$item['id'])->where('status','1')->select();

                $item['thumbCount'] = count($thumbs);

                if (isset($_POST['uid'])) {
                    $isThumb = Db::table('sf_thumbs')->where('uid',$_POST['uid'])->where('contentid',$item['id'])->find();
                    if (isset($isThumb) && $isThumb['status'] == '1') {
                        $item['isThumb'] = '1';
                    }else{
                        $item['isThumb'] = '0';
                    }
                }else{
                    $item['isThumb'] = '0';
                }


                $comment = Db::table('sf_comment')->where('contentid',$item['id'])->select();

                $item['commentCount'] = count($comment);

                return $item;
            })
            ->toArray();

        $data = config()['requestsuccess'];
        $data['code'] = 200;
        $data['message'] = '';
        $data['data'] = $list['data'];
        return json($data);

    }

    /**
     * 获取评论
     * @return \think\response\Json
     */
    public function commentList() {

        if (!Token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (Token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['contentid']) || !isset($_POST['page'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->getCommentList($_POST['contentid'],$_POST['page']);
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

    protected function getCommentList($contentid,$page) {

        $list = Db::table('sf_comment')
            ->where('status',1)
            ->where('contentid',$contentid)
            ->order('commenttime','desc')
            ->alias('a')
            ->join('sf_user w','a.uid = w.id')
            ->field('a.*,w.nickname,w.photo,w.school')
            ->paginate(10,true,['page' => $page])
            ->each(function($item, $key){

                if (isset($item['reuid'])) {
                    $user = Db::table('sf_user')->where('id',$item['reuid'])->find();
                    $reviewInfo = [
                        'nickname' => $user['nickname'],
                        'uid' => $user['id'],
                        'school' => $user['school'],
                        'photo' => $user['photo']
                    ];
                    $item['reviewInfo'] = $reviewInfo;

                    return $item;
                }else{
                    return $item;
                }

            })
            ->toArray();

        $data = config()['requestsuccess'];
        $data['code'] = 200;
        $data['message'] = '评论内容获取成功';
        $data['data'] = $list['data'];
        return json($data);

    }

    /**
     * 保存评论
     * @return \think\response\Json
     */
    public function comment() {

        if (!Token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (Token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['contentid']) || !isset($_POST['uid']) || !isset($_POST['comment']) || !isset($_POST['createuid'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    if (isset($_POST['reuid'])) {

                        return $this->setCommentsWithuid($_POST['contentid'],$_POST['uid'],$_POST['comment'],$_POST['reuid']);

                    }else{

                        return $this->setComments($_POST['contentid'],$_POST['uid'],$_POST['comment']);

                    }
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

    protected function setComments($contentid, $uid, $comment) {

        $up_data = [
            'contentid' => $contentid,
            'uid' => $uid,
            'comment' => $comment,
            'commenttime' => microtime_float(),
            'createuid' => $_POST['createuid']
        ];

        Db::table('sf_comment')
            ->data($up_data)
            ->insert();

        $data = config()['requestsuccess'];
        $data['code'] = 200;
        $data['message'] = '评论成功';
        $data['data'] = $up_data;
        return json($data);


    }

    protected function setCommentsWithuid($contentid, $uid, $comment, $reuid) {

        $up_data = [
            'contentid' => $contentid,
            'uid' => $uid,
            'comment' => $comment,
            'commenttime' => microtime_float(),
            'reuid' => $reuid,
            'createuid' => $_POST['createuid']
        ];

        Db::table('sf_comment')
            ->data($up_data)
            ->insert();

        $data = config()['requestsuccess'];
        $data['code'] = 200;
        $data['message'] = '评论成功';
        $data['data'] = $up_data;
        return json($data);


    }




}