<?php

namespace App\Http\Controllers\Three;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;


class ThreeController extends Controller
{

    public function __construct()
    {
        $this->status = [
            "200" => "success",
            "210"=>"其他设备以被强制下线",
            "40000" => "必填项不能为空",
            "40005" => "email未注册",
            "40006" => "账号密码输入错误",
        ];
    }

    public function fail($code = null, $msg = null, $data = null)
    {
        $response = [
            "code" => $code,
            "msg" => $msg,
            "data" => $data
        ];
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 登陆页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login()
    {
        return view("three.login");
    }

    /**
     * 登陆操作
     */
    public function loginDo(Request $request)
    {
        $agent = strtolower($_SERVER["HTTP_USER_AGENT"]);
        $info="";
        if (strpos($agent, "windows")) {
            $info = "windows";
        } elseif (strpos($agent, "mac")) {
            $info = "mac";
        } elseif (strpos($agent, "iphone")) {
            $info = "iphone";
        } elseif (strpos($agent, "android")) {
            $info = "android";
        } elseif (strpos($agent, "ipad")) {
            $info = "ipad";
        }
        $obj = new UserModel();
        $loginData = $request->input();
        //验证非空
        if (empty($loginData["email"]) || empty($loginData['pwd'])) {

            return $this->fail("40000", $this->status['40000']);

        }
        $first = UserModel::where(["email" => $loginData['email']])->first();

        //未注册
        if (!$first) {

            return $this->fail("40005", $this->status['40005']);

        }
        //验证密码
        if (!password_verify($loginData['pwd'], $first->pwd)) {

            return $this->fail("40006", $this->status['40006']);

        }

        $uid = $first->uid;
        $username = $first->username;
        $token = $obj->setsalt()->createtoken($uid, $username);
        if(Redis::exists($uid)){
            Redis::set($uid,$info);
            Redis::expire($uid,3600);
            return $this->fail("210", $this->status['210'], $token);
        }else{
            Redis::set($uid,$info);
            Redis::expire($uid,3600);
            return $this->fail("200", $this->status['200'], $token);
        }



    }
}
