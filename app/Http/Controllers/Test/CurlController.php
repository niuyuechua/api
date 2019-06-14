<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class CurlController extends Controller
{

    //客户端

    //百度
    public function get(){
        $url="https://www.baidu.com/";
        //初始化curl会话
        $ch=curl_init($url);
        //设置curl传输选项
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,false);
        //执行会话
        curl_exec($ch);
        //关闭会话
        curl_close($ch);
    }
    //天气
    public function get2(){
        $url="http://api.k780.com?app=weather.future&weaid=北京&appkey=42246&sign=94cfdcf87e9594bdbc981a6e349fd50f&format=json";
        //初始化curl会话
        $ch=curl_init($url);
        //设置curl传输选项
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        //执行会话
        $data=curl_exec($ch);
        $data=json_decode($data,true);
        dump($data);
        //关闭会话
        curl_close($ch);
    }
    //获取access_token
    public function get3(){
        $key="set_access_token";
        $data=Redis::get($key);
        if($data){
            //echo "有缓存";
            //dump($data);
        }else{
            //echo "没有缓存";
            $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxb6e65a6dbd6cfb06&secret=9fdf084e4ff69341e638e2e7941e8ce8";
            //初始化curl会话
            $ch=curl_init($url);
            //设置curl传输选项
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            //执行会话
            $response=curl_exec($ch);
            $arr=json_decode($response,true);       //将json字符串转化成数组
            //做缓存
            Redis::set($key,$arr['access_token']);
            Redis::expire($key,3600); //设置时间（30）
            $data=$arr['access_token'];
            //dump($data);
            //关闭会话
            curl_close($ch);
        }
        return $data;
    }
    public function post(){
        //form-data数据
        $url="http://api.com/test/post";
        $post_data=[
            'name'=>'lisi',
            'age'=>'18'
        ];
        //x-www-form-urlencoded数据
        //$post_data="name=lisi&age=19";  //curl默认将它转化成数组
        //初始化curl会话
        $ch=curl_init($url);
        //设置curl传输选项
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,false);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
        //curl_setopt($ch,CURLOPT_POST,true);
        //执行会话
        curl_exec($ch);
        $errno=curl_errno($ch);
        $error=curl_error($ch);
        dump($errno);
        dump($error);
        //关闭会话
        curl_close($ch);
    }
    public function post2(){
        $url="http://api.com/test/post2";
        //raw数据
        $post_data='上善若水';
        //初始化curl会话
        $ch=curl_init($url);
        //设置curl传输选项
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,false);
        //curl_setopt($ch,CURLOPT_BINARYTRANSFER,true);     //设为TRUE，将在启用 CURLOPT_RETURNTRANSFER 时，返回原生的（Raw）输出
        curl_setopt($ch,CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($ch,CURLOPT_POST,true);
        //执行会话
        curl_exec($ch);
        //关闭会话
        curl_close($ch);
    }
    //创建菜单
    public function post3(){
        $post_arr=[
            'button' => [
                [
                    'type'=>'click',
                    'name'=>'功能说明',
                    'key'=>'function declaration',
                ],
                [   'name'=>'娱乐',
                    'sub_button'=> [
                        ['type'=>'view',
                            'name'=>'QQ音乐',
                            'url'=>'http://y.qq.com/',
                        ],
                        [
                            'type'=>'view',
                            'name'=>'王者荣耀官网',
                            'url'=>'https://pvp.qq.com/',
                        ],
                    ],
                ],
            ],
        ];
        $json_str=json_encode($post_arr, JSON_UNESCAPED_UNICODE);   //加参数二可处理含中文的数组
        //dump($json_str);
        $url= 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->get3();
        //初始化curl会话
        $ch=curl_init($url);
        //设置curl传输选项
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$json_str);
        curl_setopt($ch,CURLOPT_POST,true);
        //执行会话
        $res=curl_exec($ch);
        $res=json_decode($res,true);
        dump($res);
        //关闭会话
        curl_close($ch);
    }
    //curl文件上传
    public function post4(){
        $url = "http://api.com/test/post4";
        $post_data = array(
                "filename" => "user2-160x160.jpg",
                "bar" => "foo",
                //要上传文件的本地地址
                "file" => "@/image/user2-160x160.jpg"
        );
        $ch = curl_init();
        curl_setopt($ch , CURLOPT_URL ,$url);
        curl_setopt($ch , CURLOPT_RETURNTRANSFER,false);    //不写此选项，默认直接输出
        curl_setopt($ch , CURLOPT_POST,1);
        curl_setopt($ch , CURLOPT_POSTFIELDS,$post_data);
        curl_exec($ch);
        curl_close($ch);
    }
    //guzzle上传文件
    public function upfile(){
        $url = "http://api.com/test/upfile";
        $res='image/user2-160x160.jpg';
        $client=new Client();
        $response = $client->request('GET',$url,[
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($res,'r'),
                ]
            ]
        ]);
        $json = $response->getBody();
        $arr=json_decode($json,true);
        dump($arr);
    }
    public function enc(){
        $url="http://api.com/test/dec";
        $data="admin123";
        $enc_data=$this->symEnc($data);
        dump($enc_data);
        $base_data=base64_encode($enc_data);
        dump($base_data);
        //初始化curl会话
        $ch=curl_init($url);
        //设置curl传输选项
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,0);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$base_data);
        curl_setopt($ch,CURLOPT_POST,true);
        //执行会话
        curl_exec($ch);
        //关闭会话
        curl_close($ch);
    }
    public function enc2(){
        $url="http://api.com/test/dec";
        $data="admin123";
        $enc_data=$this->symEnc($data);
        //dump($enc_data);
        $base_data=base64_encode($enc_data);
        //dump($base_data);
        $client=new Client();
        $res=$client->request('POST',$url,[
            'body' => $base_data
        ]);
        //获取响应状态码和结果
        $code = $res->getStatusCode(); // 200
        $reason = $res->getReasonPhrase(); // OK
        dump($code);
        dump($reason);
        $response=$res->getBody();
        echo $response;
    }
    //对称加密
    public function symEnc($data){
        $method='AES-128-CBC';
        $key='password';
        $options=0;
        $iv='chushixiangliang';
        $enc_data=openssl_encrypt($data,$method,$key,$options,$iv);
        return $enc_data;
    }
    //非对称加密
    public function rsa(){
        $url="http://api.com/test/rsadec";
        $data="上善若水任方圆";
        $priv_key=openssl_pkey_get_private("file://".storage_path("keys/priv.pem"));
        openssl_private_encrypt($data,$enc_data,$priv_key);
        //dump($enc_data);
        $client=new Client();
        $res=$client->request('POST',$url,[
            'body' => $enc_data
        ]);
        $response=$res->getBody();
        echo $response;
    }

    //对称加密+验签
    public function aes(){
        $url="http://lumen.com/aes";
        $data="你才是大人物";
        $enc_data=$this->symEnc($data);
        $priv_key=openssl_pkey_get_private("file://".storage_path("keys/priv.pem"));
        openssl_sign($enc_data,$signature,$priv_key);
        //需要将传输的数据和签名一起加密
        $arr=[
            'enc_data'=>$enc_data,
            'signature'=>$signature
        ];
        $client=new Client();
        $res=$client->request('POST',$url,[
            'form_params' => $arr
        ]);
        $response=$res->getBody();
        echo $response;
    }
    public function aesRes(){
        $enc_data=$_POST;
        $api_pub_key=openssl_pkey_get_public("file://".storage_path("keys/lumen-pub.key"));
        $res=openssl_verify($enc_data['res_data'],$enc_data['signature'],$api_pub_key);
        if($res){
            $dec_data=$this->aesDec($enc_data['res_data']);
            dump($dec_data);
        }else{
            echo '验签未通过';
        }
    }
    //对称解密
    public function aesDec($enc_data){
        $method='AES-128-CBC';
        $key='password';
        $options=0;
        $iv='chushixiangliang';
        $dec_data=openssl_decrypt($enc_data,$method,$key,$options,$iv);
        return $dec_data;
    }
}
