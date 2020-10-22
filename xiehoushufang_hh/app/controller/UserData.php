<?php
namespace app\controller;

use app\model\User;
use think\facade\Request;
use think\facade\Db;

class UserData {
    public function findLogin() {
        // $user = User::select();
        // return $user;

        if(Request::method()){
            $userdata = Request::param();
            return json($userdata);
            // dump($userdata);
        }
    }
    public function updateUser() {
        if(Request::method()){
            $userdata = Request::param();
            // return json($userdata['formdata']);
            $formdata = $userdata['formdata'];
            $Mid = $formdata['Mid'];
            // return json($Mid);
            $data = [
                'ManName' =>   $formdata['ManName'],
                'ManSex'  =>   $formdata['ManSex'],
                'ManPhone' =>  $formdata['ManPhone'],
                'ManImage' =>  $formdata['ManImage']
            ];
            if(isset($userdata['formdata'])) {
                $ManData = Db::table('houmanuser');
                // id和密码
                $panduan = $ManData->where('Mid', $Mid)->update($data);
                if (isset($panduan)) {
                    $update = array('code'=> 0, 'mes'=> '更新成功！');
                    return json($update);
                } else {
                    $update = array('code'=> 1, 'mes'=> '更新失败！');
                    return json($update);
                }
            }
        }
    }
    public function operaLog() {
        $getoperadata = Request::param();
        $currentPage = $getoperadata['currentPage'];
        if(Request::method()){
            $operadata = Db::table('operalog')->limit(($currentPage-1)*9, 9)->order('CreateTime', 'desc')->select();
            $pageTotal = Db::table('operalog')->count();
            $returndata = array('pageTotal'=> $pageTotal, 'data'=> $operadata);
            return $returndata;
        }
    }
    public function getoperaLog() {
        if(Request::method()) {
            $userdata = Request::param();
            // return $userdata;
            $randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
            $rand = substr($randStr,0,6);
            $data = [
                'LogId'         =>    $rand,
                'CreateTime'    =>    date("Y-m-d H:i:s"),
                'Content'       =>    $userdata['Content'],
                'State'         =>    $userdata['State']
            ];
            $flag = Db::table('operalog')->insert($data);
            if ($flag) {
                return 1;
            }
        }
    }
    public function deleteoperaLog() {
        if(Request::method()) {
            $userdata = Request::param();
            $LogId = $userdata['LogId'];
            $operadata = Db::table('operalog')->where('LogId', $LogId)->delete();
            if(isset($operadata)) {
                $delete = array('code'=> 0, 'mes'=> '删除成功！');
                return json($delete);
            } else {
                $delete = array('code'=> 1, 'mes'=> '删除失败！');
                return json($delete);
            }
        }
    }
}