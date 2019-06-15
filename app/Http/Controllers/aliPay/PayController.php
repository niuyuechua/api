<?php

namespace App\Http\Controllers\aliPay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class PayController extends Controller
{
    public function index(){
        $url="https://openapi.alipaydev.com/gateway.do";
        //请求参数
        $biz_content=[
            'subject'=>'测试订单',
            'out_trade_no'=>'1810_'.mt_rand(10000,99999).time(),
            'total_amount'=>mt_rand(1,100),
            'product_code'=>'QUICK_WAP_WAY'
        ];
        //公共参数
        $data=[
            'app_id'=>'2016092500596049',
            'method'=>'alipay.trade.wap.pay',
            'charset'=>'UTF-8',
            'sign_type'=>'RSA2',    //商户生成签名字符串所使用的签名算法类型
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'biz_content'=>json_encode($biz_content)
        ];
        //生成签名
        ksort($data);
        //dump($biz_content);die;
        $str='';
        foreach($data as $k=>$v){
            $str.=$k.'='.$v.'&';
        }
        $str=rtrim($str,'&');
        //dump($str);die;
        $priv_key=openssl_pkey_get_private("file://".storage_path("keys/aliPay-priv.pem"));
        //dump($priv_key);die;
        //不写参数四，默认为SHA1，对应签名算法为RSA；SHA256对应签名算法为RSA2
        openssl_sign($str,$signature,$priv_key,OPENSSL_ALGO_SHA256);
        $signature=base64_encode($signature);
        $data['sign']=$signature;
        //拼接url
        $a='?';
        foreach($data as $key=>$val){
            $a.=$key.'='.urlencode($val).'&'; //urlencode 将字符串以url形式编码
        }
        $trim2 = rtrim($a,'&');
        $url2 = $url.$trim2;
//        $client=new Client();
//        $res=$client->request('POST',$url2,[
//            'form_params' => $data
//        ]);
//        $response=$res->getBody();
//        echo $response;
        echo '等待支付';
        header('refresh:2;url='.$url2);
    }
}
