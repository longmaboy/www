<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/5/31
 * Time: 09:37
 */

namespace app\admin\controller;

use app\admin\model\SfContent;

define('UPLOAD_DIR','./uploadimage/');

class Index{

    public function index() {

        $con = SfContent::get(['status'=>'1']);

        var_dump($con->select()->toArray());

        echo "<br/>";

//        $list1=SfContent::has('AdminMessage',['user'=>'jiehechen123'])->select();

        $list = $con->paginate(10,true,[
            'page' => 1,
        ])->each(function($item, $key){
            $item['total'] = $key;
            return $item;
        })->toArray();

        var_dump($list);

//        $data = config()['requestsuccess'];
//        $data['code'] = 202;
//        $data['message'] = 'param error';
//        $data['data'] = $list;
//        echo json($data);

//        echo json($list);
    }

    public function test() {
//        return '我是后台控制器test方法';

//        $data = config();

//        dump($data);
//        dump($data['app']);

        $data = $_GET['password'];

        echo md5($_GET["password"]).'a';
        echo md5(md5($_GET["password"]).'a');



    }

    public function imgApp() {



    }


    function digui($n){
        echo $n."digui";
        if($n>0){
            $this-> digui($n-1);
        }else{
            echo "<-->";
        }
        echo $n." ";
    }


}