<?php
namespace app\controller;
use app\model\User;
use think\facade\Request;
use think\facade\Db;

class StoreManage {
    public function getTableData() {
        if(Request::method()){
            // $userdata = Request::param();
            // return json($userdata);
            // // dump($userdata);
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            if(Request::method()){
                $userdata = Db::table('storeManage')->where('isDelete', 0)->withoutField('isDelete')->limit(($currentPage-1)*$pageSize, $pageSize)->select();
                $pageTotal = Db::table('storeManage')->where('isDelete', 0)->count();
                $returndata = array('pageTotal'=> $pageTotal, 'data'=> $userdata);
                return $returndata;
            }
        }
    }
    public function reachStoreData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $currentPage = $gettabledata['currentPage'];
            $pageSize = $gettabledata['pageSize'];
            $StoreId = $gettabledata['StoreId'];
            $StoreName = $gettabledata['StoreName'];
            $StoreProvince = $gettabledata['StoreProvince'];
            $StoreCity = $gettabledata['StoreCity'];
            $StoreQu = $gettabledata['StoreQu'];

            $whereMid = $whereManName = $whereStoreProvince = $whereStoreCity = $whereStoreQu = '';
            if ($StoreId != '' || $StoreId != null) {
                $whereMid = "and StoreId like '%" .$StoreId. "%' "; 
            }
            if ($StoreName != '' || $StoreName != null) {
                $whereManName = "and StoreName like '%" .$StoreName. "%' "; 
            }
            if ($StoreProvince != '' || $StoreProvince != null) {
                $whereStoreProvince = "and StoreProvince like '%" .$StoreProvince. "%' "; 
            }
            if ($StoreCity != '' || $StoreCity != null) {
                $whereStoreCity = "and StoreCity like '%" .$StoreCity. "%' "; 
            }
            if ($StoreQu != '' || $StoreQu != null) {
                $whereStoreQu = "and StoreQu like '%" .$StoreQu. "%' "; 
            }
            $res = Db::query("SELECT * From storeManage Where isDelete = 0 "
            .$whereMid .$whereManName .$whereStoreProvince .$whereStoreCity .$whereStoreQu
            ."LIMIT " . ($currentPage - 1)*$pageSize .','. $pageSize);
            $count = sizeof(Db::query("SELECT * From storeManage Where isDelete = 0 "
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
    public function getProCityQu() {
        if(Request::method()){
            // $gettabledata = Request::param();
            $province = Db::table('province')->select();
            $city = Db::table('city')->select();
            $area = Db::table('area')->select();

            if ($province) {
                $reach = array('code'=> 0, 'mes'=> '查询成功！', 'province'=> $province, 'city'=> $city, 'area'=> $area);
                return json($reach);
            }else {
                $reach = array('code'=> 1, 'mes'=> '查询失败！');
                return json($reach);
            }
        }
    }
    public function addStoreData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $StoreId = $gettabledata['StoreId'];
            $StoreData = Db::table('storeManage');
            $panduanMid = $StoreData->where('StoreId', $StoreId)->find();
            // 省市区
            $StoreProvince = $gettabledata['StoreProvince'];
            $StoreCity = $gettabledata['StoreCity'];
            $StoreQu = $gettabledata['StoreQu'];
            $StoreProvinceName = Db::table('province')->where('code', $StoreProvince)->find();
            $StoreCityName = Db::table('city')->where('code', $StoreCity)->find();
            $StoreQuName = Db::table('area')->where('code', $StoreQu)->find();

            $insertdata = [
                'StoreId'         =>   $gettabledata['StoreId'],
                'StoreName'     =>   $gettabledata['StoreName'],
                'StorePhone'      =>   $gettabledata['StorePhone'],
                'StoreProvince'    =>   $gettabledata['StoreProvince'],
                'StoreProvinceName'    =>   $StoreProvinceName['name'],
                'StoreCity'    =>   $gettabledata['StoreCity'],
                'StoreCityName'    =>   $StoreCityName['name'],
                'StoreQu' =>   $gettabledata['StoreQu'],
                'StoreQuName' =>   $StoreQuName['name'],
                'StoreAdress' =>   $gettabledata['StoreAdress'],
                'StoreManName' =>   $gettabledata['StoreManName'],
                'InvitationCode'    =>   $gettabledata['InvitationCode'],
                'isDelete'    =>   0
            ];
            if(isset($panduanMid)) {
                $data = array('code'=> 2, 'mes'=> '门店ID已存在！请重新输入！');
                return json($data);
            } else {
                $flag = $StoreData->insert($insertdata);
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
    public function updateStoreData() {
        if(Request::method()){
            $gettabledata = Request::param();
            $StoreId = $gettabledata['StoreId'];
                // 省市区
                $StoreProvince = $gettabledata['StoreProvince'];
                $StoreCity = $gettabledata['StoreCity'];
                $StoreQu = $gettabledata['StoreQu'];
                $StoreProvinceName = Db::table('province')->where('code', $StoreProvince)->find();
                $StoreCityName = Db::table('city')->where('code', $StoreCity)->find();
                $StoreQuName = Db::table('area')->where('code', $StoreQu)->find();
            $updatedata = [
                'StoreId'         =>   $gettabledata['StoreId'],
                'StoreName'     =>   $gettabledata['StoreName'],
                'StorePhone'      =>   $gettabledata['StorePhone'],
                'StoreProvince'    =>   $gettabledata['StoreProvince'],
                'StoreProvinceName'    =>   $StoreProvinceName['name'],
                'StoreCity'    =>   $gettabledata['StoreCity'],
                'StoreCityName'    =>   $StoreCityName['name'],
                'StoreQu' =>   $gettabledata['StoreQu'],
                'StoreQuName' =>   $StoreQuName['name'],
                'StoreAdress' =>   $gettabledata['StoreAdress'],
                'StoreManName' =>   $gettabledata['StoreManName'],
                'InvitationCode'    =>   $gettabledata['InvitationCode']
            ];
            if(isset($StoreId)) {
                $StoreData = Db::table('storeManage');
                $panduanMid = $StoreData->where('StoreId', $StoreId)->update($updatedata);
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
    public function deleteStoreData() {
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
                    $res = Db::table('storeManage')->where('StoreId', $value)->save(['isDelete'=>1]);
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
}