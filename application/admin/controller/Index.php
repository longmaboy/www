<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/5/31
 * Time: 09:37
 */

namespace app\admin\controller;

define('UPLOAD_DIR','./uploadimage/');

class Index{

    public function index() {

        echo '12345678';

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