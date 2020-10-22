<?php
namespace app\controller;
use app\model\User;
use think\db\Query;
use think\facade\Request;
use think\facade\Db;


class UserManage {
    public function getTableData() {
        if(Request::method()){
            // $userdata = Request::param();
            // return json($userdata);
            // // dump($userdata);
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            if(Request::method()){
                $operadata = Db::table('houmanuser')->where('isDelete', 0)->field('ManName,Mid,ManSex,ManPhone,userRole,ManImage')->limit(($currentPage-1)*$pageSize, $pageSize)->select();
                $pageTotal = Db::table('houmanuser')->where('isDelete', 0)->count();
                $returndata = array('pageTotal'=> $pageTotal, 'data'=> $operadata);
                return $returndata;
            }
        }
    }
    public function addUserData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $Mid = $gettabledata['Mid'];
            $ManData = Db::table('houmanuser');
            $panduanMid = $ManData->where('Mid', $Mid)->find();
            $insertdata = [
                'Mid'         =>   $gettabledata['Mid'],
                'ManName'     =>   $gettabledata['ManName'],
                'ManSex'      =>   $gettabledata['ManSex'],
                'ManPhone'    =>   $gettabledata['ManPhone'],
                'userRole'    =>   $gettabledata['userRole'],
                'ManPassword' =>   $gettabledata['ManPassword'],
                'ManImage'    =>   $gettabledata['ManImage'],
                'isDelete'    =>   0
            ];
            if(isset($panduanMid)) {
                $data = array('code'=> 2, 'mes'=> '用户ID已存在！请重新输入！');
                return json($data);
            } else {
                // if ($gettabledata['userRole'] == 3) {
                //     $randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
                //     $rand = substr($randStr,0,6);
                //     $insertDriver = [
                //         'DriverId'    =>   $rand,
                //         'Mid'         =>   $gettabledata['Mid'],
                //         'ManName'     =>   $gettabledata['ManName'],
                //         'ManPhone'    =>   $gettabledata['ManPhone'],
                //         'DriverProvince'    =>   '',
                //         'DriverCity'    =>   '',
                //         'DriverQu'    =>   '',
                //         'isDelete'    =>   0
                //     ];
                //     Db::table('driverManage')->insert($insertDriver);
                // }
                $flag = $ManData->insert($insertdata);
                if ($flag) {
                    $data = array('code'=> 0, 'mes'=> '新增成功！');
                    return json($data);
                } else {
                    $data = array('code'=> 1, 'mes'=> '新增失败！');
                    return json($data);
                }
            }
        }
    }
    public function updateUserData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $Mid = $gettabledata['Mid'];
            $updatedata = [
                'Mid'         =>   $gettabledata['Mid'],
                'ManName'     =>   $gettabledata['ManName'],
                'ManSex'      =>   $gettabledata['ManSex'],
                'ManPhone'    =>   $gettabledata['ManPhone'],
                'userRole'    =>   $gettabledata['userRole'],
                // 'ManPassword' =>   $gettabledata['ManPassword'],
                'ManImage'    =>   $gettabledata['ManImage']
            ];
            if(isset($Mid)) {
                $ManData = Db::table('houmanuser');
                $panduanMid = $ManData->where('Mid', $Mid)->update($updatedata);
                if (isset($panduanMid)) {
                    $update = array('code'=> 0, 'mes'=> '更新成功！');
                    return json($update);
                } else {
                    $update = array('code'=> 1, 'mes'=> '更新失败！');
                    return json($update);
                }
            } else {
                $update = array('code'=> 1, 'mes'=> '找不到该用户！');
                return json($update);
            }
        }
    }
    public function deleteUserData() {
        if(Request::method()){
            $gettabledata = Request::param();
            // return $gettabledata['array'];
            $getdata = $gettabledata['array'];
            // return $data;
            $data = array();
            $data = $getdata;
            if (is_array($data)) {
                foreach ($data as $value) {
                    // return $value;
                    $res = Db::table('houmanuser')->where('Mid', $value)->save(['isDelete'=>1]);
                    if ($res) {
                        $success = 1;
                    } else {
                        $success = 0;
                    }
                }
                if($success) {
                    $delete = array('code'=> 0, 'mes'=> '删除成功！');
                    return json($delete);
                } else {
                    $delete = array('code'=> 1, 'mes'=> '删除失败！');
                    return json($delete);
                }
            }
        }
    }
    public function reachUserData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            $Mid = $gettabledata['Mid'];
            $ManName = $gettabledata['ManName'];
            $userRole = $gettabledata['userRole'];

            $whereMid = $whereManName = $whereuserRole = '';
            if ($Mid != '' || $Mid != null) {
                $whereMid = "and Mid like '%" .$Mid. "%' "; 
            }
            if ($ManName != '' || $ManName != null) {
                $whereManName = "and ManName like '%" .$ManName. "%' "; 
            }
            if ($userRole != '' || $userRole != null) {
                $whereuserRole = "and userRole like '%" .$userRole. "%' "; 
            }
            $res = Db::query("SELECT Mid,ManName,ManSex,ManPhone,userRole,ManImage From houmanuser Where isDelete = 0 "
            .$whereMid .$whereManName .$whereuserRole
            ."LIMIT " . ($currentPage - 1)*$pageSize .','. $pageSize);
            $count = sizeof(Db::query("SELECT ManName,Mid,ManSex,ManPhone,userRole,ManImage From houmanuser Where isDelete = 0 "
            .$whereMid .$whereManName .$whereuserRole));
            if ($res) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $res, 'pageTotal'=> $count);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '没有查询到相关信息！', 'data'=> $res, 'pageTotal'=> 0);
                return json($reach);
            }
        }
    }
}