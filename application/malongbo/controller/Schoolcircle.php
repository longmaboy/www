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
}