<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/17
 * Time: 17:00
 */

namespace app\person\controller;

use app\login\controller\token;
use app\school\controller\publish;
use think\Db;

class myschool
{
    /**
     * 学校省份api
     * @return \think\response\Json
     */
    public function schoolprovince () {

        if (!token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                return $this->queryProvince();

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

    protected function queryProvince() {

        $result = Db::table('sf_university')->distinct(true) ->field("province")->select();

        if (isset($result) && !empty($result)) {

            $data = config()['requestsuccess'];
            $data['code'] = 200;
            $data['message'] = '城市查询成功';
            $data['data'] = $result;
            return json($data);

        }else{

            $data = config()['requestsuccess'];
            $data['code'] = 203;
            $data['message'] = '查询失败';
            $data['data'] = $result;
            return json($data);

        }

    }

    /**
     * 学校列表api
     * @return \think\response\Json
     */
    public function schoollist() {

        if (!token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['province'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->queryProvinceSChool($_POST['province']);
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

    protected function queryProvinceSChool($province)
    {

        $result = Db::table('sf_university')->where('province', $province)->select();

        if (isset($result) && !empty($result)) {

            $data = config()['requestsuccess'];
            $data['code'] = 200;
            $data['message'] = '学校查询成功';
            $data['data'] = $result;
            return json($data);

        }else{

            $data = config()['requestsuccess'];
            $data['code'] = 203;
            $data['message'] = '查询失败';
            $data['data'] = $result;
            return json($data);

        }

    }

    /**
     * 设置我的学校api
     * @return \think\response\Json
     */
    public function mySchool() {

        if (!token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['school']) || !isset($_POST['sid'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->setMySchool($_POST['school'],$_POST['sid']);
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

    protected function setMySchool($school,$sid) {

        //不频繁 先查
        $exphone = Db::table('sf_user')->where('id',$_POST['uid'])->find();

        if (isset($exphone) && !empty($exphone)) {

            //更新
            $up_school = ['school' => $school, 'school_id' => $sid];
            Db::name('sf_user')
                ->where('id', $_POST['uid'])
                ->update($up_school);

            $infoData = [
                'sid'   => $sid,
                'school'  => $school,
            ];

            $data = config()['requestsuccess'];
            $data['data'] = $infoData;
            $data['code'] = 200;
            $data['message'] = '设置学校成功';
            return json($data);

        }else{

            $data = config()['requestsuccess'];
            $data['data'] = $exphone;
            $data['code'] = 201;
            $data['message'] = '用户不存在';
            return json($data);

        }

    }

    /**
     * 获取个人中心点赞评论数
     * @return \think\response\Json
     */
    public function commentThumbCount() {

        if (!token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

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


    /**
     * 点赞数已读
     * @return \think\response\Json
     */
    public function thumbsStatus() {

        if (!token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

                if (!isset($_POST['uid'])) {
                    $data = config()['requestsuccess'];
                    $data['code'] = 203;
                    $data['message'] = 'param error';
                    $data['data'] = '';
                    return json($data);
                }else{

                    return $this->setThumbsStatus($_POST['uid']);
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

    protected function setThumbsStatus($uid) {

        $update_data = ['uidstatus' => '1'];
        Db::table('sf_thumbs')
            ->where('createuid',$uid)
            ->where('status','1')
            ->where('uidstatus','0')
            ->update($update_data);

        $data = config()['requestsuccess'];
        $data['code'] = 200;
        $data['message'] = '设置点赞已阅读成功';
        $data['data'] = '';
        return json($data);

    }

    /**
     * 点赞列表
     * @return \think\response\Json
     */
    public function thumbsList() {

        if (!token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

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


    /**
     * 评论数已读
     * @return \think\response\Json
     */
    public function commentStatus() {

        if (!token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

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

        if (!token::verification_header_token()) {
            $data = config()['requestsuccess'];
            $data['code'] = 301;
            $data['message'] = 'token error';
            $data['data'] = '';
            return json($data);
        }

        if (isset($_POST['token']) && isset($_POST['verificationcode'])) {

            if (token::verification_body_token($_POST['token'],$_POST['verificationcode'])) {

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