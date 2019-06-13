<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/5/31
 * Time: 09:37
 */

namespace app\admin\controller;

use think\Db;
use think\paginator;
use think\Config;

class Index {

    public function index() {

        if (isset($_POST['type']) && isset($_POST['name'])) {

            if ($_POST['type'] == @"1" && $_POST['name'] == "list") {

                $data = Db::table('user')->select()->paginate(2);

                echo json_encode($data);

                return;
            }else{
                echo json_encode('我是后台的控制器');
                return;
            }
        }else{
            echo json_encode('参数不对');

            return;
        }

    }

    public function test() {
//        return '我是后台控制器test方法';

        $data = config();

//        dump($data);
        dump($data['app']);





    }



}