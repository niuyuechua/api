<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\testModel;
use Illuminate\Support\Facades\Redis;

class testController extends Controller
{
    public function index(){
        phpinfo();die;
        Redis::set('aaa',111);
        dump(Redis::get('aaa'));die;
        $data=[
            'name'=>'zhangsan',
            'pwd'=>'123'
        ];
        $id=testModel::insertGetId($data);
        dump($id);
    }

    //服务端

    public function post(){
        dump($_POST);
    }
    public function post2(){
        $data=file_get_contents("php://input");
        dump($data);
    }
    public function post4(){
        dump($_POST);
    }
    public function upfile(){
        echo json_encode($_GET);
    }
    public function dec(){
        $enc_data=file_get_contents("php://input");
        $dec_data=$this->symDec($enc_data);
        dump($dec_data);
    }
    //对称解密
    public function symDec($data){
        $method='AES-128-CBC';
        $key='password';
        $options=0;
        $iv='chushixiangliang';
        $base_data=base64_decode($data);
        $dec_data=openssl_decrypt($base_data,$method,$key,$options,$iv);
        return $dec_data;
    }
    //非对称解密
    public function rsaDec(){
        $enc_data=file_get_contents("php://input");
        $pub_key=openssl_pkey_get_public("file://".storage_path("keys/pub.key"));
        openssl_public_decrypt($enc_data,$dec_data,$pub_key);
        dump($dec_data);
    }
}
