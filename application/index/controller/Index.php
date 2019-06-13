<?php
namespace app\index\controller;

use think\App;
use think\Config;
use think\Controller;
use think\Db;
use think\view\driver\Think;

//use app\index\controller\Mytest;

class Index extends Controller
{
    public function index()
    {
//        return "afdafaaf";
//        $this->assign('name',$name);
//        return $this->fetch();
//        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';

        //从数据库去数据
        $data = Db::table('userinfo')->select();

//        json_decode($data);
        //分配数据给页面
        $this->assign('data',$data);

        //加载页面
        return view();

    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

    public function test(){
        return '我是用户自己创建的方法';
    }

    public function diaoyong(){

        //调用前台Mytest控制器

        echo '<hr>';

//        $model = new Mytest;

//        echo $model->test();

        $model2 = controller('Mytest');

        echo $model2->index();

    }

    public function diaoyongs(){

        $models = controller('admin/index');

        echo $models->index();

    }

    public function fangfa() {
        echo $this->diaoyong();

        echo self::test();

        echo Index::diaoyongs();

        echo action('test');

    }

    public function fangfas() {
//        $model = new \app\index\controller\Mytest;

//        echo $model->index();
//
        echo action('Mytest/index');

    }

    public function fangfass() {

        $model = new \app\admin\controller\Index;

        echo $model->index();

        echo '<hr>';

        echo action('admin/index/test');

    }

    public function getConfig() {

        //输出配置文件

        echo config('name');

        echo '<hr>';

        var_dump(config('pathinfo_fetch'));

        echo '<hr>';

        echo '111';

        echo '<hr>';

        dump(\config());

        $data = \config();

        dump($data['trace']);

        dump($data['console']["name"]);


    }

    public function getKuozhan () {
        dump(\config('database'));

//        dump(\config());

        $data = \config();

        dump($data['database']);

        dump($data['info']);

        echo $data['info']['name'];

    }

    public function getChangjing() {
        $data = \config();

        dump($data['database']['database']);
        dump($data['database']);

    }

    public function getMokuai() {

//        dump(\config());
        $data = \config();

        dump($data['config']);
    }

//$this->links[$linkNum] = new PDO($config['dsn'], $config['username'], $config['password'], $params);


}
