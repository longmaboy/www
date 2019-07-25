<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2019/6/19
 * Time: 16:42
 */

namespace app\login\controller;


class Token
{

    //设置AES秘钥
    private static $aes_key = 'bUYJ3nTV6VBasdJF'; //此处填写前后端共同约定的秘钥


    static protected function get_all_headers() {

        // 忽略获取的header数据
        $ignore = array('host','accept','content-length','content-type');

        $headers = array();

        foreach($_SERVER as $key=>$value){
            if(substr($key, 0, 5)==='HTTP_'){
                $key = substr($key, 5);
                $key = str_replace('_', ' ', $key);
                $key = str_replace(' ', '-', $key);
                $key = strtolower($key);

                if(!in_array($key, $ignore)){
                    $headers[$key] = $value;
                }
            }

        }

        return $headers;

    }

    public function setHeader() {

        $header = self::get_all_headers();

        foreach ($header as $key=>$value) {

            header($key.':'.$value);

        }

    }

    /**
     * 验证header token
     * @return bool
     */
    static public function verification_header_token() {

        $getheader = self::get_all_headers();

        if ($getheader['token']) {

            $tokenString = $getheader['token'];

            $rtoken = self::authDecode($tokenString);

            $arr = explode('.',$rtoken);

            $token = $arr[0];

            $uid = $arr[1];

            $ntime = $arr[2];

            if (microtime_float() - $ntime > 1*24*3600) {

                return false;

            }else{

                self::create_header_token($token,$uid,microtime_float());

                return true;
            }


        }else{
            return false;
        }


    }

    /**
     * 验证没登录的header token
     * @return bool
     */
    static public function verification_nologin_header_token() {

        $getheader = self::get_all_headers();

        if ($getheader['token']) {

            $tokenString = $getheader['token'];

            $rtoken = self::authDecode($tokenString);

            $arr = explode('.',$rtoken);

            $token = $arr[0];

            $uid = $arr[1];

            $ntime = $arr[2];

            if (microtime_float() - $ntime > 1*24*3600) {

                return false;

            }else{

                self::create_header_token($token,$uid,microtime_float());

                return true;
            }


        }else{
            return false;
        }


    }

    /**
     * @param $btoken
     * @param $verification
     * @return bool
     */
    static public function verification_body_token($btoken, $verification) {

        $getheader = self::get_all_headers();

        $tokenString = $getheader['token'];

        $rtoken = self::authDecode($tokenString);

        $arr = explode('.',$rtoken);

        $htoken = $arr[0];

        if (md5($htoken.'.'.$verification) == $btoken) {
            return true;
        }else{
            return false;
        }

    }

    /**
     * 加密生成 header token
     * @param $token
     * @param $uid
     * @param $time
     * @return bool
     */
    static public function create_header_token($token, $uid, $time) {

        $header = self::get_all_headers();

        if (!isset($header['device'])) {
            return false;
        }

        $string = $token.'.'.$uid.'.'.$time.'.'.$header['device'];
        $ntoken = self::myauthcode($string, 'ENCODE');
        header('token:'.$ntoken);
        return true;
    }

    /**
     * md5生成验证摘要
     * @param $token
     * @param $Verificationcode
     * @return string
     */
    static public function create_body_token($token, $verificationcode) {
        return md5($token.'.'.$verificationcode);
    }

    /**
     * 解密
     * @param $string
     * @return string
     */
    static public function authDecode($string) {
        return self::myauthcode($string, "DECODE");
    }


    /**
     * @param $string 要加密/解密的字符串
     * @param string $operation
     * @param string $aes_key
     * @return string
     */

    static public function myauthcode($string, $operation = 'DECODE') {

        if ($operation == 'DECODE') {

            $decrypted = openssl_decrypt(base64_decode($string), 'AES-128-ECB', self::$aes_key, OPENSSL_RAW_DATA);
            return $decrypted;

        }else{

            $data = openssl_encrypt($string, 'AES-128-ECB', self::$aes_key, OPENSSL_RAW_DATA);
            $data = base64_encode($data);

            return $data;

        }


    }


}