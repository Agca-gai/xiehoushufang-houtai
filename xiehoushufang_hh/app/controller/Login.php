<?php
namespace app\controller;

use app\model\User;
use think\facade\Request;
use think\facade\Db;

class Login {
    public function getUser() {
        // $user = User::select();
        // return $user;

        if(Request::method()){
            $userdata = Request::param();
            // return $userdata['username'];
            // dump($userdata);
            $Mid = $userdata['username'];
            $ManPassword = $userdata['password'];
            if($userdata) {
                $ManData = Db::table('houmanuser');
                // id和密码
                $panduanMid = $ManData->where('Mid', $Mid)->find();
                $panduanManPassword = $ManData->where('ManPassword', $ManPassword)->find();
                // userinfo 前端保存在storage
                $userinfo = Db::table('houmanuser')->where('Mid', $Mid)->field('ManName,Mid,ManSex,ManPhone,userRole,ManImage')->select();
                if(isset($panduanMid) && isset($panduanManPassword)) {
                    $data = array('code'=> 0, 'mes'=> '登录成功！', 'data'=>$userinfo);
                    return json($data);
                } else if (isset($panduanMid)) {
                    $data = array('code'=> 1, 'mes'=> '用户密码错误！');
                    return json($data);
                    // $insertdata = [
                    //     'Mid'     =>     $Mid,
                    //     'ManPassword' =>     $ManPassword,
                    //     'ManName'     =>     '用户'.$Mid
                    // ];
                    // $flag = $ManData->insert($insertdata);
                    // if (isset($flag)) {
                    //     $data = array('code'=> '0', 'mes'=> '登录成功！');
                    //     return json($data);
                    // } else {
                    //     $data = array('code'=> '1', 'mes'=> '登录失败！');
                    //     return json($data);
                    // }
                } else {
                    $data = array('code'=> 2, 'mes'=> '用户不存在！');
                    return json($data);
                }
            }
        }
    }
}