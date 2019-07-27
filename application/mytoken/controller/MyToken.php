<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/27
 * Time: 13:53
 */

namespace app\mytoken\controller;


class MyToken
{
    static public function create_header_token()
    {
        header('token:'.'afbsu80qhunjsvnjajiqj9qhiuknqjbfwbjfb');
        return true;
    }

    static public function create_body_token() {
        return 'afdgsb3q4erwgsbdfgadjianakbk';
    }

}

