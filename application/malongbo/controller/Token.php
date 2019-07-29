<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/7/29
 * Time: 12:12
 */

namespace app\malongbo\controller;


class Token
{
    static public function create_header_token()
    {
        header('token:'.'afbsu80qhunjsvnjajiqj9qhiuknqjbfwbjfb');
        return true;
    }

    static public function create_body_token() {
        return 'afdgsb3q4erwgsbdfgadjianakbk';
    }

    static public function verification_header_token() {
        return true;
    }

    static public function verification_body_token() {
        return true;
    }



}