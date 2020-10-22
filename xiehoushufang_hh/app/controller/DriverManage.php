<?php
namespace app\controller;
use app\model\User;
use think\facade\Request;
use think\facade\Db;


class DriverManage {
    public function getTableData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            if(Request::method()){
                $usermanageData = Db::table('houmanuser')->where('userRole', 3)->select();
                for ($i = 0; $i < count($usermanageData); $i++) {
                    $insertdata = [
                        'DriverId'    =>   $usermanageData[$i]['Mid'],
                        'Mid'         =>   $usermanageData[$i]['Mid'],
                        'ManName'     =>   $usermanageData[$i]['ManName'],
                        'ManPhone'    =>   $usermanageData[$i]['ManPhone'],
                        'ManImage'    =>   $usermanageData[$i]['ManImage'],
                        'isDelete'    =>   0
                    ];
                    // $operadata = Db::table('driverManage')->where('isDelete', 0)->withoutField('isDelete')->limit(($currentPage-1)*$pageSize, $pageSize)->select();
                    $isUp = Db::table('driverManage')->where('Mid', $usermanageData[$i]['Mid'])->find();
                    if ($isUp) {
                        Db::table('driverManage')->update($insertdata);
                    } else {
                        Db::table('driverManage')->insert($insertdata);
                    }
                }
                $isUpdate = Db::table('driverManage')->where('isDelete', 0)->withoutField('isDelete')->limit(($currentPage-1)*$pageSize, $pageSize)->select();
                $pageTotal = Db::table('driverManage')->where('isDelete', 0)->count();
                if (isset($isUpdate)) {
                    $data = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $isUpdate, 'pageTotal'=> $pageTotal);
                    return json($data);
                } else {
                    $data = array('code'=> 1, 'mes'=> '查询失败！');
                    return json($data);
                }
                // $pageTotal = Db::table('driverManage')->where('isDelete', 0)->count();
                    // return $usermanageData;
                // $returndata = array('pageTotal'=> $pageTotal, 'data'=> $operadata);
                // return $returndata;
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
    public function updateDriver() {
        if(Request::method()){
            $gettabledata = Request::param();
            $Mid = $gettabledata['Mid'];
                    // 省市区
                    $DriverProvince = $gettabledata['DriverProvince'];
                    $DriverCity = $gettabledata['DriverCity'];
                    $DriverQu = $gettabledata['DriverQu'];
                    $DriverProvinceName = Db::table('province')->where('code', $DriverProvince)->find();
                    $DriverCityName = Db::table('city')->where('code', $DriverCity)->find();
                    $DriverQuName = Db::table('area')->where('code', $DriverQu)->find();
            $updatedata = [
                'Mid'         =>   $gettabledata['Mid'],
                'DriverId'    =>   $gettabledata['DriverId'],
                'ManName'     =>   $gettabledata['ManName'],
                // 'ManSex'      =>   $gettabledata['ManSex'],
                'ManPhone'    =>   $gettabledata['ManPhone'],
                'DriverProvince'    =>   $gettabledata['DriverProvince'],
                'DriverProvinceName'    =>   $DriverProvinceName['name'],
                'DriverCity' =>   $gettabledata['DriverCity'],
                'DriverCityName' =>   $DriverCityName['name'],
                'DriverQu'    =>   $gettabledata['DriverQu'],
                'DriverQuName'    =>   $DriverQuName['name'],
                'InvitationCode'  =>  $gettabledata['InvitationCode']
            ];
            if(isset($Mid)) {
                $ManData = Db::table('driverManage');
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
    public function deleteDriverData() {
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
            $DriverProvince = $gettabledata['DriverProvince'];
            $DriverCity = $gettabledata['DriverCity'];
            $DriverQu = $gettabledata['DriverQu'];

            $whereMid = $whereManName = $whereStoreProvince = $whereStoreCity = $whereStoreQu = '';
            if ($Mid != '' || $Mid != null) {
                $whereMid = "and Mid like '%" .$Mid. "%' "; 
            }
            if ($ManName != '' || $ManName != null) {
                $whereManName = "and ManName like '%" .$ManName. "%' "; 
            }
            if ($DriverProvince != '' || $DriverProvince != null) {
                $whereStoreProvince = "and DriverProvince like '%" .$DriverProvince. "%' "; 
            }
            if ($DriverCity != '' || $DriverCity != null) {
                $whereStoreCity = "and DriverCity like '%" .$DriverCity. "%' "; 
            }
            if ($DriverQu != '' || $DriverQu != null) {
                $whereStoreQu = "and DriverQu like '%" .$DriverQu. "%' "; 
            }
            $res = Db::query("SELECT * From driverManage Where 1 "
            .$whereMid .$whereManName .$whereStoreProvince .$whereStoreCity .$whereStoreQu
            ."LIMIT " . ($currentPage - 1)*$pageSize .','. $pageSize);
            $count = sizeof(Db::query("SELECT * From driverManage Where 1 "
            .$whereMid .$whereManName .$whereStoreProvince .$whereStoreCity .$whereStoreQu));
            if ($res) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $res, 'pageTotal'=> $count);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '没有查询到相关信息！', 'data'=> $res, 'pageTotal'=> 0);
                return json($reach);
            }
        }
    }
    public function findProvince() {
        if(Request::method()){
            $gettabledata = Request::param();
            $res = Db::table('province')->select();
            if ($res) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $res);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '查询失败！');
                return json($reach);
            }
        }
    }
    public function findCity() {
        if(Request::method()){
            $gettabledata = Request::param();
            $provicecode = $gettabledata['provicecode'];
            $res = Db::table('city')->where('provincecode', $provicecode)->select();
            if ($res) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $res);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '查询失败！');
                return json($reach);
            }
        }
    }
    public function findQu() {
        if(Request::method()){
            $gettabledata = Request::param();
            $citycode = $gettabledata['citycode'];
            $res = Db::table('area')->where('citycode', $citycode)->select();
            if ($res) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'data'=> $res);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '查询失败！');
                return json($reach);
            }
        }
    }
    public function findInvitation() {
        if(Request::method()){
            $gettabledata = Request::param();
            $res = Db::table('storeManage')->where('isDelete', 0)->select();
            if ($res) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'Invitation'=> $res);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '查询失败！');
                return json($reach);
            }
        }
    }
}