<?php
namespace app\controller;
use app\model\User;
use think\facade\Request;
use think\facade\Db;
class Home {
    public function getUser() {
        $Mid = Request::param();
        $ManData = Db::table('houmanuser');
        $userinfo = Db::table('houmanuser')->where('Mid', $Mid['Mid'])->field('ManName,Mid,ManSex,ManPhone,userRole,ManImage')->select();
        return $userinfo;
    }
}