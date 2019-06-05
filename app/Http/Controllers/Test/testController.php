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
}
