<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/5/31
 * Time: 09:37
 */

namespace app\admin\controller;
use think\Db;
define('UPLOAD_DIR','./uploadimage/');

class Index{

    public function index() {


        //1.连接数据库

        $link = mysqli_connect('localhost', 'root', 'root123456', 'wishdb', '3306');

        if (!$link) {
            echo '数据库连接失败，错误代码：'.mysqli_connect_errno().' 错误信息：'.mysqli_connect_error().'<br />';
        }else{
            return json_encode('数据库连接成功');
        }

//        echo "<br/>";
//        echo '------------------------------------';
//        echo "<br/>";
//
//        $exphone = Db::table('sf_user')->where('telephone','13312345678')->find();
//
//        var_dump($exphone);


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